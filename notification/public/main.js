'use strict';

// This method handles the removal of subscriptionId
// in Chrome 44 by concatenating the subscription Id
// to the subscription endpoint
function endpointWorkaround(pushSubscription) {
    // Make sure we only mess with GCM
    if (pushSubscription.endpoint.indexOf(PF.gcm_endpoint) !== 0) {
        return pushSubscription.endpoint;
    }

    var mergedEndpoint = pushSubscription.endpoint;
    // Chrome 42 + 43 will not have the subscriptionId attached
    // to the endpoint.
    if (pushSubscription.subscriptionId &&
        pushSubscription.endpoint.indexOf(pushSubscription.subscriptionId) === -1) {
        // Handle version 42 where you have separate subId and Endpoint
        mergedEndpoint = pushSubscription.endpoint + '/' +
            pushSubscription.subscriptionId;
    }
    return mergedEndpoint;
}

function sendSubscriptionToServer(subscription) {

    var mergedEndpoint = endpointWorkaround(subscription);
    var endpointSections = mergedEndpoint.split('/');
    // TODO: Move Browser detection to Server
    var deviceInfo = getDeviceInfo();

    var request = new Request(PF.register_endpoint, {
        method: 'POST',
        mode: 'cors',
        redirect: 'follow',
        body: JSON.stringify({
            "did": endpointSections[endpointSections.length - 1],
            "user_id": PF.account_id,
            "browser": deviceInfo.browser,
            "platform": deviceInfo.platform
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    fetch(request).then(function (response) {
        if (PF.logging) console.log(response);
        return response.json();
    }).then(function (json) {
        if (PF.logging) console.log(json);
    }).catch(function (error) {
        console.log(error);
    });
}

// Once the service worker is registered set the initial state
function initialiseState() {
    // Are Notifications supported in the service worker?
    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        window.Demo.debug.log('Notifications aren\'t supported.');
        return;
    }

    // Check the current Notification permission.
    // If its denied, it's a permanent block until the
    // user changes the permission
    if (Notification.permission === 'denied') {
        window.Demo.debug.log('The user has blocked notifications.');
        return;
    }

    // Check if push messaging is supported
    if (!('PushManager' in window)) {
        window.Demo.debug.log('Push messaging isn\'t supported.');
        return;
    }

    // We need the service worker registration to check for a subscription
    navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
        // Do we already have a push message subscription?
        serviceWorkerRegistration.pushManager.getSubscription()
            .then(function (subscription) {
                // Enable any UI which subscribes / unsubscribes from
                // push messages.

                if (!subscription) {
                    // We aren’t subscribed to push, so set UI
                    // to allow the user to enable push
                    return;
                }

                // Keep your server in sync with the latest subscription
                sendSubscriptionToServer(subscription);

            })
            .catch(function (err) {
                window.Demo.debug.log('Error during getSubscription()', err);
            });
    });
}

function getDeviceInfo() {
    var info = {};
    var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
    var isIE = /*@cc_on!@*/false || !!document.documentMode; // At least IE6
    if (isOpera) {
        info.browser = "opera";
    }
    else if (isFirefox) {
        info.browser = "firefox";
    }
    else if (isChrome) {
        info.browser = "chrome";
    }
    else if (isSafari) {
        info.browser = "safari";
    }
    else {
        info.browser = "ie";
    }
    if (navigator.userAgent.match(/iPhone/i)) {
        info.platform = "iphone";
    }
    else if (navigator.userAgent.match(/iPad/i)) {
        info.platform = "ipad";
    }
    else if (navigator.userAgent.match(/Andriod/i)) {
        info.platform = "android";
    }
    else if (navigator.userAgent.match(/Linux/i)) {
        info.platform = "linux";
    }
    else if (navigator.userAgent.match(/Windows/i)) {
        info.platform = "windows";
    }
    else if (navigator.userAgent.match(/Mac/i)) {
        info.platform = "osx";
    }
    return info;
}

function getLocation() {
    if (navigator.geolocation) {
        if (PF.logging) console.log('Geolocation is supported!');
        var startPos;
        var geoOptions = {
            maximumAge: 5 * 60 * 1000,
            timeout: 10 * 1000,
            enableHighAccuracy: false
        };

        var geoSuccess = function(position) {
            startPos = position;
            var pos = { "success": true};
            pos.x = startPos.coords.latitude;
            pos.y = startPos.coords.longitude;
            return pos;
        };
        var geoError = function(position) {
            if(PF.logging) console.log('Error occurred. Error code: ' + error.code);
            return {"success": false, "reason": error.code};
            // error.code can be:
            //   0: unknown error
            //   1: permission denied
            //   2: position unavailable (error response from location provider)
            //   3: timed out
        };

        navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
    }
    else {
        if (PF.logging) console.log('Geolocation is not supported for this Browser/OS version yet.');
        return false;
    }
}

window.addEventListener('load', function () {
    //subscribe();

    // Check that service workers are supported, if so, progressively
    // enhance and add push messaging support, otherwise continue without it.
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/worker.js')
            .then(initialiseState);
    } else {
        window.Demo.debug.log('Service workers aren\'t supported in this browser.');
    }
});