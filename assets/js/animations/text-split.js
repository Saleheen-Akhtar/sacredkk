(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('TextSplit', () => {
    dom.qsa('[data-split="wave"]').forEach((el) => {
      if (el.dataset.splitReady) return;
      el.dataset.splitReady = '1';
      const text = el.textContent || '';
      el.textContent = '';
      text.split('').forEach((char, index) => {
        const span = document.createElement('span');
        const isSpace = char === ' ';
        span.className = `sk-wave-char${isSpace ? ' is-space' : ''}`;
        span.textContent = isSpace ? '\u00a0' : char;
        if (!isSpace) span.style.animationDelay = `${(index * (app.motion?.stagger?.md || 0.08)).toFixed(2)}s`;
        el.appendChild(span);
      });
    });
  });
})();
