(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;

  dom.onReady('Hero', () => {
    try {
    const motion = app.motion || {};
    const config = dom.readJSON('sk-hero-data');
    const fromEl = document.getElementById('hero-from');
    const toEl = document.getElementById('hero-to');

    if (!config || !Array.isArray(config.pairs) || !fromEl || !toEl || !config.pairs.length) {
      console.warn('[Hero] Missing elements or data');
      return;
    }

    const blur = '18px';
    const charDuration = motion.reduced ? 0 : 90;
    const charStagger = motion.reduced ? 0 : 42;
    let pairIndex = 0;
    let timer = 0;

    const scaleToFit = (text) => {
      toEl.textContent = text;
      toEl.style.fontSize = '';
      const vw = window.innerWidth;
      const targetWidth = vw * (text.replace(/\s/g, '').length <= 5 ? 0.96 : 0.82);
      const currentWidth = toEl.scrollWidth || 1;
      const currentSize = parseFloat(window.getComputedStyle(toEl).fontSize) || 80;
      const nextSize = Math.min(Math.max(currentSize * (targetWidth / currentWidth), 40), vw * 0.96);
      toEl.style.fontSize = `${nextSize}px`;
    };

    const renderChars = (text) => {
      toEl.textContent = '';
      text.split('').forEach((char) => {
        const span = document.createElement('span');
        span.className = 'char';
        span.textContent = char === ' ' ? '\u00a0' : char;
        span.style.display = 'inline-block';
        span.style.opacity = '0';
        span.style.filter = `blur(${blur})`;
        toEl.appendChild(span);
      });
      return dom.qsa('.char', toEl);
    };

    const revealPair = (pair) => {
      let escapedFrom = dom.escapeHTML(pair.from);
      if (escapedFrom.length > 12 && escapedFrom.includes(' ')) {
        const lastSpace = escapedFrom.lastIndexOf(' ');
        escapedFrom = escapedFrom.substring(0, lastSpace) + '<br>' + escapedFrom.substring(lastSpace + 1);
      }
      fromEl.innerHTML = escapedFrom;
      fromEl.style.opacity = '0';
      fromEl.style.filter = `blur(${blur})`;
      toEl.style.opacity = '1';
      scaleToFit(pair.to);

      const chars = renderChars(pair.to);
      fromEl.style.transition = `opacity ${motion.duration?.base ? motion.duration.base * 1000 : 800}ms ${motion.easing?.soft || 'ease'}, filter ${motion.duration?.base ? motion.duration.base * 1000 : 800}ms ${motion.easing?.soft || 'ease'}`;
      fromEl.style.opacity = '1';
      fromEl.style.filter = 'blur(0)';
      chars.forEach((char, index) => {
        window.setTimeout(() => {
          char.style.transition = `opacity ${charDuration}ms ease, filter ${charDuration}ms ease, transform ${charDuration}ms ease`;
          char.style.opacity = '1';
          char.style.filter = 'blur(0)';
        }, index * charStagger);
      });

      const totalReveal = chars.length * charStagger + charDuration + (motion.duration?.heroHold || 2000);
      timer = window.setTimeout(() => {
        fromEl.style.opacity = '0';
        fromEl.style.filter = `blur(${blur})`;
        [...chars].reverse().forEach((char, index) => {
          window.setTimeout(() => {
            char.style.opacity = '0';
            char.style.filter = `blur(${blur})`;
          }, index * charStagger);
        });
        timer = window.setTimeout(() => {
          pairIndex = (pairIndex + 1) % config.pairs.length;
          revealPair(config.pairs[pairIndex]);
        }, chars.length * charStagger + charDuration + 120);
      }, totalReveal);
    };

    revealPair(config.pairs[pairIndex]);
    window.addEventListener('resize', dom.debounce(() => scaleToFit(toEl.textContent || ''), 140), { passive: true });
    window.addEventListener('beforeunload', () => window.clearTimeout(timer));
    } catch (e) {
      console.error('[Runtime] Hero failed', e);
    }
  });
})();
