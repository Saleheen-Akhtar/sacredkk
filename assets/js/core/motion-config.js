// assets/js/core/motion-config.js
(function () {
  window.SK = window.SK || {};
  window.SK.motion = {
    reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    duration: {
      instant:  0,
      fast:     0.4,    // --duration-fast  400ms
      base:     0.8,    // --duration-base  800ms
      slow:     1.4,    // --duration-slow  1400ms  ← was 1.2, now matches spec
      ambient:  3.0,    // --duration-ambient 3000ms ← was missing
      heroHold: 2000,
      charStep:   90,
    },
    stagger: {
      sm:   0.05,   // fast
      md:   0.10,   // base  ← was 0.08, now matches spec
      lg:   0.20,   // slow
      char:   42,
    },
    distance: {
      sm: 20,
      md: 40,
      lg: 80,
    },
    // GSAP easing strings — map to CSS var equivalents shown in comment
    easing: {
      spiritual: 'power2.out',   // ≈ --ease-soft   (cinematic reveals)
      ambient:   'sine.inOut',   // ≈ --ease         (floating/parallax)
      exit:      'power1.in',    // ≈ --ease-exit    (elements leaving)
      spring:    'cubic-bezier(.34,1.56,.64,1)', // = --ease-spring
      standard:  'power3.out',   // ≈ --ease-standard
    },
    offsets: {
      parallax:       0.15,
      mobileParallax: 0.08,
    },
    blur: { lg: '18px' },
    scroll: { lerp: 0.08 },
  };
  // Backward compatibility alias
  window.SK.MOTION = window.SK.motion;
})();
