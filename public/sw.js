const CACHE_NAME = 'trial-class-v1';
const STATIC_CACHE = 'static-v1';
const IMAGES_CACHE = 'images-v1';
const DYNAMIC_CACHE = 'dynamic-v1';

const STATIC_ASSETS = [
  '/',
  '/index.php',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  '/images/logo.png',
  '/images/icons/icon-72x72.png',
  '/images/icons/icon-96x96.png',
  '/images/icons/icon-128x128.png',
  '/images/icons/icon-144x144.png',
  '/images/icons/icon-152x152.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-384x384.png',
  '/images/icons/icon-512x512.png',
  '/offline.html'
];

// Install event - cache static assets
self.addEventListener('install', function(event) {
  console.log('Service Worker: Installing...');

  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(function(cache) {
        console.log('Service Worker: Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(function() {
        console.log('Service Worker: Installation complete');
        return self.skipWaiting();
      })
      .catch(function(err) {
        console.error('Service Worker: Installation failed', err);
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', function(event) {
  console.log('Service Worker: Activating...');

  event.waitUntil(
    caches.keys()
      .then(function(cacheNames) {
        return Promise.all(
          cacheNames.map(function(cacheName) {
            if (cacheName !== STATIC_CACHE && 
                cacheName !== IMAGES_CACHE && 
                cacheName !== DYNAMIC_CACHE) {
              console.log('Service Worker: Deleting old cache', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(function() {
        console.log('Service Worker: Activation complete');
        return clients.claim();
      })
  );
});

// Fetch event - handle requests
self.addEventListener('fetch', function(event) {
  console.log('Service Worker: Fetching', event.request.url);

  // Handle different types of requests differently
  if (event.request.method !== 'GET') {
    // For non-GET requests, try network and fail gracefully
    event.respondWith(
      fetch(event.request)
        .then(function(response) {
          return response;
        })
        .catch(function(err) {
          console.error('Service Worker: Non-GET request failed', err);
          return new Response('', { status: 500 });
        })
    );
    return;
  }

  // Handle image requests
  if (event.request.destination === 'image') {
    event.respondWith(
      caches.open(IMAGES_CACHE)
        .then(function(cache) {
          return fetch(event.request)
            .then(function(networkResponse) {
              // Clone and cache the image for future requests
              cache.put(event.request, networkResponse.clone());
              return networkResponse;
            })
            .catch(function() {
              // Try to serve from cache if network fails
              return cache.match(event.request)
                .then(function(response) {
                  return response || caches.match('/images/icons/icon-192x192.png'); // fallback
                });
            });
        })
    );
    return;
  }

  // Handle API requests (for dynamic content)
  if (event.request.url.includes('/api/') || 
      event.request.url.includes('/livewire/')) {
    event.respondWith(
      fetch(event.request)
        .then(function(networkResponse) {
          // Don't cache API responses that might be user-specific
          if (networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            // Cache responses conditionally based on headers or URL patterns
            if (shouldCacheResponse(event.request, responseClone)) {
              caches.open(DYNAMIC_CACHE)
                .then(function(cache) {
                  cache.put(event.request, responseClone);
                });
            }
          }
          return networkResponse;
        })
        .catch(function() {
          // Try to serve cached response if network fails
          return caches.match(event.request)
            .then(function(response) {
              return response || caches.match('/offline.html'); // fallback page
            });
        })
    );
    return;
  }

  // For all other requests (HTML, CSS, JS)
  event.respondWith(
    caches.open(STATIC_CACHE)
      .then(function(cache) {
        return fetch(event.request)
          .then(function(networkResponse) {
            // Update cache for future requests
            cache.put(event.request, networkResponse.clone());
            return networkResponse;
          })
          .catch(function() {
            // Try to serve from cache if network fails
            return cache.match(event.request)
              .then(function(response) {
                return response || caches.match('/offline.html') || 
                  new Response('Offline: Please check your connection and try again', {
                    status: 200,
                    headers: { 'Content-Type': 'text/html' }
                  });
              });
          });
      })
  );
});

// Helper function to determine if a response should be cached
function shouldCacheResponse(request, response) {
  // Don't cache responses for authenticated users or with specific headers
  const headers = response.headers;
  if (headers.get('Cache-Control') && 
      (headers.get('Cache-Control').includes('no-cache') || 
       headers.get('Cache-Control').includes('private'))) {
    return false;
  }
  
  // For this app, we'll be selective about what dynamic content to cache
  const url = request.url;
  if (url.includes('/courses') || url.includes('/lessons')) {
    // Cache course and lesson content
    return true;
  }
  
  // Default: don't cache dynamic API responses
  return false;
}

// Listen for messages from the application
self.addEventListener('message', function(event) {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  } else if (event.data && event.data.type === 'CLEAR_CACHE') {
    // Clear all caches
    event.waitUntil(
      caches.keys()
        .then(function(cacheNames) {
          return Promise.all(
            cacheNames.map(function(cacheName) {
              return caches.delete(cacheName);
            })
          );
        })
    );
  }
});