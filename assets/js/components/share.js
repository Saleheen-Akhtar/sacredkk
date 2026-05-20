(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady(() => {
    dom.qsa('.sk-share-copy').forEach((button) => {
      button.addEventListener('click', () => {
        const url = button.dataset.url || window.location.href;
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(url).then(() => {
          button.setAttribute('aria-label', 'Link copied');
          button.style.background = 'var(--sage)';
          button.style.color = '#fff';
          window.setTimeout(() => {
            button.setAttribute('aria-label', 'Copy link');
            button.style.background = '';
            button.style.color = '';
          }, 2000);
        });
      });
    });
  });
})();
