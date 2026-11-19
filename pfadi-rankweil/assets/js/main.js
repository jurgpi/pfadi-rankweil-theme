/* Pfadi Rankweil – kleine Helfer, ohne jQuery */
(function () {
	'use strict';

	var body = document.body;
	var header = document.getElementById('site-header');

	// Schatten beim Scrollen
	if (header) {
		var onScroll = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 8);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	// Mobiles Menü
	var toggle = document.querySelector('.nav-toggle');
	if (toggle) {
		toggle.addEventListener('click', function () {
			var open = body.classList.toggle('nav-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && body.classList.contains('nav-open')) {
				body.classList.remove('nav-open');
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		});
	}

	// Untermenüs auf-/zuklappen
	document.querySelectorAll('.submenu-toggle').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			var li = btn.closest('li');
			var open = li.classList.toggle('is-open');
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	// Suche ein-/ausblenden
	var sToggle = document.querySelector('.search-toggle');
	var sBox = document.getElementById('header-search');
	if (sToggle && sBox) {
		sToggle.addEventListener('click', function () {
			var hidden = sBox.hasAttribute('hidden');
			if (hidden) {
				sBox.removeAttribute('hidden');
				body.classList.remove('nav-open');
				if (toggle) { toggle.setAttribute('aria-expanded', 'false'); }
				var f = sBox.querySelector('input');
				if (f) { f.focus(); }
			} else {
				sBox.setAttribute('hidden', '');
			}
			sToggle.setAttribute('aria-expanded', hidden ? 'true' : 'false');
		});
	}

	// Hell/Dunkel umschalten
	document.querySelectorAll('.theme-toggle').forEach(function (btn) {
		btn.addEventListener('click', function () {
			if (typeof window.pfadiToggleTheme !== 'function') { return; }
			var root = document.documentElement;
			root.classList.add('theme-anim');
			window.pfadiToggleTheme();
			setTimeout(function () { root.classList.remove('theme-anim'); }, 400);
		});
	});

	// Link kopieren
	document.querySelectorAll('.share-copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var url = btn.getAttribute('data-url');
			if (navigator.clipboard) {
				navigator.clipboard.writeText(url).then(function () {
					btn.classList.add('is-copied');
					setTimeout(function () { btn.classList.remove('is-copied'); }, 1800);
				});
			}
		});
	});
})();
