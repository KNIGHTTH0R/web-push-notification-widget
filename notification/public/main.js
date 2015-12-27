'use strict';

var API_KEY = "AIzaSyCzvaekWSLHG7FAf-IV3QS3bBJMvdY6k1s";//window.GoogleSamples.Config.gcmAPIKey;
var GCM_ENDPOINT = 'https://android.googleapis.com/gcm/send';
var PF_HOST = 'https://bebetter.in';
var REGISTER_ENDPOINT = PF_HOST + '/subscriber';
var NOTIFY_ENDPOINT = PF_HOST + 'notification/analytics';
//var IP_ENDPOINT = PF_HOST + '/delivery/ip';

var logging = true;
var isPushEnabled = false;

// This method handles the removal of subscriptionId
// in Chrome 44 by concatenating the subscription Id
// to the subscription endpoint
function endpointWorkaround(pushSubscription) {
    // Make sure we only mess with GCM
    if (pushSubscription.endpoint.indexOf('https://android.googleapis.com/gcm/send') !== 0) {
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

    var request = new Request(REGISTER_ENDPOINT, {
        method: 'POST',
        mode: 'cors',
        redirect: 'follow',
        body: JSON.stringify({
            "did": endpointSections[endpointSections.length - 1],
            "user_id": "566c79a717fa0bd070fe5e9a",
            "browser": deviceInfo.browser,
            "platform": deviceInfo.platform
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    fetch(request).then(function (response) {
        if (logging) console.log(response);
        return response.json();
    }).then(function (json) {
        if (logging) console.log(json);
    }).catch(function (error) {
        console.log(error);
    });
}

function unsubscribe() {
    navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
        // To unsubscribe from push messaging, you need get the
        // subcription object, which you can call unsubscribe() on.
        serviceWorkerRegistration.pushManager.getSubscription().then(
            function (pushSubscription) {
                // Check we have a subscription to unsubscribe
                if (!pushSubscription) {
                    // No subscription object, so set the state
                    // to allow the user to subscribe to push
                    isPushEnabled = false;
                    return;
                }

                // TODO: Make a request to your server to remove
                // the users data from your data store so you
                // don't attempt to send them push messages anymore

                // We have a subcription, so call unsubscribe on it
                pushSubscription.unsubscribe().then(function (successful) {
                    isPushEnabled = false;
                }).catch(function (e) {
                    // We failed to unsubscribe, this can lead to
                    // an unusual state, so may be best to remove
                    // the subscription id from your data store and
                    // inform the user that you disabled push

                    window.Demo.debug.log('Unsubscription error: ', e);
                });
            }).catch(function (e) {
                window.Demo.debug.log('Error thrown while unsubscribing from ' +
                    'push messaging.', e);
            });
    });
}

function subscribe() {
    // Disable the button so it can't be changed while
    // we process the permission request
    navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
            .then(function (subscription) {
                // The subscription was successful
                isPushEnabled = true;

                return sendSubscriptionToServer(subscription);
            })
            .catch(function (e) {
                if (Notification.permission === 'denied') {
                    // The user denied the notification permission which
                    // means we failed to subscribe and the user will need
                    // to manually change the notification permission to
                    // subscribe to push messages
                    window.Demo.debug.log('Permission for Notifications was denied');
                } else {
                    // A problem occurred with the subscription, this can
                    // often be down to an issue or lack of the gcm_sender_id
                    // and / or gcm_user_visible_only
                    window.Demo.debug.log('Unable to subscribe to push.', e);
                }
            });
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

                // Set your UI to show they have subscribed for
                // push messages
                 isPushEnabled = true;
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
        if (logging) console.log('Geolocation is supported!');
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
            console.log('Error occurred. Error code: ' + error.code);
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
        if (logging) console.log('Geolocation is not supported for this Browser/OS version yet.');
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