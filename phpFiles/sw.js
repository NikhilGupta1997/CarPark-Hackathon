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

/* eslint-env browser, serviceworker, es6 */

'use strict';

self.addEventListener('push', function(event) {
  console.log('[Service Worker] Push Received.');
  console.log(`[Service Worker] Push had this data elements hehehe: "${event.data.text()}"`);

  const title = 'Carpark just go filled!';
  const options = {
    body: 'Sorry, your preferred carpark just got filled, lets find you a new one.',
    icon: 'images/icon.png',
    badge: 'images/badge.png'
  };

  event.waitUntil(self.registration.showNotification(title, options));
});

// self.addEventListener('notificationclick', function(event) {
//   console.log('[Service Worker] Notification click Received.');

//   event.notification.close();

//   event.waitUntil(
//     clients.openWindow('https://developers.google.com/web/')
//   );
// });

self.addEventListener('notificationclick', event => {
    const rootUrl = new URL('http://localhost/carpark/carparkResult.php').href;
    event.notification.close();
    console.log(`[URL] "${rootUrl}"`);
    console.log(`[location] "${location}"`);
    // Enumerate windows, and call window.focus(), or open a new one.
    event.waitUntil(
      clients.matchAll().then(matchedClients => {
        for (let client of matchedClients) {
          console.log(`[client] "${client.url}"`);
          return client.focus();
        }
        return clients.openWindow("http://localhost/carpark/carparkResult.php");
      })
    );
});