<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <!-- Non-blocking Google Fonts -->
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&family=Cormorant:ital,wght@0,300;0,400;1,300;1,400&family=Jost:wght@300;400;500&display=swap" onload="this.onload=null;this.rel='stylesheet'"/>
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&family=Cormorant:ital,wght@0,300;0,400;1,300;1,400&family=Jost:wght@300;400;500&display=swap"/></noscript>
  <?php /* split-type is enqueued as a local deferred script via functions.php (wp_register_script).
           The CDN copy that used to live here was a duplicate — it blocked the parser and
           caused two versions of the library to load on every page. Removed. */ ?>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
/* ── Announcement Bar — above header, every page ── */
$sk_ann = function_exists('sk_get_active_announcement') ? sk_get_active_announcement() : null;
if ($sk_ann && !empty($sk_ann['message'])) :
    $ann_id        = 'sk-ann-' . $sk_ann['id'];
    $ann_bg        = esc_attr($sk_ann['bg_color']);
    $ann_fg        = esc_attr($sk_ann['text_color']);
    $ann_countdown = !empty($sk_ann['countdown_end']) ? esc_attr($sk_ann['countdown_end']) : '';
?>
<div
  id="<?php echo esc_attr($ann_id); ?>"
  class="sk-announcement-bar sk-ann-v2"
  style="background:<?php echo $ann_bg; ?>;color:<?php echo $ann_fg; ?>"
  role="banner"
  aria-label="Site announcement"
  <?php echo $ann_countdown ? 'data-countdown-end="' . $ann_countdown . '"' : ''; ?>
>
  <div class="sk-ann-inner wrap">

    <!-- Left: icon + text -->
    <div class="sk-ann-left">
      <svg class="sk-ann-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M9 9h.01"/><path d="m15 9-6 6"/><path d="M15 15h.01"/>
      </svg>
      <div class="sk-ann-text-wrap">
        <span class="sk-ann-message"><?php echo esc_html($sk_ann['message']); ?></span>
        <?php if (!empty($sk_ann['subtitle'])): ?>
        <span class="sk-ann-subtitle"><?php echo esc_html($sk_ann['subtitle']); ?></span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Center: countdown timer (hidden if no end date) -->
    <?php if ($ann_countdown): ?>
    <div class="sk-ann-countdown" id="<?php echo esc_attr($ann_id); ?>-timer" aria-live="off">
      <div class="sk-ann-time-block"><span class="sk-ann-time-num" data-unit="h">00</span><span class="sk-ann-time-label"><?php esc_html_e('Hours','sacred-kompass'); ?></span></div>
      <span class="sk-ann-time-sep" aria-hidden="true">:</span>
      <div class="sk-ann-time-block"><span class="sk-ann-time-num" data-unit="m">00</span><span class="sk-ann-time-label"><?php esc_html_e('Mins','sacred-kompass'); ?></span></div>
      <span class="sk-ann-time-sep" aria-hidden="true">:</span>
      <div class="sk-ann-time-block"><span class="sk-ann-time-num" data-unit="s">00</span><span class="sk-ann-time-label"><?php esc_html_e('Secs','sacred-kompass'); ?></span></div>
    </div>
    <?php endif; ?>

    <!-- Right: CTA + close -->
    <div class="sk-ann-right">
      <?php if (!empty($sk_ann['cta_text']) && !empty($sk_ann['cta_url'])): ?>
      <a href="<?php echo esc_url($sk_ann['cta_url']); ?>" class="sk-ann-cta" style="border-color:<?php echo $ann_fg; ?>;color:<?php echo $ann_fg; ?>">
        <?php echo esc_html($sk_ann['cta_text']); ?>
      </a>
      <?php endif; ?>
      <?php if ($sk_ann['dismissible']): ?>
      <button
        class="sk-ann-close"
        aria-label="<?php esc_attr_e('Dismiss announcement','sacred-kompass'); ?>"
        style="color:<?php echo $ann_fg; ?>"
        data-ann-id="<?php echo (int)$sk_ann['id']; ?>"
      >&times;</button>
      <?php endif; ?>
    </div>

  </div>
</div>
<?php endif; ?>

<?php
// Load nav items from CPT (with static fallback)
$sk_nav_items   = function_exists('sk_get_nav_items') ? sk_get_nav_items() : [];
$sk_nav_cta_label = sk_option('nav_cta_label', '');
$sk_nav_cta_url   = sk_option('nav_cta_url',   home_url('/#contact'));

// Static fallback if CPT helper not yet available (e.g. on first load)
if (empty($sk_nav_items)) {
  $sk_nav_items = [
    ['label'=>'About',          'url' => home_url('/#about'),           'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
    ['label'=>'Our Services',   'url' => home_url('/#offerings'),       'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
    ['label'=>'Journal',        'url' => home_url('/#journal-preview'), 'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
    ['label'=>'FAQ',            'url' => home_url('/#faq'),             'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
    ['label'=>'The Collective', 'url' => home_url('/collective/'),      'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
  ];
}

// Helper: build nav link URL (absolute if starts with http, else home_url-prefixed)
function sk_nav_link_url(string $raw): string {
  if (strpos($raw, 'http') === 0) return esc_url($raw);
  // Anchor or relative
  return esc_url(home_url(ltrim($raw, '/')));
}

// Helper: render highlight class / element
function sk_nav_item_class(string $highlight): string {
  return match($highlight) {
    'btn'      => 'btn btn-primary',
    'btn-ghost'=> 'btn btn-ghost',
    'gold'     => 'sk-nav-gold',
    default    => '',
  };
}
?>

<!-- ══════════════════════════════════════════════
     SIDE PANEL NAV — fixed left column
     Mobile: slides in from left via hamburger
     ══════════════════════════════════════════════ -->

<!-- Hamburger trigger (mobile only) -->
<button class="sk-hamburger" id="sk-hamburger"
        aria-label="<?php esc_attr_e('Toggle menu','sacred-kompass'); ?>"
        aria-expanded="false"
        aria-controls="sk-sidenav">
  <span></span>
  <span></span>
  <span></span>
</button>

<!-- Side panel overlay (mobile backdrop) -->
<div class="sk-sidenav-backdrop" id="sk-sidenav-backdrop"></div>

<!-- Side panel -->
<nav class="sk-sidenav" id="sk-sidenav"
     role="navigation"
     aria-label="<?php esc_attr_e('Main navigation','sacred-kompass'); ?>">

  <!-- Logo -->
  <a class="sk-sidenav-logo" href="<?php echo esc_url(home_url('/')); ?>"
     aria-label="<?php esc_attr_e('Sacred Kompass home','sacred-kompass'); ?>">
    <?php $logo = sk_logo_html('sk-sidenav-logo-img'); ?>
    <?php if ($logo) : echo $logo; ?>
      <span class="sk-sidenav-logo-name">Sacred <em>Kompass</em></span>
    <?php else : ?>
      <span class="sk-sidenav-logo-name">Sacred <em>Kompass</em></span>
    <?php endif; ?>
  </a>

  <!-- Nav links -->
  <ul class="sk-sidenav-links">
    <?php foreach ($sk_nav_items as $item):
      $link_class = sk_nav_item_class($item['highlight']);
      $href = sk_nav_link_url($item['url']);
      $icon = !empty($item['icon']) ? '<span class="sk-nav-icon" aria-hidden="true">' . esc_html($item['icon']) . '</span>' : '';
      $is_active = false;
      $label_lower = strtolower($item['label']);
      if ($label_lower === 'journal' && (is_home() || is_singular('post') || is_category() || is_tag())) $is_active = true;
      elseif ($label_lower === 'the collective' && is_page('collective')) $is_active = true;
      $final_class = trim(($link_class ? $link_class . ' ' : '') . ($is_active ? 'active' : ''));
    ?>
    <li data-label="<?php echo esc_attr($item['label']); ?>">
      <a href="<?php echo $href; ?>"
         target="<?php echo esc_attr($item['target']); ?>"
         <?php echo ($item['target'] === '_blank') ? 'rel="noopener noreferrer"' : ''; ?>
         <?php echo $final_class ? 'class="' . esc_attr($final_class) . '"' : ''; ?>>
        <?php echo $icon; ?><span><?php echo esc_html($item['label']); ?></span>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>

  <!-- CTA contact button -->
  <?php if ( $sk_nav_cta_label ) : ?>
  <div class="sk-sidenav-cta">
    <a href="<?php echo esc_url( $sk_nav_cta_url ); ?>" class="btn btn-primary sk-sidenav-cta-btn">
      <?php echo esc_html( $sk_nav_cta_label ); ?>
    </a>
  </div>
  <?php else : ?>
  <div class="sk-sidenav-cta">
    <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-primary sk-sidenav-cta-btn">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      <span>Contact</span>
    </a>
  </div>
  <?php endif; ?>

</nav>



