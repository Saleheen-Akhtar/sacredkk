<?php
/**
 * Hero — v15: FROM top-left, TO large centered, locked positions, sequential fade.
 * All content via Sacred Kompass → Settings → Hero (CPT).
 */
$bg_img = sk_option('hero_bg_image', '');

$pairs_raw = get_option('options_sk_hero_pairs_json', '');
$pairs_opt = $pairs_raw ? json_decode($pairs_raw, true) : [];
$transform_pairs = (!empty($pairs_opt)) ? $pairs_opt : [
    ['from' => 'Despair',          'to' => 'Hope'],
    ['from' => 'Business Failure', 'to' => 'Profitability'],
    ['from' => 'Resentment',       'to' => 'Forgiveness'],
    ['from' => 'Adversity',        'to' => 'Opportunity'],
    ['from' => 'Hatred',           'to' => 'Peace'],
    ['from' => 'Lonely',           'to' => 'Couplehood'],
    ['from' => 'Impulsive',        'to' => 'Aligned'],
    ['from' => 'Confusion',        'to' => 'Clarity'],
    ['from' => 'Stagnant',         'to' => 'Evolving'],
    ['from' => 'Mistrust',         'to' => 'Faith'],
    ['from' => 'Lethargy',         'to' => 'Vitality'],
];
?>
<section class="hero hero--fullscreen" aria-label="<?php esc_attr_e('Welcome to Sacred Kompass','sacred-kompass'); ?>">

  <!-- Background -->
  <div class="hero-bg-layer" aria-hidden="true">
    <?php if ($bg_img): ?>
      <div class="hero-bg-image">
        <img src="<?php echo esc_url($bg_img); ?>" alt="" role="presentation" loading="eager" fetchpriority="high" data-parallax="0.08" />
      </div>
    <?php else: ?>
      <div class="hero-bg-gradient"></div>
    <?php endif; ?>
    <div class="hero-bg-overlay"></div>
  </div>

  <!-- FROM word — top-left, close to middle line but above it -->
  <div class="hero-from-wrap" aria-hidden="true">
    <span class="hcw-from" id="hero-from"></span>
  </div>

  <!-- TO word — large, absolutely centered slightly below middle -->
  <div class="hero-to-wrap" aria-live="polite" aria-atomic="true">
    <span class="hcw-to" id="hero-to"></span>
  </div>

</section>

<script type="application/json" id="sk-hero-data"><?php echo wp_json_encode(['pairs' => array_values($transform_pairs)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<?php /* ══ SACRED DIVIDER — removed per v7 fix ══
       Hero divider/lotus/mist removed — clean transition via CSS.
       (Commented-out markup removed to avoid nested-comment rendering bug
        where inner --> tokens closed the outer comment prematurely.) */ ?>




