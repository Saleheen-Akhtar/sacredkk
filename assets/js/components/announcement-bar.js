(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady(() => {
    dom.qsa('.sk-announcement-bar').forEach((bar) => {
      const dismissButton = bar.querySelector('.sk-ann-close[data-ann-id]');
      const announcementId = dismissButton ? dismissButton.dataset.annId : '';
      const key = 'sk_ann_dismissed';
      try {
        if (announcementId && sessionStorage.getItem(key) === announcementId) {
          bar.style.display = 'none';
          return;
        }
      } catch (error) {}
      if (dismissButton) {
        dismissButton.addEventListener('click', () => {
          bar.style.display = 'none';
          try {
            sessionStorage.setItem(key, announcementId);
          } catch (error) {}
        });
      }
      const countdownEnd = bar.dataset.countdownEnd;
      if (!countdownEnd) return;
      const end = new Date(countdownEnd);
      const hEl = bar.querySelector('[data-unit="h"]');
      const mEl = bar.querySelector('[data-unit="m"]');
      const sEl = bar.querySelector('[data-unit="s"]');
      if (!hEl || !mEl || !sEl) return;
      const pad = (value) => String(value).padStart(2, '0');
      const tick = () => {
        const diff = end.getTime() - Date.now();
        if (diff <= 0) {
          bar.style.display = 'none';
          window.clearInterval(timer);
          return;
        }
        const total = Math.floor(diff / 1000);
        hEl.textContent = pad(Math.floor(total / 3600));
        mEl.textContent = pad(Math.floor((total % 3600) / 60));
        sEl.textContent = pad(total % 60);
      };
      tick();
      const timer = window.setInterval(tick, 1000);
    });
  });
})();
