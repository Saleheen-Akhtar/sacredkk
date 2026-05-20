<?php
/**
 * FAQ — reads from sk_faq CPT. Answer stored as post_meta (no ACF needed).
 */
$faq_heading_1  = sk_option('faq_heading_1',   'Frequently');
$faq_heading_em = sk_option('faq_heading_em',  'Asked');
$faq_sub        = sk_option('faq_sub',         'If you have more questions, we warmly invite you to reach out. Every journey begins with a conversation.');
$faq_cta_label  = sk_option('faq_cta_label', '');
$faq_query = new WP_Query([
    'post_type'              => 'sk_faq',
    'post_status'            => 'publish',
    'posts_per_page'         => 30,
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => false,
]);
?>
<section class="faq-section" id="faq" aria-labelledby="faq-heading">
  <div class="wrap"><div class="faq-layout">
    <div class="faq-left reveal">
      <?php /* FAQ: heading-only — eyebrow removed for rhythm variation. The h2 itself carries the section. */ ?>
      <h2 class="display-h2" id="faq-heading">
        <?php echo esc_html($faq_heading_1); ?><br><em><?php echo esc_html($faq_heading_em); ?></em>
      </h2>
      <p class="body-serif"><?php echo esc_html($faq_sub); ?></p>
      <?php if ( $faq_cta_label ) : ?>
      <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="btn btn-outline"><?php echo esc_html($faq_cta_label); ?></a>
      <?php endif; ?>
    </div>
    <div class="faq-list reveal d2" role="list">
      <?php
      $idx          = 0;
      $current_group = null;
      if ($faq_query->have_posts()) :
        while ($faq_query->have_posts()) : $faq_query->the_post();
          $q     = get_the_title();
          $a     = get_post_meta(get_the_ID(), 'faq_answer', true);
          $group = get_post_meta(get_the_ID(), 'faq_group',  true);
          if (!$a) $a = get_the_content(); // fallback
          if (!$q) { $idx++; continue; }
          // Output group heading when group changes
          if ($group && $group !== $current_group) :
            $current_group = $group;
      ?>
      <p class="faq-group-heading"><?php echo esc_html($group); ?></p>
      <?php endif; ?>
      <div class="faq-item" role="listitem">
        <button class="faq-trigger" aria-expanded="false" aria-controls="faq-body-<?php echo $idx; ?>">
          <span class="faq-q"><?php echo esc_html($q); ?></span>
          <span class="faq-toggle" aria-hidden="true"><span></span><span></span></span>
        </button>
        <div class="faq-body" id="faq-body-<?php echo $idx; ?>" role="region">
          <div class="faq-body-inner"><?php echo esc_html($a); ?></div>
        </div>
      </div>
      <?php
          $idx++;
        endwhile;
        wp_reset_postdata();
      endif;
      ?>
    </div>
  </div></div>
</section>
