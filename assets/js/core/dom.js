(function () {
  const app = window.SK = window.SK || {};
  if (app.dom) return;
  let bodyLockCount = 0;
  const readyQueue = [];
  let readyFlushed = false;
  const ready = () => {
    if (readyFlushed) return;
    readyFlushed = true;
    while (readyQueue.length) {
      const { name, fn } = readyQueue.shift();
      try {
        fn();
        console.log(`[Runtime] ${name} initialized`);
      } catch (e) {
        console.error(`[Runtime] ${name} failed:`, e);
      }
    }
    document.dispatchEvent(new CustomEvent('sk:ready'));
  };
  app.dom = {
    qs(selector, scope) {
      return (scope || document).querySelector(selector);
    },
    qsa(selector, scope) {
      return Array.from((scope || document).querySelectorAll(selector));
    },
    onReady(arg1, arg2) {
      const name = typeof arg1 === 'string' ? arg1 : 'Unnamed Module';
      const fn = typeof arg1 === 'function' ? arg1 : arg2;
      if (readyFlushed) {
        try { fn(); } catch (e) { console.error(`[Runtime] ${name} failed:`, e); }
      } else {
        readyQueue.push({ name, fn });
      }
    },
    flushReady: ready,
    readJSON(id) {
      const el = document.getElementById(id);
      if (!el) return null;
      try {
        return JSON.parse(el.textContent || '');
      } catch (error) {
        return null;
      }
    },
    escapeHTML(value) {
      return String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    },
    isTouch() {
      return window.matchMedia('(hover: none) and (pointer: coarse)').matches;
    },
    debounce(fn, wait) {
      let timer = 0;
      return function () {
        const args = arguments;
        clearTimeout(timer);
        timer = window.setTimeout(() => fn.apply(this, args), wait);
      };
    },
    lockBody() {
      bodyLockCount += 1;
      if (bodyLockCount === 1) {
        const scrollY = window.scrollY;
        document.body.dataset.skScrollY = String(scrollY);
        document.documentElement.style.overflow = 'hidden';
        document.body.classList.add('sk-scroll-locked');
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
      }
    },
    unlockBody() {
      if (bodyLockCount === 0) return;
      bodyLockCount -= 1;
      if (bodyLockCount === 0) {
        const scrollY = parseInt(document.body.dataset.skScrollY || '0', 10);
        document.documentElement.style.overflow = '';
        document.body.classList.remove('sk-scroll-locked');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        window.scrollTo(0, scrollY);
      }
    }
  };
})();
