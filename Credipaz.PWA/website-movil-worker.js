const CACHE_NAME = "website-pwa-cache-v3.00";
const OFFLINE_URL = "offline.html";
const assets = [];

self.addEventListener("install", installEvent => {
    installEvent.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            cache.addAll(assets)
        })
    )
})

self.addEventListener('activate', event => {
    event.waitUntil(
        (async () => {
            const keys = await caches.keys();
            return keys.map(async (cache) => {if (cache !== CACHE_NAME) {return await caches.delete(cache);}})
        })()
    )
})
