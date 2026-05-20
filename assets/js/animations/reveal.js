(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Reveal', () => {
    const initReveal = () => {
      try {
    const revealTargets = dom.qsa('.reveal, .reveal-left, .reveal-right, .reveal-scale, .section-enter, .reveal-cta, [data-animate], [data-reveal]');
    if (!revealTargets.length) {
      console.warn('[Reveal] No targets found');
      return;
    }
    const motion = app.motion || {};
    if (motion.reduced) {
      revealTargets.concat(dom.qsa('.stagger-children')).forEach((el) => {
        el.classList.add('visible');
      });
      return;
    }
    if (window.gsap && window.ScrollTrigger) {
      revealTargets.forEach((el) => {
        const distance = parseFloat(el.dataset.revealDistance || motion.distance?.md || 40);
        const delay = parseFloat(el.dataset.delay || '0');
        const from = { opacity: 0, y: distance };
        if (el.classList.contains('reveal-left')) {
          from.x = -distance;
          from.y = 0;
        } else if (el.classList.contains('reveal-right')) {
          from.x = distance;
          from.y = 0;
        } else if (el.classList.contains('reveal-scale')) {
          from.scale = 0.96;
          from.y = motion.distance?.sm || 20;
        }
        window.gsap.fromTo(el, from, {
          opacity: 1,
          x: 0,
          y: 0,
          scale: 1,
          duration: motion.duration?.base || 0.8,
          delay,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: el,
            start: 'top 84%',
            once: true
          },
          onComplete: () => el.classList.add('visible')
        });
      });
      return;
    }
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const element = entry.target;
        const delay = element.dataset.delay ? parseFloat(element.dataset.delay) * 1000 : 0;
        if (delay > 0) element.style.transitionDelay = `${delay}ms`;
        element.classList.add('visible');
        observer.unobserve(element);
      });
    }, { threshold: 0.16, rootMargin: '0px 0px -8% 0px' });
    revealTargets.forEach((el) => observer.observe(el));
      console.log('[Runtime] Reveal mounted');
    } catch (e) {
      console.error('[Runtime] Reveal failed', e);
    }
    };

    if (document.readyState === 'complete') initReveal();
    else window.addEventListener('load', initReveal);
  });
})();
