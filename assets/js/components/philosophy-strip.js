(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('PhilosophyStrip', () => {
    try {
    const pillars = dom.readJSON('sk-philosophy-data');
    const strip = document.getElementById('sk-philosophy-strip');
    const quote = document.getElementById('ct-quote');
    const name = document.getElementById('ct-name');
    const designation = document.getElementById('ct-desig');
    const imagesWrap = document.getElementById('ct-images');

    if (!Array.isArray(pillars) || !strip || !quote || !name || !designation || !imagesWrap || !pillars.length) {
      console.warn('[PhilosophyStrip] Missing elements or data');
      return;
    }

    const images = dom.qsa('.ct-img', imagesWrap);
    const nextButton = document.getElementById('ct-next');
    const prevButton = document.getElementById('ct-prev');
    let active = 0;
    let autoTimer = 0;
    let hovered = false;
    const gap = () => {
      const width = imagesWrap.offsetWidth || 400;
      if (width <= 1024) return 60;
      if (width >= 1456) return 86 + 0.06018 * (width - 1456);
      return 60 + (86 - 60) * ((width - 1024) / (1456 - 1024));
    };
    const applyImages = () => {
      const currentGap = gap();
      const left = (active - 1 + pillars.length) % pillars.length;
      const right = (active + 1) % pillars.length;
      images.forEach((img, index) => {
        // clear old classes
        img.classList.remove('ct-img--active', 'ct-img--left', 'ct-img--right', 'ct-img--hidden');
        // clear old inline inline style gap
        img.style.setProperty('--ct-gap', '0px');

        if (index === active) {
          img.classList.add('ct-img--active');
        } else if (index === left) {
          img.classList.add('ct-img--left');
          img.style.setProperty('--ct-gap', currentGap + 'px');
        } else if (index === right) {
          img.classList.add('ct-img--right');
          img.style.setProperty('--ct-gap', currentGap + 'px');
        } else {
          img.classList.add('ct-img--hidden');
        }
      });
    };
    const animateWords = (text) => {
      quote.innerHTML = '';
      let wordIndex = 0;
      text.split('\n').forEach((line, lineIndex) => {
        if (lineIndex > 0) quote.appendChild(document.createElement('br'));
        if (!line.trim()) return;
        line.split(' ').forEach((word) => {
          const span = document.createElement('span');
          span.className = 'ct-word';
          span.textContent = `${word}\u00a0`;
          span.style.cssText = `display:inline-block;filter:blur(10px);opacity:0;transform:translateY(5px);transition:filter .22s ease ${wordIndex * 0.025}s,opacity .22s ease ${wordIndex * 0.025}s,transform .22s ease ${wordIndex * 0.025}s;`;
          quote.appendChild(span);
          span.offsetHeight;
          span.style.filter = 'blur(0)';
          span.style.opacity = '1';
          span.style.transform = 'translateY(0)';
          wordIndex += 1;
        });
      });
    };
    const updateText = () => {
      const pillar = pillars[active];
      name.textContent = `${pillar.num} - ${pillar.title}`;
      designation.textContent = '';
      animateWords(pillar.desc || '');
    };
    const goTo = (next) => {
      active = (next + pillars.length) % pillars.length;
      applyImages();
      updateText();
    };
    const startAuto = () => {
      if (hovered) return;
      window.clearInterval(autoTimer);
      autoTimer = window.setInterval(() => goTo(active + 1), 5000);
    };
    const stopAuto = () => {
      window.clearInterval(autoTimer);
      autoTimer = 0;
    };
    images.forEach((img, index) => {
      img.addEventListener('click', () => {
        if (index === active) return;
        stopAuto();
        goTo(index);
        startAuto();
      });
      const source = img.dataset.src;
      if (!source) return;
      img.addEventListener('load', () => { img.style.background = ''; }, { once: true });
      img.src = source;
      img.removeAttribute('data-src');
    });
    if (nextButton) nextButton.addEventListener('click', () => { stopAuto(); goTo(active + 1); startAuto(); });
    if (prevButton) prevButton.addEventListener('click', () => { stopAuto(); goTo(active - 1); startAuto(); });
    strip.addEventListener('mouseenter', () => { hovered = true; stopAuto(); });
    strip.addEventListener('mouseleave', () => { hovered = false; startAuto(); });
    strip.addEventListener('focusin', stopAuto);
    strip.addEventListener('focusout', (event) => {
      if (!strip.contains(event.relatedTarget)) startAuto();
    });
    let startX = 0;
    let startY = 0;
    strip.addEventListener('touchstart', (event) => {
      startX = event.touches[0].clientX;
      startY = event.touches[0].clientY;
    }, { passive: true });
    strip.addEventListener('touchmove', (event) => {
      const dx = event.touches[0].clientX - startX;
      const dy = event.touches[0].clientY - startY;
      if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 10) event.preventDefault();
    }, { passive: false });
    strip.addEventListener('touchend', (event) => {
      const dx = event.changedTouches[0].clientX - startX;
      const dy = event.changedTouches[0].clientY - startY;
      const angle = Math.abs(Math.atan2(Math.abs(dy), Math.abs(dx)) * 180 / Math.PI);
      if (Math.abs(dx) < 40 || angle > 30) return;
      stopAuto();
      goTo(dx < 0 ? active + 1 : active - 1);
      startAuto();
    }, { passive: true });
    document.addEventListener('keydown', (event) => {
      const rect = strip.getBoundingClientRect();
      if (rect.bottom < 0 || rect.top > window.innerHeight) return;
      if (event.key === 'ArrowLeft') { stopAuto(); goTo(active - 1); startAuto(); }
      if (event.key === 'ArrowRight') { stopAuto(); goTo(active + 1); startAuto(); }
    });
    window.addEventListener('resize', dom.debounce(applyImages, 120), { passive: true });
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stopAuto();
      else startAuto();
    });
    applyImages();
    updateText();
    startAuto();
    console.log('[Runtime] PhilosophyStrip mounted');
    } catch (e) {
      console.error('[Runtime] PhilosophyStrip failed', e);
    }
  });
})();
