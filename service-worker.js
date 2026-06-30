// اسم الكاش - غيّره عند تحديث التطبيق
const CACHE_NAME = 'dlil-syria-v1';
const urlsToCache = [
  '/dlil/',
  '/dlil/css/app.css',
  '/dlil/js/app.js',
  '/dlil/images/icon-192x192.png',
  '/dlil/images/icon-512x512.png'
];

// تثبيت Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// تفعيل Service Worker وحذف الكاش القديم
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// استراتيجية: محاولة الشبكة أولاً، ثم الكاش
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // نسخ الاستجابة للكاش
        const responseClone = response.clone();
        caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, responseClone);
        });
        return response;
      })
      .catch(() => {
        return caches.match(event.request);
      })
  );
});

// === إشعارات OneSignal (اختياري) ===
// يمكنك إضافة كود OneSignal هنا إذا أردت