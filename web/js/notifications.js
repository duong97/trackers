/*
 *
 *  Push Notifications codelab
 *  Copyright 2015 Google Inc. All rights reserved.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License
 *
 */

/* eslint-env browser, es6 */

'use strict';

const applicationServerPublicKey    = 'BMLB5XX7UU0TrXOviBR6ujT44cDp2s9wBlRoZyEm8jKXUuBQ4-9KhTbNVwT85Ah3V-N_pqDgPKJz9ZCy1J2E5Ys'; // remote
//const applicationServerPublicKey    = 'BIqS3-JFcnphDyK9-Moc1zYua_JSDDFNAw0B2N3r6SIGtw1AA-Yw9Nuba-Zqc-xB27jF9dNn_NcMJ9tNu6oEAxg'; // local

let isSubscribed                    = false;
let swRegistration                  = null;
var urlHandleSubscription           = null;

function urlB64ToUint8Array(base64String) {
    const padding       = '='.repeat((4 - base64String.length % 4) % 4);
    const base64        = (base64String + padding)
                            .replace(/\-/g, '+')
                            .replace(/_/g, '/');

    const rawData       = window.atob(base64);
    const outputArray   = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i]  = rawData.charCodeAt(i);
    }
    return outputArray;
}

/* Check if Service Worker is supported */
function bindNotifications(elm, eventName, urlHandle, urlSwJs){
    if ('serviceWorker' in navigator && 'PushManager' in window) {
//        console.log('Service Worker and Push is supported');

        navigator.serviceWorker.register(urlSwJs)
                .then(function (swReg) {
//                    console.log('Service Worker is registered', swReg);

                    swRegistration          = swReg;
                    urlHandleSubscription   = urlHandle;
                    initializeUI(elm, eventName);
                })
                .catch(function (error) {
                    console.error('Service Worker Error', error);
                });
    } else {
        console.warn('Push messaging is not supported');
    }
}

/* which will check if the user is currently subscribed */
function initializeUI(elm, eventName) {
    elm.on(eventName, function () {
        if ($(this).is(':checked')) {
            subscribeUser();
        } else {
            unsubscribeUser();
        }
    });

    // Set the initial subscription value
    swRegistration.pushManager.getSubscription()
            .then(function (subscription) {
                isSubscribed = !(subscription === null);

                if (isSubscribed) {
                    console.log('User is subscribed.');
                } else {
                    console.log('User is NOT subscribed.');
                }

                updateBtn();
            });
}

/* subscribe User */
function subscribeUser() {
    const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    swRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
            .then(function (subscription) {
                console.log('Subscribed successful.');
//                console.log('Subscription info:');
//                console.log(subscription);
                updateSubscriptionOnServer(subscription);

                isSubscribed = true;

                updateBtn();
            })
            .catch(function (err) {
                console.log('Failed to subscribe the user: ', err);
                updateBtn();
            });
}

/* unsubscribe User */
function unsubscribeUser() {
    swRegistration.pushManager.getSubscription()
            .then(function (subscription) {
                if (subscription) {
                    return subscription.unsubscribe();
                }
            })
            .catch(function (error) {
                console.log('Error unsubscribing', error);
            })
            .then(function () {
                updateSubscriptionOnServer(null);

                console.log('Subscribed successful.');
                isSubscribed = false;

                updateBtn();
            });
}

/* enable our button and change the text if the user is subscribed or not */
function updateBtn() {
    // Check if user block notifications
    if (Notification.permission === 'denied') {
        updateSubscriptionOnServer(null);
        return;
    }
}

/* send our subscription to a backend */
function updateSubscriptionOnServer(subscription) {
//    console.log('subscription server info:');
//    console.log(JSON.stringify(subscription));

    if (subscription) {
        $.ajax({
            'url': urlHandleSubscription,
            'data': {
                'subscription': JSON.stringify(subscription),
            },
            'success': function (data) {
                console.log('Notify success');
                console.log(data);
            }
        });
    }
}

