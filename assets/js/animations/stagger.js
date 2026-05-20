(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Stagger', () => {
    const initStagger = () => {
      try {
    const groups = dom.qsa('.stagger-children, [data-stagger]');
    if (!groups.length) {
      console.warn('[Stagger] No targets found');
      return;
    }
    const motion = app.motion || {};
    if (motion.reduced) {
      groups.forEach((group) => group.classList.add('visible'));
      return;
    }
    if (window.gsap && window.ScrollTrigger) {
      groups.forEach((group) => {
        const children = Array.from(group.children);
        if (!children.length) return;
        window.gsap.fromTo(children, {
          opacity: 0,
          y: motion.distance?.md || 40
        }, {
          opacity: 1,
          y: 0,
          duration: motion.duration?.base || 0.8,
          stagger: parseFloat(group.dataset.stagger || motion.stagger?.md || 0.08),
          ease: 'power3.out',
          scrollTrigger: {
            trigger: group,
            start: 'top 84%',
            once: true
          },
          onComplete: () => group.classList.add('visible')
        });
      });
      return;
    }
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const group = entry.target;
        const step = parseFloat(group.dataset.stagger || '0.08');
        Array.from(group.children).forEach((child, index) => {
          child.style.transitionDelay = `${index * step}s`;
        });
        group.classList.add('visible');
        observer.unobserve(group);
      });
    }, { threshold: 0.18, rootMargin: '0px 0px -8% 0px' });
    groups.forEach((group) => observer.observe(group));
      console.log('[Runtime] Stagger mounted');
    } catch (e) {
      console.error('[Runtime] Stagger failed', e);
    }
    };

    if (document.readyState === 'complete') initStagger();
    else window.addEventListener('load', initStagger);
  });
})();
