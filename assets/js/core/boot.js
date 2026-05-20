(function () {
  if (!window.SK || !window.SK.motion) {
    console.warn('Motion config missing. Aborting animation boot.');
    return;
  }

  if (!window.gsap || !window.ScrollTrigger || !window.Lenis) {
    console.warn('Missing animation runtime');
    return;
  }

  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;

  document.documentElement.classList.add('sk-js-ready');

  // Initialize the unified scroll runtime before queues flush
  try {
    if (window.SK && window.SK.initScrollRuntime) {
      window.SK.initScrollRuntime();
    }
  } catch (e) {
    console.error('[Runtime] Failed to initialize scroll runtime:', e);
  }

  try {
    if (window.SK && window.SK.dom) {
      window.SK.dom.flushReady();
    }
  } catch (e) {
    console.error('[Runtime] Failed to flush ready queue:', e);
  }

  // Wait for fonts & layout stabilization before calculating trigger positions
  window.addEventListener('load', () => {
    window.requestAnimationFrame(() => {
      try {
        if (document.fonts) { document.fonts.ready.then(() => ScrollTrigger.refresh()); } 
        else { ScrollTrigger.refresh(); }
        console.log('[Runtime] ScrollTrigger refreshed after layout stabilization');
      } catch (e) {
        console.error('[Runtime] ScrollTrigger refresh failed:', e);
      }
    });
  });

  // Debounced refresh on layout shifts/resize
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      try {
        if (ScrollTrigger) ScrollTrigger.refresh();
      } catch (e) {
        console.error('[Runtime] ScrollTrigger debounced refresh failed:', e);
      }
    }, 200);
  }, { passive: true });
})();
