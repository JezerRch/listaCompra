// service-worker.js

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open('nome-do-cache-v2').then(function (cache) {
      '/',
        'index.php',
        'img/lista-de-compras.png'
      // adicione os arquivos que deseja armazenar em cache
    })
  );
});

self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request).then(function (response) {
      return response || fetch(event.request);
    })
  );
});

if ('serviceWorker' in navigator) {
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('/service-worker.js')
      .then(function (registration) {
        console.log('Service Worker registrado com sucesso:', registration);
      })
      .catch(function (error) {
        console.log('Falha ao registrar o Service Worker:', error);
      });
  });
}

