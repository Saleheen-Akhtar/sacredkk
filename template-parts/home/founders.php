<?php
/**
 * The Collective — Homepage Preview v2.0
 *
 * Left: large team card linking to /collective/
 * Right: editorial copy — clean, no awkward inline widths.
 * SEO: structured copy with eyebrow / heading / two punchy paras / name chips / CTA.
 */

$team_card = [
  'label'    => sk_option('founders_team_title',    __('The Collective','sacred-kompass')),
  'subtitle' => sk_option('founders_team_subtitle', __('Sacred Kompass Collective','sacred-kompass')),
  'image'    => sk_option('founders_team_image',''),
];

$section_eyebrow    = sk_option('founders_eyebrow',    'Our People');
$section_heading    = sk_option('founders_heading',    'The Guides Behind');
$section_heading_em = sk_option('founders_heading_em', 'Sacred Kompass');
$section_sub        = sk_option('founders_sub',        'Two souls, one vision. Uniting Eastern wisdom and Western heart in service of conscious living.');
$founders_hover     = sk_option('founders_hover_hint', 'Meet the Collective');
$founders_cta_label = sk_option('founders_cta_label',  'Explore the Collective');

$collective_url = home_url('/collective/');
// Resolve actual WP page permalink (handles custom slugs like 'the-collective')
$_collective_page = get_page_by_path('collective') ?: get_page_by_path('the-collective');
if ($_collective_page) {
  $collective_url = get_permalink($_collective_page);
}
?>

<section class="founders-section" id="collective" aria-labelledby="collective-heading">
  <div class="wrap">
    <div class="founders-asymgrid founders-asymgrid--collective">

      <!-- LEFT: large team card -->
      <a class="founder-card founder-card--primary reveal"
         href="<?php echo esc_url($collective_url); ?>"
         aria-label="<?php echo esc_attr($founders_hover); ?>"><?php /* hover hint: founders_hover_hint */ ?>

        <?php if (!empty($team_card['image'])): ?>
          <div class="founder-card-image">
            <img src="<?php echo esc_url($team_card['image']); ?>"
                 alt="<?php esc_attr_e('Sacred Kompass Collective','sacred-kompass'); ?>"
                 loading="lazy" />
          </div>
        <?php else: ?>
          <div class="founder-card-placeholder" aria-hidden="true">
            <div class="founder-placeholder-halo"><span class="founder-placeholder-initial">SK</span></div>
          </div>
        <?php endif; ?>

        <div class="founder-card-overlay" aria-hidden="true">
          <div class="founder-overlay-name"><?php echo esc_html($team_card['label']); ?></div>
          <span class="founder-overlay-role"><?php echo esc_html($team_card['subtitle']); ?></span>
        </div>

        <div class="founder-card-name-strip" aria-hidden="true">
          <div class="founder-strip-name"><?php echo esc_html($team_card['label']); ?></div>
          <span class="founder-strip-role"><?php echo esc_html($team_card['subtitle']); ?></span>
        </div>

        <div class="founder-card-hover-hint" aria-hidden="true">
          <span><?php echo esc_html($founders_hover); ?> &#8599;</span>
        </div>

      </a><!-- /founder-card--primary -->

      <!-- RIGHT: editorial copy — clean layout, no hard-coded widths -->
      <div class="founders-editorial-col">

        <div class="eyebrow reveal"><?php echo esc_html($section_eyebrow); ?></div>

        <h2 class="display-h2 reveal d1" id="collective-heading">
          <?php echo esc_html($section_heading); ?><br>
          <em><?php echo esc_html($section_heading_em); ?></em>
        </h2>

        <p class="founders-editorial-lead reveal d2">
          <?php echo esc_html($section_sub); ?>
        </p>

        <p class="founders-editorial-body reveal d3">
          <?php
          $founders_body = sk_option('founders_body', 'From Vedic philosophy and sacred feminine wisdom to conscious leadership and non-violent communication — every guide brings a living practice, not just a credential.');
          echo esc_html($founders_body);
          ?>
        </p>

        <a href="<?php echo esc_url($collective_url); ?>" class="btn btn-primary btn-sm reveal d4">
          <?php echo esc_html($founders_cta_label); ?>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:.3rem"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>

      </div><!-- /founders-editorial-col -->

    </div><!-- /founders-asymgrid -->
  </div><!-- /wrap -->
</section>
