const CACHE_NAME = 'budget-planner-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/page1.html',
  '/stats.html',
  '/goals.html',
  '/tips.html',
  '/feedback.html',
  '/feedback_error.html',
  '/feedback_success.html',
  '/styles.css',
  '/process_feedback.php',
  'https://cdn.jsdelivr.net/npm/chart.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(ASSETS_TO_CACHE))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
      .catch(() => {
        if (event.request.headers.get('accept').includes('text/html')) {
          return caches.match('/offline.html');
        }
      })
  );
});