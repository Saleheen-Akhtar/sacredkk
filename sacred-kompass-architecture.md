# Sacred Kompass: Frontend Systems & Token Architecture

## Mission
Create an implementation-ready, modular frontend system for Sacred Kompass that adapts the cinematic, spacing-disciplined, and motion-architected qualities of Ironhill, while preserving and elevating the site's unique "spiritual luxury" identity. The goal is to achieve an immersive, meditative, and cinematic experience through soft transitions, refined typography, and disciplined responsive spacing.

## Context & Goals
- **Platform**: Custom WordPress PHP theme.
- **Frontend Stack**: SCSS / CSS Custom Properties, modular ES6 JS, pnpm, GSAP, Lenis, Playwright.
- **Architectural Shift**: WordPress acts primarily as a headless-like CMS and asset enqueue layer. The frontend is treated as a modern application architecture with modular dependencies.
- **Visual Evolution**: A more refined, cinematic version of the current Sacred Kompass. Not a redesign, but a premium elevation focusing on spacing consistency, contrast hierarchy, and atmospheric depth.
- **Motion Identity**: Slow, atmospheric, layered, and fade-oriented. Think "cinematic spiritual storytelling." Avoid aggressive, hard, or fast kinetic transitions.

## Quality Gates & Accessibility (Target: WCAG 2.2 AA)
- **Keyboard & Focus**: Keyboard-first interactions required. Every interactive element **must** have visible focus indicators (`:focus-visible`).
- **Contrast**: Contrast constraints **must** be strictly enforced to avoid muddy areas while maintaining the earthy palette.
- **Motion Accessibility**: All animations **must** respect `prefers-reduced-motion` queries.
- **Consistency Rules**: Every non-negotiable rule must use "must". Every recommendation should use "should". Teams should prefer system consistency over local visual exceptions.

## Style Foundations & Tokens (SCSS / CSS Custom Properties)

Sacred Kompass's foundation is built on semantic variables that map to raw base values. The architecture dictates using **only** semantic tokens within component stylesheets.

### 1. Color Palette System
*Strategy: Preserve earthy tones, improve tonal consistency, reduce muddy contrast, and establish semantic usage.*

```scss
/* =========================================
   SCSS Base Tones (DO NOT USE DIRECTLY IN COMPONENTS)
   ========================================= */
:root {
  --sk-core-earth-900: #1C1917; /* Deepest shadow / spiritual void */
  --sk-core-earth-800: #292524;
  --sk-core-earth-700: #44403C;
  --sk-core-stone-300: #D6D3D1;
  --sk-core-sand-100:  #F5F5F4; /* Meditative canvas */
  --sk-core-sand-50:   #FAFAF9;

  --sk-core-accent-primary: #8B7355; /* Earthy gold / spiritual highlight */
  --sk-core-accent-muted:   #A89F91;
}

/* =========================================
   Semantic Tokens (USE THESE IN COMPONENTS)
   ========================================= */
:root {
  /* Surfaces */
  --color-surface-base: var(--sk-core-earth-900);
  --color-surface-raised: var(--sk-core-earth-800);
  --color-surface-inverse: var(--sk-core-sand-50);
  --color-surface-ambient: rgba(28, 25, 23, 0.4); /* For layered depth/glass */

  /* Typography */
  --color-text-primary: var(--sk-core-sand-50);
  --color-text-secondary: var(--sk-core-stone-300);
  --color-text-inverse: var(--sk-core-earth-900);
  --color-text-accent: var(--sk-core-accent-primary);

  /* Borders & Dividers */
  --color-border-subtle: rgba(214, 211, 209, 0.15);
  --color-border-focus: var(--sk-core-accent-primary);
}
```

### 2. Typography Token System
*Strategy: Maintain the existing font identity while introducing a mathematical scale, improving reading hierarchy, line-length limits, and breathing room.*

```scss
:root {
  /* Font Stacks */
  --font-family-primary: 'YourPrimarySerif', ui-serif, Georgia, Cambria, "Times New Roman", Times, serif; /* Update with actual brand font */
  --font-family-secondary: 'YourSecondarySans', system-ui, -apple-system, sans-serif;

  /* Fluid Base & Weights */
  --font-size-base: 16px;
  --font-weight-light: 300;
  --font-weight-regular: 400;
  --font-weight-medium: 500;

  /* Fluid Typography Scale (using Clamp for seamless cinematic scaling) */
  --font-size-xs: clamp(0.75rem, 0.71rem + 0.18vw, 0.875rem);   /* 12px -> 14px */
  --font-size-sm: clamp(0.875rem, 0.84rem + 0.18vw, 1rem);      /* 14px -> 16px */
  --font-size-md: clamp(1rem, 0.96rem + 0.18vw, 1.125rem);      /* 16px -> 18px */
  --font-size-lg: clamp(1.25rem, 1.16rem + 0.45vw, 1.5rem);     /* 20px -> 24px */
  --font-size-xl: clamp(1.75rem, 1.57rem + 0.89vw, 2.25rem);    /* 28px -> 36px */
  --font-size-2xl: clamp(2.5rem, 2.14rem + 1.79vw, 3.5rem);     /* 40px -> 56px */
  --font-size-hero: clamp(3.5rem, 2.79rem + 3.57vw, 5.5rem);    /* 56px -> 88px */

  /* Line Heights (Tighter for headers, looser for reading) */
  --line-height-hero: 1.1;
  --line-height-heading: 1.2;
  --line-height-body: 1.6;

  /* Line Length Discipline */
  --max-width-reading: 65ch;
  --max-width-intro: 45ch;
}
```

### 3. Spacing and Layout Token System
*Strategy: Inspired by Ironhill but optimized for a spiritual atmosphere. Emphasize breathing room, predictable rhythm, and seamless responsive scaling.*

```scss
:root {
  /* =========================================
     Micro/Component Spacing (Fixed or subtle fluid)
     ========================================= */
  --space-3xs: 4px;
  --space-2xs: 8px;
  --space-xs:  12px;
  --space-sm:  16px;
  --space-md:  24px;
  --space-lg:  32px;
  --space-xl:  48px;

  /* =========================================
     Macro/Section Layout Spacing (Fluid)
     ========================================= */
  --section-space-sm: clamp(3rem, 2.29rem + 3.57vw, 5rem);     /* 48px -> 80px */
  --section-space-md: clamp(5rem, 3.93rem + 5.36vw, 8rem);     /* 80px -> 128px */
  --section-space-lg: clamp(8rem, 6.57rem + 7.14vw, 12rem);    /* 128px -> 192px */

  /* =========================================
     Container Widths
     ========================================= */
  --container-width-main: 1440px;
  --container-width-narrow: 800px;
  --container-padding: clamp(1rem, 0.64rem + 1.79vw, 2rem);    /* 16px -> 32px edges */
}

/* Base Layout Utility Example */
.sk-section {
  padding-block: var(--section-space-md);
  width: 100%;
  max-width: var(--container-width-main);
  margin-inline: auto;
  padding-inline: var(--container-padding);
}

.sk-content-narrow {
  max-width: var(--max-width-reading);
  margin-inline: auto;
}
```

## Global Motion Token System (GSAP)

*Strategy: Motion must feel calm, weightless, and premium. Avoid bouncy, fast, or hyper-kinetic easings.*

### 1. Motion Tokens (JS Configuration)
To be used as the single source of truth for all GSAP animations across the theme.

```javascript
// src/js/motion/tokens.js

export const motionTokens = {
  // Duration Scale (in seconds for GSAP)
  duration: {
    instant: 0,
    fast: 0.4,       // For micro-interactions (hover, focus)
    base: 0.8,       // Standard UI transitions
    slow: 1.4,       // Cinematic reveals
    ambient: 3.0,    // Ambient floating/parallax
  },

  // Easing Curves
  ease: {
    // Soft, weightless decel (primary choice for reveals)
    spiritual: "power2.out",
    // Very gentle ease in/out for opacity and floating
    ambient: "sine.inOut",
    // Smooth, premium exit
    exit: "power1.in",
  },

  // Stagger Rhythms
  stagger: {
    fast: 0.05,
    base: 0.1,
    slow: 0.2, // Use for meditative text reveals
  }
};
```

### 2. Accessibility & Fallbacks

Every animation must respect user preferences. We use GSAP's `matchMedia` to handle `prefers-reduced-motion` and mobile fallbacks.

```javascript
// src/js/motion/context.js
import gsap from "gsap";

export const createMotionContext = () => {
  let mm = gsap.matchMedia();

  // Desktop with motion allowed
  mm.add("(min-width: 768px) and (prefers-reduced-motion: no-preference)", () => {
    // Cinematic animations run here
  });

  // Mobile OR prefers-reduced-motion
  mm.add("(max-width: 767px), (prefers-reduced-motion: reduce)", () => {
    // Apply immediate opacity sets or very simplified, instant transitions
    // Do NOT apply heavy parallax or translate transforms
  });

  return mm;
};
```

## Cinematic Transitions & Scroll Architecture

*Strategy: Utilize modern JS modularity. Lenis provides the smooth scroll foundation, and GSAP ScrollTrigger layers the cinematic depth.*

### 1. Core Scroll Setup (Lenis + GSAP)

```javascript
// src/js/core/scroll.js
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";

gsap.registerPlugin(ScrollTrigger);

export const initSmoothScroll = () => {
  const lenis = new Lenis({
    duration: 1.5, // Slow, meditative scroll
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // Smooth decel
    orientation: 'vertical',
    gestureOrientation: 'vertical',
    smoothWheel: true,
    wheelMultiplier: 0.8, // Slightly resistant for heavier, premium feel
  });

  lenis.on('scroll', ScrollTrigger.update);

  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });

  gsap.ticker.lagSmoothing(0);

  return lenis;
};
```

### 2. Reusable Cinematic Utilities

#### Ambient Reveal (For Sections/Cards)
*Effect: Element fades in slowly and floats up slightly.*

```javascript
// src/js/motion/animations.js
import gsap from "gsap";
import { motionTokens } from "./tokens";

export const initAmbientReveal = (elements) => {
  gsap.utils.toArray(elements).forEach((el) => {
    gsap.fromTo(el,
      {
        y: 40,
        opacity: 0
      },
      {
        y: 0,
        opacity: 1,
        duration: motionTokens.duration.slow,
        ease: motionTokens.ease.spiritual,
        scrollTrigger: {
          trigger: el,
          start: "top 85%", // Reveal gently as it enters the viewport
          toggleActions: "play none none reverse" // Meditative continuity
        }
      }
    );
  });
};
```

#### Soft Parallax (For Images/Backgrounds)
*Effect: Weightless, subtle parallax that doesn't distort or distract.*

```javascript
// src/js/motion/animations.js
export const initSoftParallax = (imageContainers) => {
  gsap.utils.toArray(imageContainers).forEach((container) => {
    const image = container.querySelector('img');
    if (!image) return;

    gsap.fromTo(image,
      {
        yPercent: -10 // Start slightly pulled up
      },
      {
        yPercent: 10, // Move down slightly as you scroll
        ease: "none", // Linear mapping to scroll
        scrollTrigger: {
          trigger: container,
          start: "top bottom",
          end: "bottom top",
          scrub: true // Link directly to the slow Lenis scroll
        }
      }
    );
  });
};
```

#### Layered Opacity Transitions (For Cross-Section Depth)
*Effect: The previous section fades out as the new one overlaps it.*

```javascript
// src/js/motion/animations.js
export const initSectionOverlap = (sections) => {
  gsap.utils.toArray(sections).forEach((section, index) => {
    // Skip the last section
    if (index === sections.length - 1) return;

    gsap.to(section, {
      opacity: 0,
      scale: 0.95, // Subtle push-back effect
      scrollTrigger: {
        trigger: section,
        start: "bottom bottom",
        end: "bottom top",
        scrub: true
      }
    });
  });
};
```

## Component Rules & Guidelines

1. **Interactive States**: Every component **must** define explicit states for default, hover, focus-visible, active, disabled, loading, and error.
2. **Hover Subtlety**: Hover states should be soft and atmospheric (e.g., a slow opacity shift or slight color grading). Avoid jarring scale changes.
3. **Contrast Hierarchy**: Text overlaid on images or ambient backgrounds **must** use a scrim or gradient overlay to ensure WCAG AA contrast ratio (4.5:1 for normal text, 3:1 for large text).
4. **Empty/Overflow States**: Components must anticipate long content and empty states. Paragraphs must strictly adhere to the `--max-width-reading` token.

## Anti-Patterns & Prohibited Implementations

- **DO NOT** use raw hex colors in components; always use semantic `var(--color-...)`.
- **DO NOT** remove or hide `:focus-visible` outlines without providing an equally visible, high-contrast alternative.
- **DO NOT** use "hard wipes", fast kinetic animations, or aggressive scaling. Motion must remain within the bounds of the `motionTokens`.
- **DO NOT** bypass the `initSmoothScroll` logic by using native CSS smooth scroll, to ensure GSAP ScrollTrigger stays perfectly synced with Lenis.

## QA & Accessibility Checklist

- [ ] Run automated Playwright tests to verify WCAG 2.2 AA compliance across all key templates.
- [ ] Tab through the entire page. Does every interactive element have a clear, custom focus ring?
- [ ] Enable "Prefers Reduced Motion" in OS settings. Verify that all heavy parallax and scaling animations are disabled or reduced to simple opacity fades.
- [ ] Inspect mobile layout. Ensure typography scales down via clamp rules and line lengths do not overflow the viewport.
- [ ] Verify image overlays. Test light and dark images to ensure text remains legible and contrast passes.
