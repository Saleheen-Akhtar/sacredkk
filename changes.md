# Sacred Kompass Theme - P0, P1, P2 Fixes

## CSS — The Legacy Problem
- **P0:** Fixed the build script path in `scripts/build-assets.js` for `buttons.css` (`assets/css/components/buttons.css` to `assets/css/core/buttons.css`).
- **P0:** Removed the old `.nav-*` block (~75 lines of dead code) from `assets/css/sections/navigation.css`.
- **P1:** Removed dead CSS code blocks from `assets/css/core/legacy.css`:
  - Old offerings grid (`.offerings-section`, `.offering-card`, `.offering-*`)
  - Old founders (`.founder-card-*`)
  - Old FAQ (`.faq-section`)
- **P1:** Removed `wp_add_inline_style` workaround in `functions.php` and correctly solved the `background-image` layering and conflicts using CSS variables on `.strip--circular` and elements via PHP dynamically mapping.
- **P2:** Replaced hardcoded images in `assets/css/core/legacy.css` and `assets/css/sections/about.css` with CSS variables dynamically rendered via ACF/PHP `sk_option` (`--about-bg`, `--stories-bg`, etc).
- **P2:** Moved `.philosophy-strip-header` and `.philosophy-strip-intro` out of `legacy.css` into `assets/css/sections/philosophy-strip.css`.

## JavaScript — Structural Issues & Animation Architecture
- **P1:** Removed 5 empty zero-byte JS files that were polluting the repo (`main.js`, `gsap-animations.js`, `journal-filter.js`, `split-type.min.js`, `scroll-timelines.js` ghost file).
- **P2:** Removed hardcoded `cssText` animation properties from `assets/js/components/philosophy-strip.js` and replaced them with CSS state classes (`.ct-img--active`, `.ct-img--left`, etc.) and CSS custom properties inside `assets/css/sections/philosophy-strip.css`.
- **P2:** Moved hardcoded animation timing values (`charDuration`, `charStagger`, `blur`, `lerp`) into `assets/js/core/motion-config.js` and updated `assets/js/components/hero.js` and `assets/js/core/scroll-timelines.js` to reference the configuration.
- **P2:** Fixed a double-invocation initialization bug in `assets/js/animations/reveal.js` and `stagger.js` by removing the `document.readyState` load event listener, as they are already bound via `dom.onReady`.
- **P2:** Corrected the missing identifier in `dom.onReady()` within `assets/js/components/philosophy-strip.js`.
- **P2:** Unhooked the unused `founders-slider.js` from the build script (`scripts/build-assets.js`).

## General Build & Setup Updates
- Evaluated `template-parts/home/client-stories.php` and left it intact for future integrations.
- Successfully ran `node scripts/build-assets.js` after all source changes to rebuild `app.min.css` and `app.min.js`.

## P3 & Long Term
- **P3 Build Quality:** Integrated `clean-css` and `terser` into the build process replacing the fragile regex logic. Cleaned up `node_modules` from tracking by fixing the `.gitignore`.
- **P3 PHP Cleanup:** Removed the duplicate logo fallback conditional branch in `header.php`. Extracted hardcoded `home_url('/#contact')` string repeats in `header.php` to use the global `$sk_nav_cta_url` variable.
- **Long Term:** Created `assets/css/core/layout.css` starting the massive task to deconstruct the monolithic `legacy.css` into modular layout constructs. Reconfigured `scripts/build-assets.js` to build it properly.
