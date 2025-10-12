const CACHE_NAME = "tienda-mil-cache-v.24";

const assets = [];

self.addEventListener("install", installEvent => {
    installEvent.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            cache.addAll(assets)
        })
    )
})
/*
self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.open(cacheName)
            .then(function (cache) {
                cache.match(event.request)
                    .then(function (cacheResponse) {
                        fetch(event.request)
                            .then(function (networkResponse) {
                                cache.put(event.request, networkResponse)
                            })
                        return cacheResponse || networkResponse
                    })
            })
    )
});
*/