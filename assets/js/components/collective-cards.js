(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady(() => {
    if (!dom.isTouch()) return;
    dom.qsa('.sk-collective-card').forEach((card) => {
      card.addEventListener('click', (event) => {
        const revealed = card.classList.contains('is-revealed');
        dom.qsa('.sk-collective-card.is-revealed').forEach((item) => item.classList.remove('is-revealed'));
        if (!revealed) {
          card.classList.add('is-revealed');
          event.preventDefault();
        }
      });
    });
  });
})();
