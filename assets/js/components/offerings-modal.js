(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady('Offerings', () => {
    try {
    const track = document.getElementById('sk-rc-track');
    
    if (!track) {
      console.warn('[Offerings] Track not found');
      return;
    }

    const offerings = dom.readJSON('sk-offerings-data');
    const overlay = document.getElementById('sk-rc-overlay');
    const backdrop = document.getElementById('sk-rc-backdrop');
    const box = document.getElementById('sk-rc-box');
    const closeButton = document.getElementById('sk-rc-close');
    const content = document.getElementById('sk-rc-overlay-content');

    if (!offerings || !overlay || !box || !content) {
      console.warn('[Offerings] Modal elements or data missing');
      return;
    }

    let activeSlug = '';
    const fmtLabels = { inperson: 'In-person', online: 'Online', both: 'In-person & Online', hybrid: 'Hybrid' };
    const buildOverlay = (item) => {
      activeSlug = item.slug || '';
      const existingHeader = box.querySelector('.sk-rc-ol-header');
      const existingFooter = box.querySelector('.sk-rc-ol-action-row');
      if (existingHeader) existingHeader.remove();
      if (existingFooter) existingFooter.remove();
      const header = document.createElement('div');
      header.className = 'sk-rc-ol-header';
      header.innerHTML = `<p class="sk-rc-ol-tag">${dom.escapeHTML(item.tag || '')}</p><p class="sk-rc-ol-num">${dom.escapeHTML(item.num || '')}</p><p class="sk-rc-ol-title">${dom.escapeHTML(item.title || '')}</p>`;
      box.insertBefore(header, box.firstChild);
      let badges = '';
      if (item.duration) badges += `<span class="sk-rc-ol-badge">&#x23F1; ${dom.escapeHTML(item.duration)}</span>`;
      if (item.format) badges += `<span class="sk-rc-ol-badge">&#128205; ${dom.escapeHTML(fmtLabels[item.format] || item.format)}</span>`;
      if (item.capacity) badges += `<span class="sk-rc-ol-badge">&#128101; ${dom.escapeHTML(item.capacity)}</span>`;
      content.innerHTML = `<div class="sk-rc-ol-quote-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg></div><p class="sk-rc-ol-desc">${dom.escapeHTML(item.desc || '')}</p>${badges ? `<div class="sk-rc-ol-badges">${badges}</div>` : ''}`;
      const ctaBase = (window.skAppData && window.skAppData.contactUrl) || '/#contact';
      const ctaHref = item.cta_url || `${ctaBase}${ctaBase.includes('?') ? '&' : '?'}service=${encodeURIComponent(item.slug || '')}`;
      box.insertAdjacentHTML('beforeend', `<div class="sk-rc-ol-action-row"><div class="sk-rc-ol-price-wrap">${item.price ? `<span class="sk-rc-ol-price-label">From</span><span class="sk-rc-ol-price">${dom.escapeHTML(item.price)}</span>` : ''}${item.availability ? `<span class="sk-rc-ol-avail">&#x23F3; ${dom.escapeHTML(item.availability)}</span>` : ''}</div><a href="${dom.escapeHTML(ctaHref)}" class="btn btn-primary sk-rc-ol-cta" id="sk-rc-enquire-btn" data-magnetic>Enquire about this pathway</a></div>`);
      content.scrollTop = 0;
    };
    const openCard = (index) => {
      if (!offerings || !overlay || !box || !content) {
        console.warn('[Offerings Modal] Missing required elements or JSON data. Modal disabled.');
        return;
      }

      const item = offerings[index];
      if (!item) return;
      buildOverlay(item);
      overlay.hidden = false;
      dom.lockBody();
      if (app.scroll) app.scroll.pause();
      overlay.offsetHeight;
      overlay.classList.add('is-open');
    };
    const closeCard = () => {
      overlay.classList.remove('is-open');
      if (app.scroll) app.scroll.resume();
      dom.unlockBody();
      window.setTimeout(() => {
        overlay.hidden = true;
      }, 320);
    };
    
    app.offerings = app.offerings || {};
    app.offerings.open = openCard;

    let dragX = null;
    let dragging = false;
    track.addEventListener('mousedown', (event) => {
      dragX = event.clientX;
      dragging = false;
    });
    track.addEventListener('mousemove', (event) => {
      if (dragX === null) return;
      const delta = event.clientX - dragX;
      if (Math.abs(delta) > 4) {
        dragging = true;
        track.classList.add('is-dragging');
      }
      if (dragging) {
        track.scrollLeft -= delta;
        dragX = event.clientX;
      }
    });
    ['mouseup', 'mouseleave'].forEach((name) => {
      track.addEventListener(name, () => {
        dragX = null;
        window.setTimeout(() => {
          dragging = false;
          track.classList.remove('is-dragging');
        }, 50);
      });
    });
    track.addEventListener('click', (event) => {
      if (dragging && !dom.isTouch()) event.stopPropagation();
    }, true);
    track.querySelectorAll('.sk-rc-card').forEach((card) => {
      card.addEventListener('click', () => openCard(parseInt(card.dataset.index || '0', 10)));
    });
    if (dom.isTouch()) {
      let startScroll = 0;
      let startX = 0;
      let startY = 0;
      track.addEventListener('touchstart', (event) => {
        startScroll = track.scrollLeft;
        startX = event.touches[0].clientX;
        startY = event.touches[0].clientY;
      }, { passive: true });
      track.addEventListener('touchend', (event) => {
        const dx = Math.abs(event.changedTouches[0].clientX - startX);
        const dy = Math.abs(event.changedTouches[0].clientY - startY);
        if (Math.abs(track.scrollLeft - startScroll) > 8 || dx > 12 || dy > 12) return;
        const target = event.target.closest('.sk-rc-card');
        if (!target) return;
        event.preventDefault();
        openCard(parseInt(target.dataset.index || '0', 10));
      }, { passive: false });
    }
    
    if (overlay && box) {
      if (closeButton) closeButton.addEventListener('click', closeCard);
      if (backdrop) backdrop.addEventListener('click', closeCard);
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !overlay.hidden) closeCard();
      });
      overlay.addEventListener('wheel', (event) => {
        if (box.contains(event.target)) {
          event.stopPropagation();
        } else {
          event.preventDefault();
          event.stopPropagation();
        }
      }, { passive: false });
      overlay.addEventListener('touchmove', (event) => {
        if (box.contains(event.target)) {
          event.stopPropagation();
        } else {
          event.preventDefault();
          event.stopPropagation();
        }
      }, { passive: false });
    }

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('sk-rc-card-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15 });
      track.querySelectorAll('.sk-rc-card-wrap').forEach((wrap) => observer.observe(wrap));
    }

      console.log('[Runtime] Offerings mounted');
    } catch (e) {
      console.error('[Runtime] Offerings failed:', e);
    }
  });
})();
