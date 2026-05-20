<?php
/**
 * Offerings / Pathways — Retro Carousel v10.0
 *
 * Faithful PHP port of the retro-testimonial React component.
 * Horizontal scrolling carousel of parchment cards.
 * Click to expand any card into a full-screen detail view (AnimatePresence equivalent).
 * Arrow navigation + drag/swipe on touch.
 * Warm ivory/parchment palette — fully on-brand.
 */

$offerings_eyebrow    = get_option('options_sk_offerings_eyebrow',    'What We Offer');
$offerings_heading    = get_option('options_sk_offerings_heading',    'Pathways of');
$offerings_heading_em = get_option('options_sk_offerings_heading_em', 'Guidance');
$offerings_sub        = get_option('options_sk_offerings_sub',        'Each pathway is an invitation, not a prescription. We meet you exactly where you are, and walk with you from there.');
$offerings_cta_url    = get_option('options_sk_offerings_cta_url',    home_url('/#contact'));

$offerings_query = new WP_Query([
    'post_type'              => 'sk_offering',
    'post_status'            => 'publish',
    'posts_per_page'         => 20,
    'orderby'                => 'menu_order',
    'order'                  => 'ASC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
]);

$offerings_data = [];
$idx = 0;
if ($offerings_query->have_posts()) :
    while ($offerings_query->have_posts()) : $offerings_query->the_post();
        $pid = get_the_ID();
        $offerings_data[] = [
            'index' => $idx,
            'num'   => str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
            'title' => get_the_title(),
            'tag'   => get_post_meta($pid, 'offering_tag',   true),
            'desc'  => get_post_meta($pid, 'offering_desc',  true),
            'price' => get_post_meta($pid, 'offering_price', true),
            'img'          => (function($pid) {
                                $att_id = (int) get_post_meta($pid, 'offering_image_id', true);
                                if ($att_id) return wp_get_attachment_image_url($att_id, 'large') ?: '';
                                return get_the_post_thumbnail_url($pid, 'large') ?: '';
                              })($pid),
            'duration'     => get_post_meta($pid, 'offering_duration',     true) ?: '',
            'format'       => get_post_meta($pid, 'offering_format',       true) ?: '',
            'capacity'     => get_post_meta($pid, 'offering_capacity',     true) ?: '',
            'availability' => get_post_meta($pid, 'offering_availability', true) ?: '',
            'cta_url'      => get_post_meta($pid, 'offering_cta_url',      true) ?: '',
            'slug'  => get_post_meta($pid, 'offering_form_slug', true) ?: '',
        ];
        $idx++;
    endwhile;
    wp_reset_postdata();
endif;

if (empty($offerings_data)) {
    $placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='600' viewBox='0 0 800 600'%3E%3Crect width='100%25' height='100%25' fill='%23EDE3D2'/%3E%3C/svg%3E";
    $offerings_data = [
        ['index'=>0,'num'=>'01','title'=>'Meditation & Mindfulness',              'tag'=>'Personal',    'price'=>'', 'slug'=>'meditation', 'desc'=>'Tailored practices for stress reduction, focus, and emotional balance — meeting you wherever you are on your inner journey.',            'img'=>$placeholder],
        ['index'=>1,'num'=>'02','title'=>'Compassionate Communication',           'tag'=>'Relational',  'price'=>'', 'slug'=>'nvc',        'desc'=>'Nonviolent Communication tools to foster empathy, resolve conflicts, and build stronger, more authentic relationships.',                  'img'=>$placeholder],
        ['index'=>2,'num'=>'03','title'=>'Astrology & Strategic Insight',         'tag'=>'Guidance',    'price'=>'', 'slug'=>'astrology',  'desc'=>'Vedic Jyotish astrology as a living guidance system — clarity on aligned decision-making, timing, and sacred cycles.',                  'img'=>$placeholder],
        ['index'=>3,'num'=>'04','title'=>"Women's Wellness & Empowerment",        'tag'=>'Empowerment', 'price'=>'', 'slug'=>'womens',     'desc'=>"Programmes supporting women in reclaiming their sacred power, well-being, and intuitive wisdom through the sacred feminine.",            'img'=>$placeholder],
        ['index'=>4,'num'=>'05','title'=>'Leadership & Organisational Alignment', 'tag'=>'Corporate',   'price'=>'', 'slug'=>'corporate',  'desc'=>'Workshops to integrate conscious leadership, emotional resilience, and holistic growth — culture built from the inside out.',           'img'=>$placeholder],
    ];
}

// Background texture — subtle warmth behind card images (matches component's backgroundImage)
$bg_texture = get_option('options_sk_offerings_bg_texture', '');
?>

<section class="sk-rc-section" id="offerings" aria-labelledby="offerings-heading">

  <!-- Section header -->
  <div class="wrap sk-rc-header">
    <div class="eyebrow eyebrow-c reveal"><?php echo esc_html($offerings_eyebrow); ?></div>
    <h2 class="display-h2 reveal d1" id="offerings-heading">
      <?php echo esc_html($offerings_heading); ?> <em><?php echo esc_html($offerings_heading_em); ?></em>
    </h2>
    <p class="body-serif reveal d2"><?php echo esc_html($offerings_sub); ?></p>
  </div>

  <!-- Carousel wrapper -->
  <div class="sk-rc-carousel-outer reveal d3">

    <!-- Scrollable track -->
    <div class="sk-rc-track" id="sk-rc-track" role="list">
      <?php foreach ($offerings_data as $i => $o) :
        $short_desc = mb_strlen($o['desc']) > 110
          ? mb_substr($o['desc'], 0, 110) . '…'
          : $o['desc'];
        $short_tag  = mb_strlen($o['tag'])  > 28
          ? mb_substr($o['tag'],  0, 28) . '…'
          : $o['tag'];
      ?>
      <div
        class="sk-rc-card-wrap"
        role="listitem"
      >
        <!-- The card button (opens expanded view) -->
        <button
          class="sk-rc-card" data-magnetic
          data-index="<?php echo $i; ?>"
          aria-label="<?php echo esc_attr('Open ' . $o['title']); ?>"
          aria-haspopup="dialog"
        >
          <!-- Background texture (opacity 30%) -->
          <div class="sk-rc-card-texture" aria-hidden="true"<?php if (empty($o['img']) && !empty($bg_texture)) echo ' style="background-image: url(\'' . esc_url($bg_texture) . '\'); background-size: cover; background-position: center;"'; ?>>
            <?php if (!empty($o['img'])): ?>
            <img src="<?php echo esc_url($o['img']); ?>" alt="" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>" />
            <?php endif; ?>
          </div>

          <!-- Profile / icon area -->
          <div class="sk-rc-card-avatar" aria-hidden="true">
            <span class="sk-rc-card-num"><?php echo esc_html($o['num']); ?></span>
          </div>

          <!-- Short description -->
          <p class="sk-rc-card-desc"><?php echo esc_html($short_desc); ?></p>

          <!-- Name (title) -->
          <p class="sk-rc-card-name"><?php echo esc_html($o['title']); ?></p>

          <!-- Designation (tag) -->
          <p class="sk-rc-card-tag"><?php echo esc_html($short_tag); ?></p>
          <?php if (!empty($o['price'])): ?>
          <!-- Price -->
          <p class="sk-rc-card-price"><?php echo esc_html($o['price']); ?></p>
          <?php endif; ?>
        </button>
      </div>
      <?php endforeach; ?>
    </div><!-- /track -->



  </div><!-- /carousel-outer -->

</section>

<!-- ── Expanded card overlay (AnimatePresence equivalent) ── -->
<div class="sk-rc-overlay" id="sk-rc-overlay" role="dialog" aria-modal="true" aria-label="Pathway detail" hidden>
  <div class="sk-rc-overlay-backdrop" id="sk-rc-backdrop"></div>
  <div class="sk-rc-overlay-box" id="sk-rc-box">
    <!-- Close -->
    <button class="sk-rc-overlay-close" id="sk-rc-close" aria-label="Close">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <!-- Dynamic content injected by JS -->
    <div id="sk-rc-overlay-content"></div>
  </div>
</div>

<script type="application/json" id="sk-offerings-data"><?php echo wp_json_encode(array_values($offerings_data), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
