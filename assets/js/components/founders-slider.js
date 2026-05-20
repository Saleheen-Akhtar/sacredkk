(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('FoundersSlider', () => {
    const initFoundersSlider = () => {
      try {
    const root = dom.qs('#sk-fts');
    if (!root) {
      console.warn('[FoundersSlider] Missing root element');
      return;
    }
    const thumbs = dom.qsa('.sk-fts-thumb', root);
    if (!thumbs.length) {
      console.warn('[FoundersSlider] Missing thumbs');
      return;
    }
    const photos = dom.qsa('.sk-fts-photo-frame', root);
    const panels = dom.qsa('.sk-fts-panel', root);
    const currentCounter = dom.qs('#sk-fts-cur', root);
    let index = 0;
    let animating = false;
    const total = thumbs.length;
    const activate = (nextIndex, direction) => {
      if (animating || nextIndex === index) return;
      animating = true;
      const leaving = index;
      index = (nextIndex + total) % total;
      thumbs[leaving] && thumbs[leaving].classList.remove('is-active');
      thumbs[index] && thumbs[index].classList.add('is-active');
      if (currentCounter) currentCounter.textContent = String(index + 1).padStart(2, '0');
      const outgoingPhoto = photos[leaving];
      const incomingPhoto = photos[index];
      if (outgoingPhoto) {
        outgoingPhoto.classList.add(direction === 'next' ? 'is-leaving-up' : 'is-leaving-down');
        window.setTimeout(() => outgoingPhoto.classList.remove('is-active', 'is-leaving-up', 'is-leaving-down'), 360);
      }
      if (incomingPhoto) {
        incomingPhoto.classList.add(direction === 'next' ? 'is-entering-up' : 'is-entering-down', 'is-active');
        window.setTimeout(() => incomingPhoto.classList.remove('is-entering-up', 'is-entering-down'), 360);
      }
      const outgoingPanel = panels[leaving];
      const incomingPanel = panels[index];
      if (outgoingPanel) {
        outgoingPanel.classList.add('is-exiting');
        window.setTimeout(() => outgoingPanel.classList.remove('is-active', 'is-exiting'), 260);
      }
      if (incomingPanel) {
        incomingPanel.classList.add('is-active');
      }
      window.setTimeout(() => { animating = false; }, 380);
    };
    thumbs.forEach((thumb, thumbIndex) => {
      thumb.addEventListener('click', () => activate(thumbIndex, thumbIndex > index ? 'next' : 'prev'));
    });
    let startX = 0;
    let startY = 0;
    const swipeTargets = [root, dom.qs('#sk-fts-photo-stage', root)].filter(Boolean);
    swipeTargets.forEach((element) => {
      element.addEventListener('touchstart', (event) => {
        startX = event.touches[0].clientX;
        startY = event.touches[0].clientY;
      }, { passive: true });
      element.addEventListener('touchend', (event) => {
        const dx = event.changedTouches[0].clientX - startX;
        const dy = event.changedTouches[0].clientY - startY;
        if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy) * 2) {
          activate(dx < 0 ? index + 1 : index - 1, dx < 0 ? 'next' : 'prev');
        }
      }, { passive: true });
    });
    document.addEventListener('keydown', (event) => {
      const rect = root.getBoundingClientRect();
      if (rect.bottom < 0 || rect.top > window.innerHeight) return;
      if (event.key === 'ArrowLeft') activate(index - 1, 'prev');
      if (event.key === 'ArrowRight') activate(index + 1, 'next');
    });
      console.log('[Runtime] FoundersSlider mounted');
      } catch (e) {
        console.error('[Runtime] FoundersSlider failed', e);
      }
    };
    if (document.readyState === 'complete') initFoundersSlider();
    else window.addEventListener('load', initFoundersSlider);
  });
})();
