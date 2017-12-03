var cacheName = 'giftroom';

var filesToCache = [
  '/',
  '/css/app.css',
  '/js/app.js'
];

self.addEventListener('install', function (e){
  e.waitUntil(
    caches.open(cacheName).then(function (cache){
      return cache.addAll(filesToCache);
    })
  );
});

self.addEventListener('activate', function (e){
  e.waitUntil(
    caches.keys().then(function (keyList){
      return Promise.all(keyList.map(function (key){
        if ( key !== cacheName && key !== dataCacheName ) {
          return caches.delete(key);
        }
      }));
    })
  );
  return self.clients.claim();
});

self.addEventListener('fetch', function (e){
  // todo add fetch code here
});