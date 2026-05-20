(function () {
  const app = window.SK = window.SK || {};

  app.initScrollRuntime = function initScrollRuntime() {
    if (app.scroll && app.scroll.initialized) return app.scroll;

    const gsap = window.gsap;
    const ScrollTrigger = window.ScrollTrigger || (gsap && gsap.ScrollTrigger);
    const LenisCtor = window.Lenis;
    const listeners = [];
    let lenis = null;
    let paused = false;
    let progress = null;

    app.motion = app.motion || {};

    if (!app.motion.reduced) {
      document.body.classList.add('js-motion-ready');
    } else {
      document.body.classList.add('no-motion');
    }

    progress = document.querySelector('.sk-progress');
    if (!progress) {
      progress = document.createElement('div');
      progress.className = 'sk-progress';
      progress.id = 'sk-progress-bar';
      document.body.appendChild(progress);
    }

    const emit = () => {
      const scrollY = window.scrollY;
      const max = Math.max(document.documentElement.scrollHeight - window.innerHeight, 1);
      if (progress) progress.style.width = `${(scrollY / max) * 100}%`;
      listeners.forEach((fn) => fn(scrollY, max));
    };

    if (gsap && ScrollTrigger) {
      gsap.registerPlugin(ScrollTrigger);
      if (LenisCtor && !app.motion.reduced) {
        lenis = new LenisCtor({
          autoRaf: false,
          smoothWheel: true,
          lerp: window.SK.motion?.lerp || 0.08
        });
        document.documentElement.classList.add('lenis', 'lenis-smooth');
        lenis.on('scroll', () => {
          ScrollTrigger.update();
          emit();
        });
        const update = (time) => {
          lenis.raf(time * 1000);
        };
        gsap.ticker.add(update);
        gsap.ticker.lagSmoothing(0);
      }
    } else {
      document.body.classList.add('no-gsap');
    }

    window.addEventListener('scroll', emit, { passive: true });

    app.scroll = {
      initialized: true,
      lenis,
      onFrame(fn) {
        listeners.push(fn);
        fn(window.scrollY, Math.max(document.documentElement.scrollHeight - window.innerHeight, 1));
      },
      pause() {
        if (paused) return;
        paused = true;
        document.documentElement.classList.add('lenis-stopped');
        if (lenis) lenis.stop();
      },
      resume() {
        if (!paused) return;
        paused = false;
        document.documentElement.classList.remove('lenis-stopped');
        if (lenis) lenis.start();
        emit();
      },
      refresh() {
        if (ScrollTrigger) ScrollTrigger.refresh();
        emit();
      },
      scrollTo(target, offset) {
        const destination = typeof target === 'number'
          ? target
          : Math.max(0, target.getBoundingClientRect().top + window.scrollY - (offset || 0));
        if (lenis) {
          lenis.scrollTo(destination);
          return;
        }
        window.scrollTo({
          top: destination,
          behavior: app.motion.reduced ? 'auto' : 'smooth'
        });
      }
    };

    emit();
    return app.scroll;
  };
})();