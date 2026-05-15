const CACHE_NAME = 'grozeo-v1';
const STATIC_ASSETS = [
    '/css/',
    '/js/',
    '/lib/',
    '/fonts/',
    '/images/'
];

self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            )
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);

    if (event.request.method !== 'GET') return;

    const isStaticAsset = STATIC_ASSETS.some(path => url.pathname.startsWith(path)) ||
        /\.(css|js|woff2?|ttf|eot|svg|png|jpe?g|gif|webp|ico)(\?.*)?$/i.test(url.pathname);

    if (isStaticAsset) {
        event.respondWith(
            caches.match(event.request).then(cached => {
                if (cached) return cached;
                return fetch(event.request).then(response => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                    }
                    return response;
                });
            })
        );
        return;
    }

    if (url.pathname.startsWith('/api/') || url.pathname.startsWith('/Health/')) return;

    event.respondWith(
        fetch(event.request).then(response => {
            if (response.ok && event.request.headers.get('accept')?.includes('text/html')) {
                const clone = response.clone();
                caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
            }
            return response;
        }).catch(() => caches.match(event.request))
    );
});
