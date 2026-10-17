/**
 * Bali Paradise Surf Lesson - vanilla JS, no dependencies.
 *
 * Header (progress, scrollspy, mobile nav) - hero parallax - count-up numbers -
 * scroll reveal - group-size picker - interactive map - FAQ accordion + search -
 * booking (stepper, live WhatsApp preview, copy) - lightbox (arrows, swipe) -
 * click-to-load YouTube. Everything respects prefers-reduced-motion.
 */
(function () {
	'use strict';

	var doc = document;
	var reduce = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
	var hasIO = 'IntersectionObserver' in window;

	function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }
	function easeOut(t) { return 1 - Math.pow(1 - t, 3); }

	/* ---------- Header, progress bar, hero parallax (one rAF-throttled handler) ---------- */
	var header = $('.site-header');
	var bar = $('.progress span');
	var heroMedia = $('[data-parallax]');
	var navLinks = $$('.nav a[href*="#"]:not(.btn)');
	var ticking = false;

	function clearCurrent() {
		navLinks.forEach(function (a) { a.removeAttribute('aria-current'); });
	}

	function onScroll() {
		var y = window.pageYOffset || 0;
		if (header) { header.classList.toggle('is-scrolled', y > 24); }
		if (bar) {
			var max = doc.documentElement.scrollHeight - window.innerHeight;
			bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, y / max) : 0).toFixed(4) + ')';
		}
		if (heroMedia && !reduce && y < window.innerHeight * 1.2) {
			heroMedia.style.transform = 'translate3d(0,' + (y * 0.15).toFixed(1) + 'px,0)';
		}
		if (y < 120) { clearCurrent(); }
		ticking = false;
	}
	function requestTick() {
		if (!ticking) { ticking = true; window.requestAnimationFrame(onScroll); }
	}
	onScroll();
	window.addEventListener('scroll', requestTick, { passive: true });
	window.addEventListener('resize', requestTick);

	/* ---------- Scrollspy: highlight the nav link of the section in view ---------- */
	if (hasIO && navLinks.length) {
		var byId = {};
		navLinks.forEach(function (a) {
			var id = (a.getAttribute('href').split('#')[1] || '');
			if (id) { byId[id] = a; }
		});
		var spy = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting || (window.pageYOffset || 0) < 120) { return; }
				clearCurrent();
				var link = byId[entry.target.id];
				if (link) { link.setAttribute('aria-current', 'true'); }
			});
		}, { rootMargin: '-45% 0px -50% 0px' });
		Object.keys(byId).forEach(function (id) {
			var section = doc.getElementById(id);
			if (section) { spy.observe(section); }
		});
	}

	/* ---------- Mobile navigation ---------- */
	var toggle = $('.nav-toggle');
	var nav = doc.getElementById('site-nav');

	function setNav(open) {
		if (!toggle || !nav) { return; }
		nav.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (header) { header.classList.toggle('nav-open', open); }
	}
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			setNav(toggle.getAttribute('aria-expanded') !== 'true');
		});
		nav.addEventListener('click', function (e) {
			if (e.target.closest('a')) { setNav(false); }
		});
		doc.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
				setNav(false);
				toggle.focus();
			}
		});
	}

	/* ---------- Count-up for the hero numbers ("2,500+", "4.9/5", "98%") ---------- */
	$$('.stats dd').forEach(function (el) {
		var raw = el.textContent.trim();
		var m = /^(\D*)([\d,]*\.?\d+)(.*)$/.exec(raw);
		if (!m || reduce || !hasIO) { return; }
		var target = parseFloat(m[2].replace(/,/g, ''));
		var dec = (m[2].split('.')[1] || '').length;
		var sep = m[2].indexOf(',') > -1;

		function fmt(v) {
			var s = v.toFixed(dec);
			if (sep) {
				var parts = s.split('.');
				parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
				s = parts.join('.');
			}
			return m[1] + s + m[3];
		}

		el.textContent = fmt(0);
		var io = new IntersectionObserver(function (entries) {
			if (!entries[0].isIntersecting) { return; }
			io.disconnect();
			var t0 = null;
			(function step(ts) {
				if (t0 === null) { t0 = ts; }
				var p = Math.min(1, (ts - t0) / 1400);
				el.textContent = p < 1 ? fmt(target * easeOut(p)) : raw;
				if (p < 1) { window.requestAnimationFrame(step); }
			})(performance.now());
		}, { threshold: 0.6 });
		io.observe(el);
	});

	/* ---------- Scroll reveal (only items that start below the fold, so nothing flashes) ---------- */
	if (hasIO && !reduce) {
		var revealIO = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					var el = entry.target;
					el.classList.add('is-in');
					revealIO.unobserve(el);
					// Once it has faded in, hand the element back to its normal styles
					// (so hover / picker states are not overridden by the reveal rule).
					window.setTimeout(function () {
						el.removeAttribute('data-reveal');
						el.classList.remove('is-in');
						el.style.removeProperty('--i');
					}, 900);
				}
			});
		}, { threshold: 0.12 });
		['.values li', '.packages .package', '.steps li', '.quotes .quote', '.locations .location'].forEach(function (sel) {
			$$(sel).forEach(function (el, i) {
				if (el.getBoundingClientRect().top < window.innerHeight * 0.92) { return; }
				el.setAttribute('data-reveal', '');
				el.style.setProperty('--i', i % 4);
				revealIO.observe(el);
			});
		});
	}

	/* ---------- Booking form -> WhatsApp ---------- */
	var form = doc.getElementById('booking-form');
	var preview = doc.getElementById('wa-preview');

	function money(n) {
		return 'Rp' + Math.round(n).toLocaleString('id-ID');
	}

	function formatDate(iso) {
		var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(iso || '');
		if (!m) { return ''; }
		var d = new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]));
		return d.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' });
	}

	function val(name) {
		var el = form.elements[name];
		return el ? String(el.value || '').trim() : '';
	}

	function buildMessage() {
		var select = form.elements['package'];
		var opt = select && select.options[select.selectedIndex];
		var people = Math.max(1, parseInt(val('people'), 10) || 1);
		var lines = ['Hello Bali Paradise Surf Lesson! I would like to book a surf lesson.', ''];

		if (val('guest_name')) { lines.push('Name: ' + val('guest_name')); }

		if (opt) {
			var price = Number(opt.getAttribute('data-price')) || 0;
			var unit = opt.getAttribute('data-unit') || 'person';
			var pkg = opt.getAttribute('data-name') || '';
			lines.push('Lesson: ' + pkg + (price ? ' (' + money(price) + ' / ' + unit + ')' : ''));
		}

		lines.push('Surfers: ' + people);
		if (val('ages')) { lines.push('Ages: ' + val('ages')); }
		if (val('date')) { lines.push('Date: ' + formatDate(val('date'))); }
		if (val('time')) { lines.push('Time: ' + val('time')); }
		if (val('level')) { lines.push('Experience: ' + val('level')); }
		if (val('stay')) { lines.push('Staying at: ' + val('stay')); }
		if (val('notes')) { lines.push('Notes: ' + val('notes')); }

		if (opt) {
			var p = Number(opt.getAttribute('data-price')) || 0;
			var u = opt.getAttribute('data-unit') || 'person';
			if (p && u === 'person') {
				lines.push('', 'Estimated total: ' + money(p * people) + ' (to be confirmed)');
			}
		}
		lines.push('', 'Thank you!');
		return lines.join('\n');
	}

	function updatePreview() {
		if (preview && form) { preview.textContent = buildMessage(); }
	}

	function setError(field, show) {
		var wrap = field.closest('.field');
		var msg = wrap && wrap.querySelector('.field__error');
		field.setAttribute('aria-invalid', show ? 'true' : 'false');
		if (msg) {
			msg.hidden = !show;
			if (show) {
				if (!msg.id) { msg.id = field.id + '-error'; }
				field.setAttribute('aria-describedby', msg.id);
			}
		}
	}

	function validate() {
		var firstBad = null;
		Array.prototype.forEach.call(form.querySelectorAll('[required]'), function (field) {
			var bad = !field.checkValidity() || !String(field.value).trim();
			setError(field, bad);
			if (bad && !firstBad) { firstBad = field; }
		});
		if (firstBad) { firstBad.focus(); }
		return !firstBad;
	}

	if (form) {
		var dateInput = form.elements['date'];
		if (dateInput) {
			var t = new Date();
			dateInput.min = t.getFullYear() + '-' + String(t.getMonth() + 1).padStart(2, '0') + '-' + String(t.getDate()).padStart(2, '0');
		}

		form.addEventListener('input', updatePreview);
		form.addEventListener('change', updatePreview);

		form.addEventListener('submit', function (e) {
			e.preventDefault();
			if (!validate()) { return; }
			var number = (form.getAttribute('data-wa') || '').replace(/\D+/g, '');
			var base = number ? 'https://wa.me/' + number : 'https://wa.me/';
			var url = base + '?text=' + encodeURIComponent(buildMessage());
			var win = window.open(url, '_blank', 'noopener');
			if (!win) { window.location.href = url; }
		});

		// "Book this lesson" buttons preselect the package.
		Array.prototype.forEach.call(doc.querySelectorAll('[data-select-package]'), function (btn) {
			btn.addEventListener('click', function () {
				var select = form.elements['package'];
				if (select) {
					select.value = btn.getAttribute('data-select-package');
					updatePreview();
				}
			});
		});

		updatePreview();
	}

	/* ---------- Group-size picker: recommends the best-fitting lesson ---------- */
	var picker = $('[data-picker]');
	if (picker) {
		var cards = $$('.packages .package');
		var result = $('.picker__result', picker);
		var opts = $$('.picker__opt', picker);

		var bestFor = function (n) {
			var pick = null;
			var largest = null;
			cards.forEach(function (c) {
				var cap = parseInt(c.getAttribute('data-capacity'), 10) || 0;
				if (!cap) { return; }
				if (!largest || cap > parseInt(largest.getAttribute('data-capacity'), 10)) { largest = c; }
				if (cap >= n && (!pick || cap < parseInt(pick.getAttribute('data-capacity'), 10))) { pick = c; }
			});
			return pick || largest;
		};

		opts.forEach(function (btn) {
			btn.addEventListener('click', function () {
				var wasOn = btn.getAttribute('aria-pressed') === 'true';
				opts.forEach(function (o) { o.setAttribute('aria-pressed', 'false'); });
				cards.forEach(function (c) { c.classList.remove('is-match', 'is-dim'); });
				result.hidden = true;
				if (wasOn) { return; }

				var card = bestFor(parseInt(btn.getAttribute('data-size'), 10) || 1);
				if (!card) { return; }
				btn.setAttribute('aria-pressed', 'true');
				cards.forEach(function (c) { c.classList.add(c === card ? 'is-match' : 'is-dim'); });

				var title = $('h3', card);
				var price = $('.price', card);
				result.textContent = 'Best fit for your group: ' + (title ? title.textContent.trim() : 'this lesson') +
					(price ? ' - ' + price.textContent.replace(/\s+/g, ' ').trim() : '') + ' ';
				var go = doc.createElement('a');
				go.className = 'btn btn--sun btn--sm';
				go.href = '#book';
				go.textContent = 'Book this lesson';
				go.addEventListener('click', function () {
					var src = $('[data-select-package]', card);
					var f = doc.getElementById('booking-form');
					if (f && src) {
						if (f.elements['package']) { f.elements['package'].value = src.getAttribute('data-select-package'); }
						if (f.elements['people']) { f.elements['people'].value = btn.getAttribute('data-people') || '1'; }
						updatePreview();
					}
				});
				result.appendChild(go);
				result.hidden = false;
			});
		});
	}

	/* ---------- Interactive Google Map (keyless iframe embed) ---------- */
	var mapBox = $('[data-map]');
	if (mapBox) {
		var spots = [];
		try { spots = JSON.parse(mapBox.getAttribute('data-spots') || '[]'); } catch (err) { spots = []; }
		var embed = $('.location-map__embed', mapBox);
		var frame = $('.location-map__frame', mapBox);
		var lock = $('.location-map__lock', mapBox);
		var chips = $$('[data-map-chip]', mapBox);
		var nameEl = $('[data-map-name]', mapBox);
		var addrEl = $('[data-map-addr]', mapBox);
		var dirEl = $('[data-map-dir]', mapBox);
		var ratingEl = $('[data-map-rating]', mapBox);

		var showSpot = function (i, bringIntoView) {
			var s = spots[i];
			if (!s || !frame) { return; }
			chips.forEach(function (c) {
				c.setAttribute('aria-pressed', String(parseInt(c.getAttribute('data-map-chip'), 10) === i));
			});
			embed.classList.add('is-loading');
			frame.src = 'https://www.google.com/maps?q=' + encodeURIComponent(s.q) + '&z=15&output=embed';
			frame.title = 'Map: ' + s.name;
			if (nameEl) { nameEl.textContent = s.name; }
			if (addrEl) { addrEl.textContent = s.addr; }
			if (dirEl) { dirEl.href = s.dir; }
			if (ratingEl) { ratingEl.hidden = i !== 0; }
			if (bringIntoView) {
				mapBox.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
			}
		};

		if (frame) {
			frame.addEventListener('load', function () { embed.classList.remove('is-loading'); });
		}
		chips.forEach(function (c) {
			c.addEventListener('click', function () { showSpot(parseInt(c.getAttribute('data-map-chip'), 10), false); });
		});
		$$('[data-map-spot]').forEach(function (b) {
			b.addEventListener('click', function () { showSpot(parseInt(b.getAttribute('data-map-spot'), 10), true); });
		});

		// The map only captures scroll/touch after a tap, so it never traps the page.
		if (lock && embed) {
			lock.addEventListener('click', function () { embed.classList.remove('is-locked'); });
			embed.addEventListener('pointerleave', function (e) {
				if (e.pointerType === 'mouse') { embed.classList.add('is-locked'); }
			});
		}
	}

	/* ---------- FAQ: animated accordion + live search ---------- */
	var faqItems = $$('.faq__list details');

	function toggleFaq(d, open) {
		var sum = $('summary', d);
		var ans = $('.faq__answer', d);
		d.classList.toggle('is-open', open);
		if (d._anim) { d._anim.cancel(); d._anim = null; }
		if (reduce || !d.animate || !sum || !ans) { d.open = open; return; }

		var from = d.offsetHeight;
		if (open) { d.open = true; }
		var to = open ? d.offsetHeight : from - ans.offsetHeight;
		d.style.overflow = 'hidden';
		var anim = d.animate({ height: [from + 'px', to + 'px'] }, { duration: 280, easing: 'cubic-bezier(.2,.7,.2,1)' });
		d._anim = anim;
		anim.onfinish = function () { d.open = open; d.style.overflow = ''; d._anim = null; };
		anim.oncancel = function () { d.style.overflow = ''; };
	}

	faqItems.forEach(function (d) {
		var sum = $('summary', d);
		if (d.open) { d.classList.add('is-open'); }
		if (!sum) { return; }
		sum.addEventListener('click', function (e) {
			e.preventDefault();
			var isOpen = d.classList.contains('is-open');
			if (!isOpen) {
				faqItems.forEach(function (o) {
					if (o !== d && o.classList.contains('is-open')) { toggleFaq(o, false); }
				});
			}
			toggleFaq(d, !isOpen);
		});
	});

	var faqSearch = $('.faq__search');
	var faqEmpty = $('.faq__empty');
	if (faqSearch) {
		faqSearch.addEventListener('input', function () {
			var q = faqSearch.value.trim().toLowerCase();
			var hits = 0;
			faqItems.forEach(function (d) {
				var hit = !q || d.textContent.toLowerCase().indexOf(q) > -1;
				d.hidden = !hit;
				if (hit) { hits++; }
			});
			if (faqEmpty) { faqEmpty.hidden = hits > 0; }
		});
	}


	/* ---------- Booking extras: surfers stepper + copy message ---------- */
	$$('.stepper__btn').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var input = $('input', btn.parentNode);
			if (!input) { return; }
			var min = parseInt(input.min, 10) || 1;
			var max = parseInt(input.max, 10) || 20;
			var next = Math.min(max, Math.max(min, (parseInt(input.value, 10) || min) + parseInt(btn.getAttribute('data-step'), 10)));
			input.value = next;
			input.dispatchEvent(new Event('input', { bubbles: true }));
		});
	});

	var copyBtn = doc.getElementById('wa-copy');
	if (copyBtn && form) {
		var copyLabel = copyBtn.textContent;
		copyBtn.addEventListener('click', function () {
			var text = buildMessage();
			var done = function () {
				copyBtn.textContent = 'Copied!';
				window.setTimeout(function () { copyBtn.textContent = copyLabel; }, 2000);
			};
			var fallback = function () {
				var ta = doc.createElement('textarea');
				ta.value = text;
				ta.setAttribute('readonly', '');
				ta.style.cssText = 'position:fixed;opacity:0;pointer-events:none';
				doc.body.appendChild(ta);
				ta.select();
				try { if (doc.execCommand('copy')) { done(); } } catch (err) { /* ignore */ }
				doc.body.removeChild(ta);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(done, fallback);
			} else {
				fallback();
			}
		});
	}

	/* ---------- Lightbox (<dialog>): arrows, keyboard, swipe, counter ---------- */
	var dialog = doc.getElementById('lightbox');
	if (dialog && typeof dialog.showModal === 'function') {
		var links = $$('[data-lightbox]');
		var img = $('img', dialog);
		var capEl = $('.lightbox__cap', dialog);
		var cntEl = $('.lightbox__count', dialog);
		var current = 0;
		var startX = null;

		if (links.length < 2) { dialog.classList.add('lightbox--single'); }

		var preload = function (i) {
			var l = links[(i + links.length) % links.length];
			if (l) { (new Image()).src = l.getAttribute('href'); }
		};

		var load = function (i) {
			current = (i + links.length) % links.length;
			var link = links[current];
			var probe = new Image();
			dialog.classList.add('is-swapping');
			var apply = function () {
				img.src = link.getAttribute('href');
				img.alt = link.getAttribute('data-alt') || '';
				if (capEl) { capEl.textContent = img.alt; }
				if (cntEl) { cntEl.textContent = (current + 1) + ' / ' + links.length; }
				dialog.classList.remove('is-swapping');
				preload(current + 1);
				preload(current - 1);
			};
			probe.onload = apply;
			probe.onerror = apply;
			probe.src = link.getAttribute('href');
		};

		links.forEach(function (link, i) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				dialog.showModal();
				load(i);
			});
		});

		$('.lightbox__close', dialog).addEventListener('click', function () { dialog.close(); });
		var prev = $('.lightbox__nav--prev', dialog);
		var next = $('.lightbox__nav--next', dialog);
		if (prev) { prev.addEventListener('click', function () { load(current - 1); }); }
		if (next) { next.addEventListener('click', function () { load(current + 1); }); }

		dialog.addEventListener('click', function (e) {
			if (e.target === dialog) { dialog.close(); }
		});
		dialog.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') { load(current - 1); }
			if (e.key === 'ArrowRight') { load(current + 1); }
		});
		dialog.addEventListener('pointerdown', function (e) { startX = e.clientX; });
		dialog.addEventListener('pointerup', function (e) {
			if (startX === null) { return; }
			var dx = e.clientX - startX;
			startX = null;
			if (Math.abs(dx) > 50) { load(current + (dx < 0 ? 1 : -1)); }
		});
		dialog.addEventListener('close', function () { img.removeAttribute('src'); });
	}

	/* ---------- YouTube: load the player only after a click ---------- */
	$$('[data-yt]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var f = doc.createElement('iframe');
			f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(btn.getAttribute('data-yt')) + '?autoplay=1&rel=0';
			f.title = btn.getAttribute('data-title') || 'Video';
			f.allow = 'accelerometer; autoplay; encrypted-media; picture-in-picture; fullscreen';
			f.allowFullscreen = true;
			btn.replaceWith(f);
			f.focus();
		});
	});
})();
