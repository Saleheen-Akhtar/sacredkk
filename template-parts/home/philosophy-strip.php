<?php
/**
 * Philosophy Strip — Faithful port of CircularTestimonials component.
 * 3 stacked images (left/centre/right) with 3D perspective + word-blur animated text.
 */
$pillars = sk_repeater('options_sk_philosophy_pillars_json');
if (empty($pillars)) {
    $pillars = sk_default_pillars();
}

// Assign images to each pillar
$placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='800' viewBox='0 0 800 800'%3E%3Crect width='100%25' height='100%25' fill='%23EDE3D2'/%3E%3C/svg%3E";
$pillar_images = [
    $placeholder,
    $placeholder,
    $placeholder,
    $placeholder,
    $placeholder,
];

$pillars_js = [];
foreach ($pillars as $i => $p) {
    $custom_img = !empty($p['pillar_image']) ? esc_url_raw($p['pillar_image']) : '';
    $pillars_js[] = [
        'num'   => $p['pillar_num']   ?? '0'.($i+1),
        'title' => $p['pillar_title'] ?? '',
        'desc'  => $p['pillar_desc']  ?? '',
        'src'   => $custom_img ?: $pillar_images[$i % count($pillar_images)],
    ];
}
?>
<div class="strip strip--circular" aria-label="<?php esc_attr_e('Core Pillars','sacred-kompass'); ?>" id="sk-philosophy-strip">
  <div class="wrap">
    <div class="philosophy-strip-header reveal">
      <?php /* Number-led interstitial — breaks eyebrow+h2 monotony deliberately */ ?>
      <span class="philosophy-strip-num" aria-hidden="true">&#8212;</span>
      <h2 class="display-h2 reveal d1">
        <?php
        $phil_heading    = sk_option('philosophy_heading',    'How We Work');
        $phil_heading_em = sk_option('philosophy_heading_em', 'With You');
        echo esc_html($phil_heading);
        ?> <em><?php echo esc_html($phil_heading_em); ?></em>
      </h2>
      <p class="philosophy-strip-intro reveal d2"><?php echo esc_html( sk_option('philosophy_intro', 'Every pathway begins with a single question: what is ready to be seen? These are the lenses we bring.') ); ?></p>
    </div>
    <div class="circular-testimonials" id="sk-circular-testimonials">

      <!-- Images container (left) -->
      <div class="ct-images" id="ct-images">
        <?php foreach ($pillars_js as $i => $p) : ?>
        <img
          class="ct-img"
          <?php if ($i === 0) : ?>
          src="<?php echo esc_url($p['src']); ?>"
          <?php else : ?>
          src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1 1'%3E%3C/svg%3E"
          data-src="<?php echo esc_url($p['src']); ?>"
          <?php endif; ?>
          alt="<?php echo esc_attr($p['title']); ?>"
          data-index="<?php echo $i; ?>"
          loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"
          decoding="async"
        />
        <?php endforeach; ?>
      </div>

      <!-- Content (right) -->
      <div class="ct-content">
        <div class="ct-text" id="ct-text">
          <div class="ct-meta">
            <h3 class="ct-name"  id="ct-name"></h3>
            <p  class="ct-desig" id="ct-desig"></p>
          </div>
          <p class="ct-quote" id="ct-quote"></p>
        </div>
        <div class="ct-arrows">
          <button class="ct-btn ct-btn--prev" id="ct-prev" aria-label="<?php esc_attr_e('Previous','sacred-kompass'); ?>">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12 15L7 10L12 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="ct-btn ct-btn--next" id="ct-next" aria-label="<?php esc_attr_e('Next','sacred-kompass'); ?>">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M8 5L13 10L8 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
      </div>

    </div>
  </div>
    </div>
</div>

<script type="application/json" id="sk-philosophy-data"><?php echo wp_json_encode($pillars_js); ?></script>
