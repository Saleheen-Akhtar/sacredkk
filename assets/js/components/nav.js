(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Nav', () => {
    document.documentElement.style.setProperty('--sidenav-offset', '0px');
    const panel = dom.qs('#sk-sidenav');
    const backdrop = dom.qs('#sk-sidenav-backdrop');
    const hamburger = dom.qs('#sk-hamburger');
    const hero = dom.qs('.hero--fullscreen, .hero--split, #hero, .hero-section');
    const links = panel ? dom.qsa('a', panel) : [];
    if (panel && hero && app.scroll) {
      app.scroll.onFrame((scrollY) => {
        if (window.innerWidth <= 900) {
          panel.classList.toggle('sk-sidenav--visible', scrollY >= 60);
          return;
        }
        panel.classList.toggle('sk-sidenav--visible', scrollY >= (hero.offsetHeight || window.innerHeight) * 0.85);
      });
    } else if (panel) {
      panel.classList.add('sk-sidenav--visible');
    }
    const open = () => {
      if (!panel || !hamburger) return;
      panel.classList.add('open');
      if (backdrop) backdrop.classList.add('visible');
      hamburger.classList.add('open');
      hamburger.setAttribute('aria-expanded', 'true');
      dom.lockBody();
      if (app.scroll) app.scroll.pause();
    };
    const close = () => {
      if (!panel || !hamburger) return;
      panel.classList.remove('open');
      if (backdrop) backdrop.classList.remove('visible');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
      dom.unlockBody();
      if (app.scroll) app.scroll.resume();
    };
    app.nav = app.nav || {};
    app.nav.close = close;
    if (hamburger) {
      hamburger.addEventListener('click', () => {
        panel.classList.contains('open') ? close() : open();
      });
    }
    if (backdrop) backdrop.addEventListener('click', close);
    links.forEach((link) => {
      link.addEventListener('click', () => {
        if (window.innerWidth <= 900) close();
      });
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') close();
    });
    const updateActive = () => {
      const scrollPoint = window.scrollY + window.innerHeight * 0.45;
      let activeId = '';
      ['about', 'offerings', 'journal-preview', 'collective', 'faq', 'contact'].forEach((id) => {
        const section = document.getElementById(id);
        if (section && section.offsetTop <= scrollPoint) activeId = id;
      });
      links.forEach((link) => {
        const href = link.getAttribute('href') || '';
        const isCurrent = activeId && (href.endsWith(`/#${activeId}`) || href === `#${activeId}`);
        link.classList.toggle('active', Boolean(isCurrent));
      });
    };
    if (app.scroll) app.scroll.onFrame(updateActive);
  });
})();
