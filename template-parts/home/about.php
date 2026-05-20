<?php
/**
 * About Section — v16
 *
 * Layout matches the wireframe sketch:
 *  LEFT  — brand name (SACRED KOMPASS) + tagline "Exploring Your Inner Journey"
 *  RIGHT — three stacked content rows:
 *            ④ Organisation descriptor
 *            ⑤ Bridge copy (main body)
 *            ⑥ Welcome line
 *
 * All text is admin-controlled via Settings › About.
 * No fallback strings — empty field = element not rendered.
 */

$bg_img      = sk_option('hero_bg_image', '');
$eyebrow     = sk_option('about_eyebrow', '');
$tagline     = sk_option('about_tagline', '');
$org_desc    = sk_option('about_org_descriptor', '');
$bridge      = sk_option('about_bridge_copy', '');
$welcome     = sk_option('about_welcome_strip', '');
$heading     = sk_option('about_heading', '');
$body        = sk_option('about_body', '');
?>

<section class="sk-about" id="about"
         aria-labelledby="about-heading"
         itemscope itemtype="https://schema.org/Organization">

  <meta itemprop="name" content="Sacred Kompass Collective" />

  <div class="sk-about__inner wrap">

    <!-- LEFT: Brand name + tagline -->
    <div class="sk-about__brand-col reveal d1" itemprop="legalName">

      <div class="sk-about__brand"
           <?php if ( $bg_img ) : ?>style="--about-bg: url('<?php echo esc_url( $bg_img ); ?>'); --hero-img: url('<?php echo esc_url( $bg_img ); ?>');"<?php endif; ?>>
        <span class="sk-about__brand-line sk-about__brand-line--sacred">SACRED</span>
        <span class="sk-about__brand-line sk-about__brand-line--kompass">KOMPASS</span>
      </div>

      <?php if ( $tagline ) : ?>
      <p class="sk-about__tagline">
        <span class="sk-about__tagline-text" id="sk-about-tagline" data-split="wave"><?php echo esc_html( $tagline ); ?></span>
      </p>
      <?php endif; ?>

    </div>

    <!-- RIGHT: Three stacked rows -->
    <div class="sk-about__content">

      <?php if ( $eyebrow ) : ?>
      <div class="sk-about__row reveal d2">
        <div class="eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
      </div>
      <?php endif; ?>

      <?php if ( $org_desc ) : ?>
      <div class="sk-about__row reveal d2">
        <p class="sk-about__org" itemprop="description"><?php echo esc_html( $org_desc ); ?></p>
      </div>
      <?php endif; ?>

      <?php if ( $heading || $bridge || $body ) : ?>
      <div class="sk-about__row reveal d3">
        <?php if ( $heading ) : ?>
        <h2 class="sk-about__heading" id="about-heading"><?php echo esc_html( $heading ); ?></h2>
        <?php endif; ?>
        <?php if ( $bridge ) : ?>
        <p class="sk-about__bridge"><?php echo esc_html( $bridge ); ?></p>
        <?php endif; ?>
        <?php if ( $body ) : ?>
        <p class="sk-about__body"><?php echo esc_html( $body ); ?></p>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php if ( $welcome ) : ?>
      <div class="sk-about__row reveal d4">
        <?php
        // Auto-link sacredkompass.org if present in the welcome text
        $domain = parse_url(home_url(), PHP_URL_HOST);
        $welcome_linked = preg_replace(
          '/\b' . preg_quote($domain, '/') . '\b/i',
          '<a href="' . esc_url(home_url('/')) . '" class="sk-about__site-link" target="_blank" rel="noopener noreferrer">' . esc_html($domain) . '</a>',
          esc_html( $welcome )
        );
        ?>
        <p class="sk-about__welcome"><?php echo $welcome_linked; ?></p>
      </div>
      <?php endif; ?>

    </div>

  </div>

</section>
