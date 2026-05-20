(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Magnetic', () => {
    if (app.motion.reduced || dom.isTouch()) return;
    dom.qsa('[data-magnetic]').forEach((el) => {
      el.addEventListener('mousemove', (event) => {
        const rect = el.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width - 0.5) * 12;
        const y = ((event.clientY - rect.top) / rect.height - 0.5) * 12;
        el.style.transform = `translate3d(${x}px, ${y}px, 0)`;
      });
      el.addEventListener('mouseleave', () => {
        el.style.transform = '';
      });
    });
  });
})();
