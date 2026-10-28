(function () {
	'use strict';

	var hoverMQ = window.matchMedia ? window.matchMedia('(hover: hover) and (pointer: fine)') : null;

	function isMobile() {
		if (navigator.userAgentData && navigator.userAgentData.mobile === true) {
			return true;
		}
		return /Android|iPhone|iPad|iPod|Mobile|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
			(navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1); // iPadOS
	}

	/* WhatsApp: app su mobile/tablet, WhatsApp Web su desktop */
	function setupWhatsAppLinks(scope) {
		var mobile = isMobile();
		(scope || document).querySelectorAll('[data-web-url]').forEach(function (a) {
			if (mobile) {
				a.href = a.dataset.mobileUrl;
				a.removeAttribute('target');
			} else {
				a.href = a.dataset.webUrl;
				a.target = '_blank';
			}
		});
	}

	/* ---------- Menu contatti ---------- */
	function setOpen(root, open) {
		if (!root) { return; }
		var btn = root.querySelector('.lu3g-wa__toggle');
		var menu = root.querySelector('.lu3g-wa__menu');

		root.classList.toggle('is-open', open);
		if (btn) {
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			btn.setAttribute('aria-label', open ? btn.dataset.labelClose : btn.dataset.labelOpen);
		}
		if (menu) {
			if (open) { menu.removeAttribute('inert'); } else { menu.setAttribute('inert', ''); }
		}
		if (open) { root._lu3gOpenedAt = Date.now(); }
	}

	function closeAll(except) {
		document.querySelectorAll('.lu3g-wa--menu.is-open').forEach(function (r) {
			if (r !== except) { setOpen(r, false); }
		});
	}

	document.addEventListener('click', function (e) {
		var t = e.target;
		if (!(t instanceof Element)) { return; }

		var root = t.closest('.lu3g-wa--menu');
		closeAll(root); // clic fuori: chiude gli altri menu aperti

		var toggle = t.closest('.lu3g-wa__toggle');
		if (toggle && root) {
			e.preventDefault();
			var justOpenedByHover = root.dataset.hover === '1' && hoverMQ && hoverMQ.matches &&
				root._lu3gOpenedAt && (Date.now() - root._lu3gOpenedAt < 600);
			setOpen(root, justOpenedByHover ? true : !root.classList.contains('is-open'));
			return;
		}

		var item = t.closest('.lu3g-wa__item');
		if (item && root && root.dataset.closeOnClick === '1') {
			setTimeout(function () { setOpen(root, false); }, 200);
		}
	});

	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') { return; }
		document.querySelectorAll('.lu3g-wa--menu.is-open').forEach(function (r) {
			var hadFocus = r.contains(document.activeElement);
			setOpen(r, false);
			var btn = r.querySelector('.lu3g-wa__toggle');
			if (hadFocus && btn) { btn.focus(); }
		});
	});

	function setupHover(scope) {
		if (!hoverMQ || !hoverMQ.matches) { return; }
		(scope || document).querySelectorAll('.lu3g-wa--menu[data-hover="1"]').forEach(function (r) {
			if (r.dataset.hoverBound) { return; }
			r.dataset.hoverBound = '1';
			var timer;
			r.addEventListener('mouseenter', function () {
				clearTimeout(timer);
				if (!r.classList.contains('is-open')) { setOpen(r, true); }
			});
			r.addEventListener('mouseleave', function () {
				timer = setTimeout(function () { setOpen(r, false); }, 300);
			});
		});
	}

	/* ---------- Barra mobile ---------- */

	// Rifrazione del vetro: i filtri SVG sullo sfondo (backdrop-filter: url()) funzionano
	// solo nei browser Chromium. Altrove la barra resta vetro smerigliato.
	(function detectRefraction() {
		var brands = navigator.userAgentData && navigator.userAgentData.brands;
		var chromium = !!(brands && brands.some(function (b) { return /Chromium/i.test(b.brand); }));
		if (chromium) {
			document.documentElement.classList.add('lu3g-wa-can-refract');
		}
	})();

	// Safari iOS attiva :active sui link solo se esiste un listener touch
	document.addEventListener('touchstart', function () {}, { passive: true });

	// Spazio in fondo alla pagina pari all'ingombro reale della barra (altezza + distanza dal basso)
	function updateBarSpace() {
		var bar = document.querySelector('.lu3g-wa-bar');
		var rootStyle = document.documentElement.style;
		if (!bar || window.getComputedStyle(bar).display === 'none') {
			rootStyle.removeProperty('--lu3g-wa-bar-space');
			return;
		}
		var space = Math.ceil(window.innerHeight - bar.getBoundingClientRect().top);
		if (space > 0) {
			rootStyle.setProperty('--lu3g-wa-bar-space', space + 'px');
		}
	}
	var barTimer;
	window.addEventListener('resize', function () {
		clearTimeout(barTimer);
		barTimer = setTimeout(updateBarSpace, 150);
	});
	// dopo l'animazione di entrata la posizione è quella definitiva
	window.addEventListener('load', function () { setTimeout(updateBarSpace, 450); });

	function init(scope) {
		setupWhatsAppLinks(scope);
		setupHover(scope);
		setTimeout(updateBarSpace, 450);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { init(); });
	} else {
		init();
	}

	// Refresh live nell'editor Elementor
	window.addEventListener('elementor/frontend/init', function () {
		if (window.elementorFrontend && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction('frontend/element_ready/lu3g_whatsapp_sticky.default', function ($scope) {
				init($scope[0]);
			});
		}
	});
})();
