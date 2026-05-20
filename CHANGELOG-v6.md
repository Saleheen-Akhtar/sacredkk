# Sacred Kompass v6 — Change Log

## v24.0.0 Changes — Performance, UX & Code Quality Audit

### Performance Fixes

1. **Carousel rAF → GSAP ticker** (`gsap-animations.js` — `initOfferingsCarousel`)
   - Replaced the raw `requestAnimationFrame` loop with `gsap.ticker.add(...)`.
   - The GSAP scheduler automatically pauses when the browser tab is hidden, applies lag smoothing, and integrates natively with Lenis — eliminating continuous CPU burn on background tabs.

2. **`will-change: transform` on animated elements** (`style.css`)
   - Added `will-change: transform` to `.about-right-large` (GSAP parallax watermark).
   - `.parallax-slow`, `.parallax-med`, `.parallax-fast`, and `.hero-bg-image img` were already correctly set.
   - Hint is scoped only to elements that are actually animated — not applied globally.

3. **Consolidated `ScrollTrigger.refresh()`** (`gsap-animations.js` — `init`)
   - Removed the double-fire: `document.fonts.ready.then(...)` + bare `requestAnimationFrame(...)` fired within the same frame on fast font loads, triggering two full layout recalculations.
   - Replaced with a single debounced `scheduleRefresh()` (60 ms) called by both `fonts.ready` and `window.resize`.
   - Also adds the previously missing resize handler — layout-dependent ScrollTriggers are now refreshed after viewport changes.

### UX / Layout Fixes

4. **SplitType stale DOM on resize** (`gsap-animations.js` — `initSplitText`)
   - `SplitType` spans were never reverted on window resize, causing misaligned character animations at new viewport widths.
   - Instances now stored in `splits[]`; on resize, each is `.revert()`-ed before `doSplit()` re-initialises them. Debounced at 200 ms.

5. **Scroll-spy RAF throttle** (`main.js`)
   - The section loop was iterating over all sections and reading `offsetTop` on every single scroll tick (~6 DOM reads/frame at 60fps).
   - Wrapped in `requestAnimationFrame` with a `spyTicking` flag — DOM reads are now batched to at most one per frame.

6. **Featured card arrow — keyboard focus & accessibility** (`style.css`)
   - Arrow circle had no focus-visible state, making it invisible to keyboard users who could only see it on mouse hover.
   - Added `.sk-jp-featured-link:focus-visible .sk-jp-feat-arrow` rule matching the terra hover state with a 2px outline.
   - `aria-hidden="true"` was already present in the PHP template (no change needed).

7. **Mobile nav overlay close — internal anchors only** (`main.js`)
   - `closeMenu` was firing for all overlay link clicks, clearing `body overflow` before navigation completed on slower connections, leaving the page scrollable during load.
   - Scoped to `href.startsWith('#') || href.startsWith('/#')` only — external and page-change links no longer trigger the close handler.

### Code Quality Fixes

8. **`scrolled` class race condition removed** (`gsap-animations.js` — `initNav`)
   - `initNav()` contained a `ScrollTrigger.create` that toggled `.scrolled` on every scroll update in parallel with `main.js`, causing double DOM mutations per tick.
   - `initNav()` is now an intentional no-op. `main.js` is the sole owner of all nav class logic.

9. **Form submit handler — fragile pattern replaced** (`main.js`)
   - `submitBtn._clickHandler` with manual `removeEventListener`/re-`addEventListener` is lost if WordPress or a plugin re-renders the button.
   - Replaced with `form.addEventListener('submit', ...)` — button-agnostic, fires once per submission, survives re-renders.

### Content Fix

10. **Side card excerpt legibility** (`style.css`)
    - Side journal cards were showing a single barely-legible line of excerpt at ~12px.
    - Font size bumped from `0.82rem` to `0.8125rem` (~13px) and `-webkit-line-clamp` updated to show 2 lines minimum.

### Still Required (Editorial / WordPress Admin)

- **[HIGH]** Unpublish or draft the three placeholder posts ("testing", "Testing", "no idea which category is this") before next traffic push. The featured post slot gets the most visibility — prioritise replacing it first.
- **[MEDIUM]** Rename the "Journal" category to a topical label (e.g. "Reflections", "Insights") so badge labels reflect content, not page type.
- **[LEAN UX]** Write one hypothesis per key section (contact, journal, collective) with a success metric and 30-day timeframe.
- **[LEAN UX]** Add an exit-intent poll (Tally/Typeform) or Hotjar session recording to `/collective` and `/journal`.

---



### New Files
- **`searchform.php`** — Custom search form override. Renders a branded search bar using theme tokens (`--border`, `--ivory`, `--font-display`) instead of the default WordPress input. Injected into `home.php` and `category.php` above the journal filter tabs.

### Feature Additions
1. **Search bar on archive pages** (`home.php`, `category.php`) — `get_search_form()` injected directly above the `<nav class="sk-journal-tabs">` element on both archive templates.
2. **Newsletter section** (`home.php`, `category.php`, `single.php`) — Forminator newsletter block injected above `get_footer()` on all three templates. Replace `YOUR_FORM_ID_HERE` with the actual Forminator form ID in the WP admin.

### Bug Fixes
3. **Escaped `<em>` in Related Articles heading** (`single.php`) — `esc_html_e('Related <em>Articles</em>')` was stripping the HTML tag and rendering it as literal text. Split into two separate calls: `esc_html_e('Related')` + `<em><?php esc_html_e('Articles') ?></em>`.

### Design Upgrades (`style.css`)
4. **Editorial blog card layout** — Replaced the white-box 16:9 SaaS card with a transparent editorial card: no background, no border, no box-shadow on the container. Image switches to portrait `4/5` aspect ratio with shadow on the image only. Excerpt now uses `--font-display` (Cormorant Garamond) for a luxury feel. Footer divider changed to a subtle gold line.
5. **Editorial featured post layout** — Replaced the side-by-side white card with a transparent `1.1fr / 0.9fr` grid. Featured image now portrait `4/5` with `--shadow-lift`. Body text floats freely on the parchment background. Collapses to single-column below 1024px.
6. **Post hero image wide-out** — Hero image expanded from `880px / 520px` to `1140px / 600px` — wider than the reading column for a cinematic editorial feel. Mobile override added: `height: 40vh`. `.sk-post-body` padding updated to `5rem 2.5rem` to match.
7. **Jetpack Like button tamed** — Default heading and "Like this:" label hidden. Widget wrapped in subtle gold-tinted borders. iframe filtered to warm greyscale at rest, full colour on hover — blends seamlessly with the luxury aesthetic.

---

## v22.0.0 Changes — Bug Fixes & Touch Device Polish

### Bug Fixes

1. **Journal Tabs — Undefined CSS Variables** (`style.css`)
   - `.sk-journal-tab` was referencing `--sk-muted` and `--sk-gold`, which are not defined in `:root`. The browser was silently falling back to hardcoded hex values (`#888`, `#c49a2a`), bypassing the design token system entirely.
   - Fixed: replaced with the correct global tokens `--ink-muted` and `--gold`.

2. **Sticky Hover States on Touch Devices** (`style.css`)
   - `.founder-card`, `.sk-rc-card`, and `.value-card` hover effects (3D tilt, darkening overlay, shadow lift) were permanently stuck after a tap on iOS/Android Safari and Chrome.
   - Fixed: all complex transform-based `:hover` rules stripped from the global scope and consolidated into a single `@media (hover: hover) and (pointer: fine)` block. A duplicate bare `.founder-card:hover` declaration was also removed. Touch users now see clean, un-stuck card states.

3. **Empty State Footer Float** (`style.css`)
   - `.sk-home-empty` had no minimum height, causing the footer to detach and float in the middle of the screen on large monitors when a category had no posts.
   - Fixed: `min-height: 55vh`, `display: flex`, and `justify-content: center` added. *(Already applied in v21.)*

4. **Un-copyable Carousel Text** (`style.css` + `gsap-animations.js`)
   - `user-select: none` was set permanently on `.sk-rc-track`, preventing users from copying offering titles, descriptions, or prices.
   - Fixed: removed `user-select: none` from the base `.sk-rc-track` rule. Added `.sk-rc-track.is-dragging { user-select: none }` so selection is only blocked during an active mouse drag. JS updated to toggle the `is-dragging` class instead of using inline `style.userSelect`.

5. **Z-Index War on Progress Bar** (`style.css`)
   - `.sk-progress` was set to `z-index: 10001`, which would cause it to slice through third-party overlays (cookie banners, live chat widgets) that conventionally sit at `9999`–`10000`.
   - Fixed: reduced to `z-index: 1000`, safely below the WordPress admin bar (`99999`) and above standard page content.

---

## v9.0.0 Changes — WordPress.com Compatibility & Journal Auto-Setup

### Major: Full WordPress.com Support (Free & Premium)

1. **Journal Category Auto-Creation** (3-layer protection)
   - **On init** (priority 99): Creates "journal" category if missing on every page load
   - **On theme activation**: Creates via `after_switch_theme` hook for fresh installs
   - **On post publish**: Auto-assigns uncategorized posts to "journal" category
   - Ensures journal page works immediately on both WordPress.com free and premium plans

2. **Smart Permalink Detection**
   - `template_redirect` now checks `get_option('permalink_structure')` before redirecting
   - **On WordPress.com FREE**: Skips redirect (permalinks disabled), uses `/?cat=journal` 
   - **On WordPress.com PREMIUM**: Redirect works, uses `/category/journal/`
   - **On self-hosted**: Works with pretty permalinks `/category/journal/`

3. **Dynamic Category Links in Navigation**
   - Header.php updated to use `get_category_link( get_cat_ID('journal') )`
   - **Mobile nav**: Journal link now adapts to site's permalink structure
   - **Desktop nav**: Journal link now adapts to site's permalink structure
   - Works identically on free, premium, and self-hosted — no hardcoded URLs

4. **Auto-Assign Posts to Journal**
   - New `publish_post` hook automatically assigns new blog posts to "journal" category
   - Only assigns if post has no categories (doesn't override existing assignments)
   - Ensures all blog posts appear on journal page without manual categorization

### Bug Fixes
- Fixed 504 timeout on WordPress.com free when visiting `/category/journal/`
- Fixed hardcoded permalink URLs in navigation that broke on WordPress.com free
- Fixed missing journal category causing 404 errors

### Backward Compatibility
- Changes are 100% compatible with v8.x installs
- No database migration needed
- Existing posts keep their categories
- Works on all WordPress.com plans and self-hosted WordPress

---

## v8.1.0 Changes — Journal/Blog Page Fix

### Bug Fix: Journal page (/blog) not loading

**Root causes fixed:**

1. **Blog page missing from auto-setup** — The `sk_auto_setup()` function never created the Journal page. It is now included in the pages array with slug `blog`, title `Journal`, and the `page-blog.php` template is automatically assigned via `_wp_page_template` post meta on creation.

2. **`page_for_posts` guard incomplete** — The hook that prevents WordPress from hijacking the `/blog` slug only fired when `page_for_posts` was non-zero. If the option had never been set (fresh installs), the guard was silently skipped. The check now also handles the `false` case (option absent) and always enforces `page_for_posts = 0`.

3. **`template_include` fallback strengthened** — The fallback now matches the blog page by both slug and page ID, and handles the edge case where WordPress incorrectly treats `/blog` as `is_home()` instead of `is_page()`.

4. **Migration for existing installs (v9)** — Added `sk_migrate_blog_page_v9()`: a one-time migration that runs on sites where `sk_setup_done_v53` already fired. It creates the Journal page (or assigns the correct template to an existing page), clears `page_for_posts`, and flushes rewrite rules automatically — no manual admin steps required.

---

## v6.0.0 Changes

### 1. Hero Section — New Split Layout
- **New layout**: Full-bleed background image + left text column + right portrait image panel — matching the provided reference design.
- **Background image**: Set in *SK Settings → Hero Section → Background Image* (paste URL or drag & drop to preview).
- **Right panel image**: Set in *SK Settings → Hero Section → Right Panel Image* (paste URL or drag & drop to preview).
- Feature strip at the bottom (Inner Clarity · Spiritual Guidance · Transform Your Life) — auto-rendered.
- Text content (headings, sub, CTAs) unchanged — all still editable in SK Settings.
- Fully responsive: right panel hidden on mobile, single-column with full-bleed bg retained.

### 2. Mobile Navbar Fix
- Added **✕ close button** inside the mobile overlay (top-right corner, 44×44px tap target).
- Fixed CTA button overflow on medium-width screens (hidden below 800px, before hamburger kicks in at 768px).
- Hamburger `aria-expanded` state now syncs correctly on open/close.
- Overlay `z-index` stacking corrected so hamburger always sits above overlay.

### 3. Founders — New Asymmetric Layout
- New layout matches wireframe: **1 large primary card (left) + stacked secondary cards (right)**.
- First team member (lowest Order number) → big card. All others → stacked smaller cards.
- All hover animations preserved identically from v5.

### 4. Founders — CPT (sk_team)
- New **Team Members** custom post type registered (`sk_team`).
- Appears in WP Admin under *Sacred Kompass → Team Members*.
- Each member has: First Name, Last Name, Origin, Role, Bio, Expertise Tags.
- **Portrait photo**: use the Featured Image box (standard WP media upload) OR paste a URL in the *Portrait Photo* meta box sidebar — with drag & drop preview.
- **Display order**: use *Page Attributes → Order* (lower = displayed first = primary/large card).
- **Add/Remove**: standard WP Add New / Trash. No limit on team member count.
- Falls back to the old JSON-based founders (SK Settings) if no CPT entries exist.

## Install / Upgrade
Same as v5 — upload theme folder, activate. No database migration needed.
Run `/?sk_reseed=1` (logged in as admin) only if you want to reset all settings to defaults.
