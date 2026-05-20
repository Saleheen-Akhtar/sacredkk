(function () {
  window.SK = window.SK || {};
  window.SK.motion = {
    reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    duration: {
      fast: .4,
      base: .8, charStep: 90,
      slow: 1.2,
      heroHold: 2000
    },
    stagger: {
      sm: .04, char: 42,
      md: .08,
      lg: .14
    },
    distance: {
      sm: 20,
      md: 40,
      lg: 80
    },
    easing: {
      soft: 'ease',
      spring: 'cubic-bezier(.34,1.56,.64,1)',
      standard: 'cubic-bezier(.22,.61,.36,1)'
    },
    blur: '18px', lerp: 0.08, offsets: {
      parallax: 0.15,
      mobileParallax: 0.08
    }
  };
  // Backward compatibility alias for any lingering module references
  window.SK.MOTION = window.SK.motion;
})();