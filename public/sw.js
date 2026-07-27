const CACHE_VERSION = 'aeh-shell-v2';

const SHELL_ASSETS = [
    '/manifest.json',
    '/offline.html',
    '/favicon.ico',
    '/assets/logo.webp',
    '/assets/logo.ico',
];

const STATIC_ASSET_PATTERN = /\.(css|js|png|jpg|jpeg|svg|ico|woff2?)$/i;

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION).then((cache) => cache.addAll(SHELL_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((names) => Promise.all(
            names
                .filter((name) => name !== CACHE_VERSION)
                .map((name) => caches.delete(name))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    if (request.method !== 'GET' || new URL(request.url).origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match('/offline.html'))
        );
        return;
    }

    const url = new URL(request.url);
    const isStaticAsset = url.pathname.startsWith('/assets/')
        || url.pathname.startsWith('/build/')
        || STATIC_ASSET_PATTERN.test(url.pathname);

    if (isStaticAsset) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) {
                    return cached;
                }

                return fetch(request).then((response) => {
                    const responseClone = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => cache.put(request, responseClone));
                    return response;
                });
            })
        );
    }
});
