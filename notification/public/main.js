'use strict';

var API_KEY = "AIzaSyCzvaekWSLHG7FAf-IV3QS3bBJMvdY6k1s";//window.GoogleSamples.Config.gcmAPIKey;
var GCM_ENDPOINT = 'https://android.googleapis.com/gcm/send';
var PF_HOST = 'https://bebetter.in';
var REGISTER_ENDPOINT = PF_HOST + '/subscriber';
var NOTIFY_ENDPOINT = PF_HOST + 'notification/analytics';
//var IP_ENDPOINT = PF_HOST + '/delivery/ip';

// var curlCommandDiv = document.querySelector('.js-curl-command');
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
    // TODO: Send the subscription.endpoint
    // to your server and save it to send a
    // push message at a later date
    //
    // For compatibly of Chrome 43, get the endpoint via
    // endpointWorkaround(subscription)
    console.log('TODO: Implement sendSubscriptionToServer() ~~~DONE');

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
            "device": deviceInfo.browser,
            "platform": deviceInfo.platform
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    fetch(request).then(function (response) {
        console.log(response);
        return response.json();
    }).then(function (json) {
        console.log(json);
    }).catch(function (error) {
        console.log(error);
    });
    // $.post('https://bebetter.in/subscribe', { "did": endpointSections[endpointSections.length - 1]}, function(response) {
    //   console.log(response);
    // });

    // This is just for demo purposes / an easy to test by
    // generating the appropriate cURL command
    console.log(showCurlCommand(mergedEndpoint));
}

// NOTE: This code is only suitable for GCM endpoints,
// When another browser has a working version, alter
// this to send a PUSH request directly to the endpoint
function showCurlCommand(mergedEndpoint) {
    // The curl command to trigger a push message straight from GCM
    if (mergedEndpoint.indexOf(GCM_ENDPOINT) !== 0) {
        window.Demo.debug.log('This browser isn\'t currently ' +
            'supported for this demo');
        return;
    }

    var endpointSections = mergedEndpoint.split('/');
    var subscriptionId = endpointSections[endpointSections.length - 1];

    var curlCommand = 'curl --header "Authorization: key=' + API_KEY +
        '" --header Content-Type:"application/json" ' + GCM_ENDPOINT +
        ' -d "{\\"registration_ids\\":[\\"' + subscriptionId + '\\"]}"';

    // curlCommandDiv.textContent = curlCommand;
    return curlCommand;
}

function unsubscribe() {
    var pushButton = document.querySelector('.js-push-button');
    // pushButton.disabled = true;
    // curlCommandDiv.textContent = '';

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
                    // pushButton.disabled = false;
                    // pushButton.textContent = 'Enable Push Messages';
                    return;
                }

                // TODO: Make a request to your server to remove
                // the users data from your data store so you
                // don't attempt to send them push messages anymore

                // We have a subcription, so call unsubscribe on it
                pushSubscription.unsubscribe().then(function (successful) {
                    // pushButton.disabled = false;
                    // pushButton.textContent = 'Enable Push Messages';
                    isPushEnabled = false;
                }).catch(function (e) {
                    // We failed to unsubscribe, this can lead to
                    // an unusual state, so may be best to remove
                    // the subscription id from your data store and
                    // inform the user that you disabled push

                    window.Demo.debug.log('Unsubscription error: ', e);
                    // pushButton.disabled = false;
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
    // var pushButton = document.querySelector('.js-push-button');
    // pushButton.disabled = true;

    navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
            .then(function (subscription) {
                // The subscription was successful
                isPushEnabled = true;
                // pushButton.textContent = 'Disable Push Messages';
                // pushButton.disabled = false;

                // TODO: Send the subscription subscription.endpoint
                // to your server and save it to send a push message
                // at a later date
                return sendSubscriptionToServer(subscription);
            })
            .catch(function (e) {
                if (Notification.permission === 'denied') {
                    // The user denied the notification permission which
                    // means we failed to subscribe and the user will need
                    // to manually change the notification permission to
                    // subscribe to push messages
                    window.Demo.debug.log('Permission for Notifications was denied');
                    // pushButton.disabled = true;
                } else {
                    // A problem occurred with the subscription, this can
                    // often be down to an issue or lack of the gcm_sender_id
                    // and / or gcm_user_visible_only
                    window.Demo.debug.log('Unable to subscribe to push.', e);
                    // pushButton.disabled = false;
                    // pushButton.textContent = 'Enable Push Messages';
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
                // var pushButton = document.querySelector('.js-push-button');
                // pushButton.disabled = false;

                if (!subscription) {
                    // We aren’t subscribed to push, so set UI
                    // to allow the user to enable push
                    return;
                }

                // Keep your server in sync with the latest subscription
                sendSubscriptionToServer(subscription);

                // Set your UI to show they have subscribed for
                // push messages
                // pushButton.textContent = 'Disable Push Messages';
                // isPushEnabled = true;
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
        console.log('Geolocation is supported!');
        var startPos;
        var geoOptions = {
            maximumAge: 5 * 60 * 1000,
            timeout: 10 * 1000,
            enableHighAccuracy: false
        };

        var geoSuccess = function(position) {
            startPos = position;
            var pos = { "success": true};
            var pos.x = startPos.coords.latitude;
            var pos.y = startPos.coords.longitude;
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
        console.log('Geolocation is not supported for this Browser/OS version yet.');
        return false;
    }
}

window.addEventListener('load', function () {
    var pushButton = document.querySelector('.js-push-button');
    // pushButton.addEventListener('click', function() {
    //   if (isPushEnabled) {
    //     unsubscribe();
    //   } else {
    //     subscribe();
    //   }
    // });
    subscribe();

    // Check that service workers are supported, if so, progressively
    // enhance and add push messaging support, otherwise continue without it.
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/worker.js')
            .then(initialiseState);
    } else {
        window.Demo.debug.log('Service workers aren\'t supported in this browser.');
    }
});