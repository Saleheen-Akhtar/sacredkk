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

## Final Fixes & Cascade Resolution (Round 2)
- **P0 Build Updates:** Included `founders-slider.js` in `scripts/build-assets.js` to fix the broken Collective page slider. Successfully regenerated the dist outputs. Also ensured `legacy.css` has no loose CSS syntax falling outside of the `@layer` brace. Fixed `template-parts/home/about.php` background image variable loading incorrectly.
- **P1 Component Layering:** Pushed all unlayered modular section CSS files inside `@layer components {}` and responsive CSS files inside `@layer responsive {}` allowing `legacy.css` and base values to appropriately compute using CSS cascade architecture.
- **P1 Hero & Nav Cascades:** Fixed `hero.css` and `.nav:not(.scrolled)` bug where elements overlapped improperly by injecting `.home .sk-sidenav:not(.sk-sidenav--visible)` with transparency instead. Reset `body { padding-left: 0 }` inside `theme.css`. Removed duplicate layout padding rules like `.about-section` off of `about.php`.
- **P2 Extraction:** Extracted component CSS for `sections/founders.css`, `sections/values.css`, and `sections/footer.css` straight out of `legacy.css` logic to reduce bundle size and separate concerns. Assigned name identifier `dom.onReady('Nav')` for the navigation initialization inside `nav.js`.
- **P3 Dynamic Resizing:** Exchanged rigid `body { padding-bottom: 100px }` hardcode into CSS variable `--sidenav-height: 82px;` to adapt dynamically with sizing. Purged `.strip--circular::before` from Legacy code to fix overlay rendering bugs over the new structure.

## Final Round of Audit Fixes
- **Critical Architecture Fixes:** Placed `legacy.css` first securely at position 0 inside the `build-assets.js` order preventing bundle side effects. Wrapped 100% of legacy styles properly into `@layer legacy`.
- **Responsive Recovery:** Appended three previously missing build array configurations for `cta.css`, `journal-preview.css`, and `newsletter.css` directly restoring dynamic grid mobile sizing capabilities that had regressed.
- **Specifics Cleanup:** Cleaned ghost rule conflicts like `.sk-progress` and removed multiple competing unlayered padding structures over `body`, `.about-section` and `.journal-preview-section`. Cleaned out hardcoded inline values for JS configuration arrays directly returning JSON using minification flags on PHP templates `hero.php` and `offerings.php`.
- **Colors Standardization:** Unified legacy inline hex strings such as `#fff` or `#c94f4f` to custom tokens `--color-white`, `--color-error` directly mapping properties globally inside `theme.css`. Removed static hardware overrides over GSAP elements inside `.strip--circular` philosophy file.

## Design System Migration & Variable Normalization
- **CSS Architecture Swap:** Replaced all base tone static tokens inside `theme.css` with a new, 7-tiered semantic abstraction scale allowing precise cross-referencing and consistent component consumption.
- **Component File Migrations:** Ran mass replacements safely shifting values inside `typography.css`, `buttons.css`, `about.css`, `offerings.css`, `founders.css`, `footer.css`, `faq.css`, `hero.css`, `editorial.css`, and multiple other nested assets. Migrated standard transitions into variable aliases.
- **Focus Verification:** Handled accessibility by adding robust `.focus-visible` hooks avoiding silent loss on element styling overriding standard `:focus` boundaries.
- **JS Timings Standardization:** Updated `motion-config.js` manually pushing config specs explicitly to match the corresponding durations encoded dynamically into CSS arrays.
