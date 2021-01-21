const staticCacheName = "BrainyWater 1.0";
const filesToCache = [
	// Pages
	"index.html",
	"entrar/index.html",

	// Manifest
	"manifest.json",

	// Icons
	"assets/icons/icon-72x72.png",
	"assets/icons/icon-96x96.png",
	"assets/icons/icon-128x128.png",
	"assets/icons/icon-144x144.png",
	"assets/icons/icon-152x152.png",
	"assets/icons/icon-192x192.png",
	"assets/icons/icon-384x384.png",
	"assets/icons/icon-512x512.png",
	"assets/images/icons/favicon.png",

	// Estilo
	"assets/css/style.css",

	// Imagens
	"assets/images/about.svg",
	"assets/images/header-logo-dark.svg",
	"assets/images/landing-1.svg",
	"assets/images/landing-2.svg",
	"assets/images/landing-3.svg",
	"assets/images/landing-4.svg",
	"assets/images/landing-5.svg",
	"assets/images/landing-page-header.svg",
	"assets/images/landing-separator-1.svg",
	"assets/images/landing-separator-2.svg",
	"assets/images/landing-separator-3.svg",
	"assets/images/landing-separator-4.svg",
	"assets/images/landing-separator-5.svg",
	"assets/images/login.svg",
	"assets/images/sign-up.svg",
	"assets/images/icons/close.svg",
	"assets/images/icons/droplet.svg",
	"assets/images/icons/email-icon.svg",
	"assets/images/icons/email.svg",
	"assets/images/icons/flow-blue.svg",
	"assets/images/icons/flow.svg",
	"assets/images/icons/hide-password.svg",
	"assets/images/icons/home.svg",
	"assets/images/icons/lamp.svg",
	"assets/images/icons/learn-more.svg",
	"assets/images/icons/lock.svg",
	"assets/images/icons/logout.svg",
	"assets/images/icons/main-header.svg",
	"assets/images/icons/pencil.svg",
	"assets/images/icons/person-icon.svg",
	"assets/images/icons/reservoir-blue.svg",
	"assets/images/icons/reservoir.svg",
	"assets/images/icons/white-brain.svg"
];

// Cache on install
this.addEventListener("install", (event) => {
	this.skipWaiting();
	event.waitUntil(
		caches.open(staticCacheName).then((cache) => {
			return cache.addAll(filesToCache);
		})
	);
});

// Clear cache on activate
this.addEventListener("activate", (event) => {
	event.waitUntil(
		caches.keys().then((cacheNames) => {
			return Promise.all(
				cacheNames
					.filter((cacheName) => cacheName !== staticCacheName)
					.map((cacheName) => caches.delete(cacheName))
			);
		})
	);
});

// Serve from Cache
this.addEventListener("fetch", (event) => {
	event.respondWith(
		caches
			.match(event.request)
			.then((response) => {
				return response || fetch(event.request);
			})
			.catch(() => {
				return caches.match("offline.html");
			})
	);
});