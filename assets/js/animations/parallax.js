(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Parallax', () => {
    const initParallax = () => {
      try {
    const motion = app.motion || {};
    if (motion.reduced || !app.scroll) return;
    const targets = dom.qsa('[data-parallax]');
    if (!targets.length) {
      console.warn('[Parallax] No targets found');
      return;
    }
    const amount = motion.offsets ? motion.offsets[dom.isTouch() ? 'mobileParallax' : 'parallax'] : 0.15;
    app.scroll.onFrame(() => {
      targets.forEach((el) => {
        const rect = el.getBoundingClientRect();
        const midpoint = rect.top + rect.height / 2 - window.innerHeight / 2;
        const strength = parseFloat(el.dataset.parallax || amount);
        el.style.transform = `translate3d(0, ${midpoint * -strength}px, 0)`;
      });
    });
      console.log('[Runtime] Parallax mounted');
    } catch (e) {
      console.error('[Runtime] Parallax failed', e);
    }
    };
    
    if (document.readyState === 'complete') initParallax();
    else window.addEventListener('load', initParallax);
  });
})();
