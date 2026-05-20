(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Journal', () => {
    try {
    const input = dom.qs('.sk-search-input');
    const noResults = document.getElementById('sk-no-results');
    const postsWrap = dom.qs('.sk-home-posts-section .wrap');
    
    if (!input || !postsWrap) {
      console.warn('[Journal] Preview/Filter missing');
      return;
    }
    
    const articles = dom.qsa('article[data-search]', postsWrap);
    if (!articles.length) return;
    const grid = dom.qs('.sk-blog-grid', postsWrap);
    const applyFilter = () => {
      const query = input.value.trim().toLowerCase();
      if (!query) {
        articles.forEach((article) => {
          article.style.display = '';
          article.removeAttribute('data-hidden');
        });
        if (grid) grid.style.display = '';
        if (noResults) noResults.classList.remove('visible');
        return;
      }
      const words = query.split(/\s+/).filter(Boolean);
      let visibleCount = 0;
      articles.forEach((article) => {
        const haystack = (article.getAttribute('data-search') || '').toLowerCase();
        const matches = words.every((word) => haystack.includes(word));
        article.style.display = matches ? '' : 'none';
        article.toggleAttribute('data-hidden', !matches);
        if (matches) visibleCount += 1;
      });
      if (grid) {
        const anyVisible = dom.qsa('article[data-search]', grid).some((article) => article.getAttribute('data-hidden') !== 'true');
        grid.style.display = anyVisible ? '' : 'none';
      }
      if (noResults) noResults.classList.toggle('visible', visibleCount === 0);
    };
    const debounced = dom.debounce(applyFilter, 120);
    input.addEventListener('input', debounced);
    input.addEventListener('keyup', debounced);
    input.addEventListener('search', applyFilter);
    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', (event) => {
        if (!input.value.trim()) {
          event.preventDefault();
          input.focus();
        }
      });
    }
    if (input.value.trim()) applyFilter();

    console.log('[Runtime] Journal mounted');
    } catch (e) {
      console.error('[Runtime] Journal failed:', e);
    }
  });
})();
