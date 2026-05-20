# Sacred Kompass v9 — Setup & Developer Guide

---

## What's new in v23

- **Custom search bar** — `searchform.php` created. A branded search input (using theme tokens) now appears above the journal filter tabs on both `home.php` and `category.php`.
- **Newsletter section** — A Forminator-powered "Join the Inner Circle" signup block has been injected above the footer on `home.php`, `category.php`, and `single.php`. Replace `YOUR_FORM_ID_HERE` with your actual form ID from the Forminator plugin dashboard.
- **`<em>` rendering bug fixed** — The word "Articles" in the Related Articles heading on `single.php` was being rendered as plain text due to `esc_html_e()` stripping the HTML tag. Now correctly italicised in terracotta.
- **Editorial blog card redesign** — Cards are now transparent (no white box, no border). Images use a portrait `4/5` ratio mimicking high-end print magazines. Excerpt uses Cormorant Garamond. Footer uses a gold divider line.
- **Editorial featured post redesign** — The featured first post is now a side-by-side editorial layout (`1.1fr / 0.9fr`) with a portrait image and floating body text. Collapses gracefully to single-column on tablet.
- **Post hero image wide-out** — Single post hero images now break wider than the reading column (`1140px`, `600px` tall) for a cinematic feel. Reduces to `40vh` on mobile.
- **Jetpack Like button styled** — The Jetpack widget is now visually integrated: default label hidden, iframe warmed with a sepia filter at rest, full colour on hover, wrapped in subtle gold borders.

---

## What's new in v22

- **Journal tab tokens fixed** — Tab colours now correctly use `--ink-muted` and `--gold` from the design token system instead of undefined `--sk-muted` / `--sk-gold` fallback values.
- **Touch hover states fixed** — Founder cards, offering cards, and value cards no longer get permanently stuck in their hover state after a tap on iOS or Android. All complex hover effects are now guarded by `@media (hover: hover) and (pointer: fine)`.
- **Carousel text is now copyable** — `user-select: none` removed from `.sk-rc-track` permanently; selection is now only blocked during an active drag via the `.is-dragging` CSS class toggled by JavaScript.
- **Progress bar z-index reduced** — `.sk-progress` dropped from `z-index: 10001` to `1000`, preventing it from slicing through third-party plugin overlays (cookie banners, live chat widgets, etc.).
- **Footer float on empty categories fixed** — `.sk-home-empty` now has `min-height: 55vh` so the footer stays pinned to the bottom on large screens when a category has no posts.

---

## What's new in v9

- **WordPress.com compatibility** — Now works on both FREE and PREMIUM plans. Navigation links automatically adapt to the correct URL format (`/?cat=journal` on free, `/category/journal/` on premium).
- **Journal category auto-creation** — The "journal" category is created automatically on page load (init), theme activation, and post publication. No manual setup needed.
- **Auto-assign blog posts** — New blog posts without a category are automatically assigned to "journal" so they appear on the journal page.
- **Smart permalink detection** — The theme checks if permalinks are enabled before attempting redirects, preventing 504 timeouts on WordPress.com free tier.
- **Dynamic navigation links** — Header links use `get_category_link()` instead of hardcoded URLs, ensuring they work on all WordPress hosting setups.

---

## What's new in v7

- **Transparent hero nav** — Navbar is fully transparent over the hero section and transitions to a glass-morphism style on scroll.
- **Nav hide/show on scroll** — Navbar slides out when scrolling down, reappears when scrolling up.
- **Hero layout refined** — Features strip removed. Content vertically centred so CTAs are always visible on load. Background image no longer bounces on scroll.
- **Founders: 3-card layout + popup modals** — Left large card = team/group, top-right = Kalai, bottom-right = Christophe. Clicking any card opens a full-screen modal with bio, tags, and a CTA.
- **Contact form redesigned** — First/last name split row, service interest dropdown, textarea, front-end validation with live error states.
- **Philosophy strip restored** — Three numbered pillar cards are back between the hero and the about section.
- **Footer text legibility** — All footer text brightened significantly.
- **Fully responsive** — Hero, hamburger menu, and all cards tested down to 320px.

---

## Step 1 — Upload and activate the theme

### Option A — WP Admin (recommended)
1. Appearance → Themes → Add New → Upload Theme
2. Upload `sacred-kompass-v7.zip` → Install Now → Activate

### Option B — FTP / SSH
1. Delete `/wp-content/themes/sacred-kompass-v7/` if it exists
2. Upload the `sacred-kompass-v7` folder to `/wp-content/themes/`
3. Appearance → Themes → Activate **Sacred Kompass**

---

## Step 2 — Automatic Setup (No manual configuration needed!)

The theme automatically handles:
✅ **Journal category creation** — Created on first page load, theme activation, and post publication  
✅ **Blog post organization** — New posts without a category are automatically assigned to "journal"  
✅ **Navigation links** — Journal link in header automatically uses the correct URL format for your hosting:
  - WordPress.com FREE: `/?cat=journal`
  - WordPress.com PREMIUM: `/category/journal/`
  - Self-hosted: `/category/journal/`

**Optional:** To seed default content (hero text, founder cards, values, offerings, FAQ):
Visit: `https://yoursite.com/wp-admin/?sk_reseed=1` (must be logged in as admin)

Safe to run once or repeat after theme updates.

---

## Step 4 — Edit all content

Go to **★ Sacred Kompass** in the WP Admin sidebar. All settings live on one scrollable page.

| Section | Fields |
|---------|--------|
| ✦ Hero | Animated headline lines (3 stages), sub-text, two CTA buttons, background image URL, right panel image URL |
| ✦ About | Eyebrow, heading, body HTML, pull quote, tradition tags |
| ✦ Philosophy Strip | Three numbered pillar cards — title, description (Add/Remove rows) |
| ✦ Quote Band | Vision quote, highlight phrase, attribution |
| ✦ Founders | Section header + `founders_team_image` URL for the large left card + individual founder cards with photo URL, role, origin, bio, tags (Add/Remove) |
| ✦ Core Values | Value cards — title + description (Add/Remove) |
| ✦ Contact | Eyebrow, sub-text, Forminator form ID |
| ✦ Footer & Social | Email, phone, tagline, copyright, social URLs |

Click **Save All Changes** at the top or bottom after editing.

---

## Step 5 — Add your logo

1. **Appearance → Customize → Site Identity**
2. Click **Select Logo** → upload your logo file
3. Click **Publish**

The logo appears in the header (44 px tall) and footer (52 px tall).
Text fallback: "Sacred *Kompass*" if no logo is uploaded.

**Recommended logo specs:**
- Format: PNG with transparent background, or SVG
- Dimensions: at least 240 × 80 px (width can be larger, height scales)

---

## Step 6 — Manage Offerings

**★ Sacred Kompass → ✦ Offerings** (native WP post type)

Each offering is a CPT post with these meta fields:
- **Title** — offering name
- **Category Tag** — short label e.g. `Personal`, `Corporate`
- **Description** — card body text
- **Price** — optional; leave blank to hide
- **Featured Image** — optional card image (4:3 ratio recommended)

---

## Step 7 — Manage FAQ

**★ Sacred Kompass → ✦ FAQ** (native WP post type)

Each FAQ item is a CPT post:
- **Title** — the question displayed as the accordion header
- **Answer** (meta box) — the expanded answer text

---

## Step 8 — Add founder & team photos

**Large left card (Team):**
In **★ Sacred Kompass → ✦ Founders**, paste a URL into the **Team Photo URL** field (`founders_team_image`).

**Individual founder cards (Kalai, Christophe):**
In each founder row, paste the photo URL into the **Portrait Photo URL** field.

To get a URL: Media → Add New → upload → click image → copy **File URL** from the right panel.

Recommended dimensions:
- Team/group card: landscape or portrait, min **900 × 1000 px**
- Individual cards: portrait orientation, min **520 × 420 px**

---

## Step 9 — Contact form (Forminator)

The fallback form (shown when no Forminator ID is set) includes:
- First name + Last name (side by side)
- Email address
- Area of interest (dropdown)
- Message (textarea)
- Client-side validation with live error feedback

**To use Forminator instead:**
1. Install and activate **Forminator** (free, by WPMU DEV)
2. Forminator → Forms → Create New → add your fields → Publish
3. Note the form ID from the URL (e.g. `form_id=42` → ID is `42`)
4. **★ Sacred Kompass → ✦ Contact Section → Forminator Form ID** → enter `42` → Save

The Forminator form will automatically inherit the dark theme styling.

---

## Step 10 — Hero images

The hero has two image slots:

| Option key | Where it shows |
|---|---|
| `hero_bg_image` | Full-bleed background across both columns |
| `hero_right_image` | Right panel image (desktop only, hidden on mobile) |

Set either or both from **★ Sacred Kompass → ✦ Hero**. Both accept direct image URLs.

Recommended: at least **1400 × 900 px**, high-quality JPEG or WebP.

---

## Step 11 — Managing the Journal (Blog)

The theme automatically manages your blog/journal section. **No additional configuration needed.**

### How it works:
- **Journal category auto-creation** — The "journal" category is created automatically on first page load
- **Auto-category assignment** — New blog posts without a category are automatically added to "journal"
- **Smart navigation links** — The Journal link in your header automatically adapts to your hosting platform:
  - **WordPress.com FREE**: `/?cat=journal`
  - **WordPress.com PREMIUM**: `/category/journal/`
  - **Self-hosted**: `/category/journal/`

### To add blog posts:
1. Go to **Posts → Add New** in WordPress admin
2. Write your post and assign it a category (optional — it will be auto-assigned to "journal" if left uncategorized)
3. Publish
4. The post automatically appears on your Journal page

### Customize journal content:
In **★ Sacred Kompass** settings, you can customize:
- Blog tagline/description (shown above the journal posts)

All posts display with:
- Thumbnail image
- Reading time estimate
- Publication date
- Category badge
- Excerpt

---

## Troubleshooting

| Issue | Fix |
|---|---|
| Sections render blank | Run `?sk_reseed=1` in WP admin |
| Logo not showing | Appearance → Customize → Site Identity → Select Logo → Publish |
| Founders modal not opening | Check browser console; ensure `main.js` is enqueued via `wp_footer` |
| Philosophy strip missing | Check **★ Sacred Kompass → ✦ Philosophy Strip** has saved pillars, or run reseed |
| Nav not transparent on hero | Ensure the homepage uses `front-page.php` (body class includes `.home`) |
| Forminator form unstyled | Theme injects dark-mode overrides only when Forminator is active and an ID is set |
| Journal page shows 404 | Ensure the "journal" category exists (Posts → Categories → Check for "journal"). If missing, simply create it. The theme will auto-create it on next page load. |
| Blog posts not showing on journal page | Check that posts have the "journal" category assigned. Posts without a category will be auto-assigned to "journal" on next publish/save. |
| Journal link broken on WordPress.com FREE | This is normal! The link should be `/?cat=journal` (not `/category/journal/`). The theme automatically uses the correct format. Test and verify it works. |
| 504 timeout when visiting journal page | This usually means permalinks are incompatible with your hosting. The theme now checks for this automatically. If it persists, check WordPress Settings → Permalinks and try a different permalink structure. |

---

## File reference

```
sacred-kompass-v7/
├── functions.php              Settings page, helpers, enqueue, logo, auto-setup
├── header.php                 Nav bar — glassmorphism + hamburger mobile menu
├── footer.php                 Footer — brightened text, logo support
├── front-page.php             Homepage template (includes all section parts)
├── page.php                   All standard WP Pages
├── style.css                  Master stylesheet (tokens → components → responsive)
│
├── inc/
│   ├── cpt.php                Offerings + FAQ CPTs + native meta boxes
│   ├── content.php            Default content seeder (no ACF needed)
│   ├── acf-fields.php         Stub (ACF not required)
│   └── setup.php              Stub
│
├── assets/
│   └── js/
│       └── main.js            Scroll logic, nav hide/show, reveals, modals,
│                              hamburger, FAQ accordion, form validation
│
└── template-parts/home/
    ├── hero.php               Split hero — bg image, left text, right panel
    ├── philosophy-strip.php   Three numbered pillar cards
    ├── about.php              About section with pull-quote
    ├── offerings.php          5-column offering cards (CPT)
    ├── quote-band.php         Dark quote band
    ├── founders.php           3-card asymmetric layout + popup modals
    ├── testimonials.php       Core values grid
    ├── faq.php                Accordion FAQ (CPT)
    └── cta.php                Contact section — dark bg + improved form
```

---

*Sacred Kompass v9 — WordPress.com ready (free & premium). Auto-setup journal. Zero plugins required. One menu. One scroll. Every pixel intentional.*
