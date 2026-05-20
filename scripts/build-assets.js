const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');
const cssFiles = [
  'assets/css/core/theme.css',
  'assets/css/core/reset.css',
  'assets/css/core/typography.css',
  'assets/css/core/buttons.css',
  'assets/css/sections/about.css',
  'assets/css/sections/homepage.css',
  'assets/css/sections/editorial.css',
  'assets/css/core/legacy.css',
  'assets/css/sections/navigation.css',
  'assets/css/sections/announcement.css',
  'assets/css/sections/journal-preview.css',
  'assets/css/sections/hero.css',
  'assets/css/sections/offerings.css',
  'assets/css/sections/philosophy-strip.css',
  'assets/css/sections/faq.css',
  'assets/css/utilities/hooks.css',
  'assets/css/animations/runtime.css',
  'assets/css/responsive/about.css',
  'assets/css/responsive/hero.css',
  'assets/css/responsive/offerings.css',
  'assets/css/responsive/mobile.css'
];
const vendorFiles = [
  'assets/js/vendors/split-type.min.js'
];
const appFiles = [
  'assets/js/core/dom.js',
  'assets/js/core/motion-config.js',
  'assets/js/core/scroll-timelines.js',
  'assets/js/animations/reveal.js',
  'assets/js/animations/parallax.js',
  'assets/js/animations/stagger.js',
  'assets/js/animations/magnetic.js',
  'assets/js/animations/text-split.js',
  'assets/js/components/announcement-bar.js',
  'assets/js/components/nav.js',
  'assets/js/components/faq.js',
  'assets/js/components/contact.js',
  'assets/js/components/share.js',
  'assets/js/components/collective-cards.js',
  'assets/js/components/founders-slider.js',
  'assets/js/components/hero.js',
  'assets/js/components/offerings-modal.js',
  'assets/js/components/philosophy-strip.js',
  'assets/js/components/journal-filter.js',
  'assets/js/core/boot.js'
];

function read(relativePath) {
  return fs.readFileSync(path.join(root, relativePath), 'utf8');
}

function write(relativePath, content) {
  const target = path.join(root, relativePath);
  fs.mkdirSync(path.dirname(target), { recursive: true });
  fs.writeFileSync(target, content);
}

function minifyCss(content) {
  return content
    .replace(/\r\n/g, '\n')
    .replace(/\/\*[\s\S]*?\*\//g, '')
    .replace(/\s+/g, ' ')
    .replace(/\s*([{}:;,>])\s*/g, '$1')
    .replace(/;}/g, '}')
    .trim();
}

function compactJs(content) {
  return content
    .replace(/\r\n/g, '\n')
    .split('\n')
    .map((line) => line.trim())
    .filter(Boolean)
    .join('\n');
}

const cssBundle = minifyCss(cssFiles.map(read).join('\n'));
const vendorBundle = vendorFiles.map(read).join('\n');
const appBundle = compactJs(appFiles.map(read).join('\n'));

write('assets/dist/app.min.css', cssBundle);
write('assets/dist/vendor.min.js', vendorBundle);
write('assets/dist/app.min.js', appBundle);
