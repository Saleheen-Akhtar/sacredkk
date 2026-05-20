(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady(() => {
    const triggers = dom.qsa('.faq-trigger');
    if (!triggers.length) return;
    triggers.forEach((trigger) => {
      trigger.addEventListener('click', () => {
        const expanded = trigger.getAttribute('aria-expanded') === 'true';
        triggers.forEach((item) => {
          item.setAttribute('aria-expanded', 'false');
          const body = document.getElementById(item.getAttribute('aria-controls'));
          if (body) body.classList.remove('open');
        });
        if (expanded) return;
        trigger.setAttribute('aria-expanded', 'true');
        const target = document.getElementById(trigger.getAttribute('aria-controls'));
        if (target) target.classList.add('open');
      });
    });
  });
})();
