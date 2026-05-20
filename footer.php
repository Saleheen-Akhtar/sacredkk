<?php
$tagline         = sk_option('footer_tagline',      'Ancient wisdom for the modern soul.');
$copyright       = sk_option('footer_copyright',    'Sacred Kompass Collective &middot; Singapore');
$email           = sk_option('footer_email',        'collective@sacredkompass.org');
$phone           = sk_option('footer_phone',        '+65 84343915');
$phone_c         = preg_replace('/[^+0-9]/', '', $phone);
$footer_location = sk_option('footer_location_bar', 'Bedok North, Singapore &nbsp;&middot;&nbsp; Online Worldwide');

$footer_col_navigate  = sk_option('footer_col_navigate',  'Navigate');
$footer_col_offerings = sk_option('footer_col_offerings', 'Offerings');
$footer_col_connect   = sk_option('footer_col_connect',   'Connect');
$footer_col_legal     = sk_option('footer_col_legal',     'Legal');

// ── Dynamic nav items — same source as header, already filtered by section state
$footer_nav_items = function_exists('sk_get_nav_items') ? sk_get_nav_items() : [];
if (empty($footer_nav_items)) {
    // Static fallback — filter by section state
    $section_map = function_exists('sk_nav_section_map') ? sk_nav_section_map() : [];
    $all = [
        ['label' => 'About',          'url' => '/#about',            'target' => '_self'],
        ['label' => 'Astrology',      'url' => '/#offerings',        'target' => '_self'],
        ['label' => 'Articles',       'url' => '/journal/',          'target' => '_self'],
        ['label' => 'FAQ',            'url' => '/#faq',              'target' => '_self'],
        ['label' => 'The Collective', 'url' => '/collective/',       'target' => '_self'],
        ['label' => 'Contact',        'url' => '/#contact',          'target' => '_self'],
    ];
    $footer_nav_items = array_values(array_filter($all, function($item) use ($section_map) {
        foreach ($section_map as $anchor => $key) {
            $rel = str_replace(rtrim(home_url(), '/'), '', $item['url']);
            if ($rel === $anchor || $item['url'] === $anchor) {
                return function_exists('sk_section_enabled') ? sk_section_enabled($key) : true;
            }
        }
        return true;
    }));
}

// ── Dynamic offerings (titles + links from sk_offering CPT)
$footer_offerings = get_posts([
    'post_type'      => 'sk_offering',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);
?>
<footer class="footer sk-footer-v2" role="contentinfo">

  <!-- Top: large brand wordmark -->
  <div class="sk-foot-top wrap">
    <div class="sk-foot-brand-row">
      <div class="sk-foot-wordmark-wrap">
        <?php
          $foot_logo = sk_logo_html('sk-foot-logo-img');
          if ($foot_logo) echo $foot_logo;
        ?>
        <span class="sk-foot-wordmark">Sacred <em>Kompass</em></span>
      </div>
    </div>
    <p class="sk-foot-tagline"><?php echo esc_html($tagline); ?></p>
  </div>

  <div class="sk-foot-divider"></div>

  <!-- Mid: nav columns -->
  <div class="sk-foot-mid wrap">

    <div class="sk-foot-col">
      <p class="sk-foot-col-label"><?php echo esc_html($footer_col_navigate); ?></p>
      <ul>
        <?php foreach ($footer_nav_items as $item):
          if (empty($item['desktop'])) continue; // respect desktop visibility flag
          $href   = strpos($item['url'], 'http') === 0 ? esc_url($item['url']) : esc_url(home_url(ltrim($item['url'], '/')));
          $target = ($item['target'] ?? '_self') === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '';
        ?>
        <li><a href="<?php echo $href; ?>"<?php echo $target; ?>><?php echo esc_html($item['label']); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="sk-foot-col">
      <p class="sk-foot-col-label"><?php echo esc_html($footer_col_offerings); ?></p>
      <ul>
        <?php if (!empty($footer_offerings)):
          foreach ($footer_offerings as $offering):
            $cta = get_post_meta($offering->ID, 'offering_cta_url', true) ?: home_url('/#offerings');
        ?>
        <li><a href="<?php echo esc_url($cta); ?>"><?php echo esc_html(get_the_title($offering)); ?></a></li>
        <?php endforeach;
        else: ?>
        <li><a href="<?php echo esc_url(home_url('/#offerings')); ?>"><?php esc_html_e('Meditation &amp; Mindfulness', 'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#offerings')); ?>"><?php esc_html_e('Compassionate Communication', 'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#offerings')); ?>"><?php esc_html_e('Jyotish Astrology', 'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#offerings')); ?>"><?php esc_html_e("Women's Wellness", 'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#offerings')); ?>"><?php esc_html_e('Corporate Programmes', 'sacred-kompass'); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="sk-foot-col">
      <p class="sk-foot-col-label"><?php echo esc_html($footer_col_connect); ?></p>
      <ul>
        <li><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
        <li><a href="tel:<?php echo esc_attr($phone_c); ?>"><?php echo esc_html($phone); ?></a></li>
        <?php
        $ig = sk_option('social_instagram');
        if ($ig) :
          if (!preg_match('#^https?://#i', $ig)) {
            $ig = 'https://www.instagram.com/' . ltrim($ig, '/@');
          }
        ?>
          <li><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Instagram', 'sacred-kompass'); ?></a></li>
        <?php endif; ?>
        <?php
        $fb = sk_option('social_facebook');
        if ($fb) :
          if (!preg_match('#^https?://#i', $fb)) {
            $fb = 'https://www.facebook.com/' . ltrim($fb, '/@');
          }
        ?>
          <li><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Facebook', 'sacred-kompass'); ?></a></li>
        <?php endif; ?>
        <?php
        $wa = sk_option('social_whatsapp');
        if ($wa) :
          if (!preg_match('#^https?://#i', $wa)) {
            $wa = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $wa);
          }
        ?>
          <li><a href="<?php echo esc_url($wa); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('WhatsApp', 'sacred-kompass'); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="sk-foot-col">
      <p class="sk-foot-col-label"><?php echo esc_html($footer_col_legal); ?></p>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/terms'));           ?>"><?php esc_html_e('Terms of Use',   'sacred-kompass'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/disclaimer'));      ?>"><?php esc_html_e('Disclaimer',     'sacred-kompass'); ?></a></li>
      </ul>
    </div>

  </div>

  <div class="sk-foot-divider"></div>

  <!-- Bottom bar -->
  <div class="sk-foot-bottom wrap">
    <span class="sk-foot-copy">
      &copy; <?php echo date('Y'); ?> <?php echo wp_kses($copyright, ['em'=>[],'strong'=>[],'br'=>[],'span'=>[]]); ?>
    </span>
    <span class="sk-foot-location"><?php echo wp_kses($footer_location, ['strong'=>[],'em'=>[],'span'=>[]]); ?></span>
  </div>

</footer>
<?php wp_footer(); ?>

</body>
</html>

