# v2 - interactive layer

Files changed: header.php, front-page.php, assets/css/main.css, assets/js/main.js,
template-parts/{hero,packages,locations,gallery,faq,booking}.php. New: template-parts/videos.php.

- Map: real Google Maps iframe (keyless `output=embed`), beach chips + "Show on map" buttons, tap-to-unlock so it never traps page scroll.
- Hero: slow push-in, parallax, two-layer animated waves, count-up numbers.
- Header: scroll progress bar, scrollspy, animated mobile menu.
- Packages: "how many people?" picker highlights the best-fit lesson and pre-fills the booking form.
- Gallery: 13 photos, grid never leaves holes, lightbox with arrows / keyboard / swipe / caption / counter.
- Videos: new section, YouTube loads only after click (youtube-nocookie).
- FAQ: smooth accordion, "+" rotates to "x" (old transition never ran), live search.
- Booking: surfers stepper, copy-message button.
- Scroll reveal on a few lists only; everything honours prefers-reduced-motion.
- Removed 6 byte-identical unreferenced images (5.3 MB).
