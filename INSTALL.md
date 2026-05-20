# Sacred Kompass v5 — Installation Guide
**Zero paid plugins. Fully dynamic. Pre-filled content.**

---

## YOUR STACK (all FREE)

| Layer | Plugin | Purpose |
|---|---|---|
| Content fields | Advanced Custom Fields (ACF) | Options page fields |
| Repeatable items | Custom Post Types (built into theme) | Offerings + FAQ |
| Drag-drop order | Post Types Order | Reorder Offerings/FAQ |
| Contact form | Forminator | Contact section form |

---

## STEP 1 — Install Plugins (before uploading theme)

Go to **Plugins → Add New** and install + activate these 4 free plugins:

1. **Advanced Custom Fields** — search "advanced custom fields" (by Delicious Brains)
2. **Post Types Order** — search "post types order" (by Nsp Code)
3. **Forminator** — search "forminator" (by WPMU DEV) ← *optional, for contact form*

> ⚠️ Install ACF **before** the theme. It must be active when the theme activates.

---

## STEP 2 — Upload & Activate Theme

1. **Appearance → Themes → Add New → Upload Theme**
2. Upload `sacred-kompass-v5.zip`
3. Click **Activate**

**What happens automatically on activation:**
- ✅ Homepage ("Home") is created and set as the front page
- ✅ Legal pages created: Privacy Policy, Terms of Use, Disclaimer
- ✅ All 5 Offerings are inserted with full content
- ✅ All 6 FAQ items are inserted with full content
- ✅ Founder data pre-filled in ACF options
- ✅ All text content pre-filled — site is live immediately

---

## STEP 3 — Edit Content (ACF Options Page)

Go to **Sacred Kompass** in the WordPress sidebar.

### Main Page (sk-settings tabs):
| Tab | What you edit |
|---|---|
| ✦ Hero Section | Stage text, sub-text, CTA buttons, hero image |
| ✦ About Section | Eyebrow, heading, body text, pull quote |
| ✦ Tradition Tags | Pill tags (one per line) |
| ✦ Philosophy Strip | 3 pillar cards |
| ✦ Quote Band | Vision quote + highlight word |
| ✦ Contact Section | Eyebrow, sub-text, Forminator form ID |
| ✦ Footer & Social | Email, phone, tagline, Instagram/Facebook/WhatsApp |

### Sub-pages:
- **✦ Founders** — add/edit founder cards (photo, name, bio, tags)
- **✦ Values** — add/edit core value cards

---

## STEP 4 — Edit Offerings

Go to **Offerings** in the WP sidebar.

Each offering is a post:
- **Title** = offering name
- **Category Tag** (ACF) = e.g. Personal / Guidance / Corporate
- **Description** (ACF) = card body text
- **Price** (ACF) = optional, leave blank to hide
- **Featured Image** = card image (optional)

**To reorder:** Offerings → drag the posts into the order you want (Post Types Order handles this).

---

## STEP 5 — Edit FAQ

Go to **FAQ** in the WP sidebar.

- **Title** = the question
- **Answer** (ACF) = the answer text

Reorder by dragging, same as Offerings.

---

## STEP 6 — Set Up Contact Form (optional)

1. **Forminator → Forms → Create New Form**
2. Add fields: Name, Email, Message
3. Note the form ID (shown in the URL when editing)
4. **Sacred Kompass → Contact Section → Forminator Form ID** → paste the number
5. Save — form appears live immediately

---

## STEP 7 — Add Founder Photos

1. **Sacred Kompass → ✦ Founders** (sub-page)
2. Click **Edit** on each founder row
3. Upload portrait photo (min 520×700px)
4. Save

---

## STEP 8 — Legal Pages

The three legal pages are already created and styled automatically.
Templates are hardcoded PHP — no editing needed unless content changes.

To update content: **Appearance → Theme File Editor → page-disclaimer.php** (etc.)

---

## STEP 9 — Footer Menu (optional)

**Appearance → Menus → Create Menu → Footer Menu**
Add: Privacy Policy, Terms of Use, Disclaimer pages
Assign to **Footer** menu location.

---

## FILE STRUCTURE

```
sacred-kompass-v5/
│
├── style.css                      ← Theme declaration + ALL CSS
├── functions.php                  ← Master: loads inc/, registers ACF options, helpers
├── front-page.php                 ← Homepage template (loads all sections)
├── header.php                     ← Nav + mobile overlay
├── footer.php                     ← Footer grid + legal links
├── index.php                      ← WP fallback (required)
├── page.php                       ← Generic inner page
│
├── page-disclaimer.php            ← /disclaimer — hardcoded legal template
├── page-privacy-policy.php        ← /privacy-policy — hardcoded legal template
├── page-terms.php                 ← /terms — hardcoded legal template
│
├── inc/
│   ├── setup.php                  ← Runs on activation: creates pages, seeds content
│   ├── cpt.php                    ← Registers sk_offering + sk_faq post types + ACF fields
│   ├── acf-fields.php             ← Registers all ACF field groups (options page)
│   └── content.php                ← Inserts default Offerings + FAQ + Founders + Values
│
├── template-parts/home/
│   ├── hero.php                   ← Full-screen hero (ACF options)
│   ├── philosophy-strip.php       ← 3-pillar strip (ACF repeater)
│   ├── about.php                  ← About + traditions (ACF options)
│   ├── offerings.php              ← 5-card grid (sk_offering CPT)
│   ├── quote-band.php             ← Dark quote band (ACF options)
│   ├── founders.php               ← 2-card hover reveal (ACF sub-page)
│   ├── testimonials.php           ← Values 2×2 grid (ACF sub-page)
│   ├── faq.php                    ← Accordion (sk_faq CPT)
│   └── cta.php                    ← Contact section (ACF + Forminator)
│
└── assets/
    ├── js/main.js                 ← All JS: nav, parallax, FAQ, reveal, smooth scroll
    ├── css/                       ← Reserved for future CSS additions
    └── images/                    ← Place site images here
```

---

## HOW DATA FLOWS

```
ACF Options Page (sk-settings)
  └─→ sk_option() helper
        └─→ template-parts/*.php
              └─→ renders on front-page.php

Custom Post Types (Offerings / FAQ)
  └─→ WP_Query in template-parts
        └─→ ordered by menu_order (Post Types Order)
              └─→ renders on front-page.php
```

---

## TROUBLESHOOTING

| Problem | Fix |
|---|---|
| ACF fields not showing | Make sure ACF plugin is active |
| Content not pre-filled | Deactivate + reactivate theme |
| Offerings/FAQ empty | Go to Offerings or FAQ → check posts exist and are Published |
| Contact form not showing | Set Forminator Form ID in Sacred Kompass → Contact Section |
| Reorder not working | Make sure Post Types Order plugin is active |

---

*Sacred Kompass v5.0.0 — Built for agency reuse. 100% free stack.*
