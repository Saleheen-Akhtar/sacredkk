<?php
/**
 * Template Name: Disclaimer
 * Content is now editable via WP Admin → ★ Sacred Kompass → Legal Pages
 * (post slug: disclaimer)
 *
 * Falls back to hardcoded content if no sk_legal post exists yet.
 */
get_header();

$legal = sk_get_legal_page('disclaimer');

$eyebrow        = $legal['eyebrow']        ?? 'Sacred Kompass Collective';
$title          = $legal['title']          ?: 'Disclaimer';
$effective_date = $legal['effective_date'] ?? '24 March 2026';
$location       = $legal['location']       ?? 'Singapore';
$content        = $legal['content']        ?? '';

if (empty($content)) {
    ob_start(); ?>
  <h2>Wellness &amp; Holistic Services Disclaimer</h2>
  <p>The information, sessions, programmes, and guidance offered by Sacred Kompass Collective — including Vedic Jyotish astrology, meditation, breathwork, energy healing, Nonviolent Communication (NVC), women's wellness, and coaching — are intended for educational, self-development, and personal growth purposes only.</p>

  <h2>Not a Substitute for Professional Advice</h2>
  <p>The services provided by Sacred Kompass Collective are not a substitute for professional medical, psychological, psychiatric, legal, or financial advice. We strongly encourage you to seek appropriate licensed professionals for any medical, mental health, or legal concerns.</p>
  <p>If you are experiencing a medical emergency, a mental health crisis, or thoughts of self-harm, please contact emergency services or a qualified healthcare professional immediately.</p>

  <h2>Astrology &amp; Jyotish</h2>
  <p>Vedic Jyotish astrology is offered as a traditional system of wisdom and self-reflection. Astrological consultations and insights are based on ancient interpretive traditions and are meant to support your own reflection and decision-making — not as predictive guarantees. Individual outcomes may vary. You retain full responsibility for your own choices and actions.</p>

  <h2>Results &amp; Outcomes</h2>
  <p>Personal transformation and wellness results vary significantly from person to person. Sacred Kompass Collective makes no guarantees regarding specific outcomes, improvements, or results arising from engagement with our services, programmes, or content.</p>

  <h2>Third-Party Resources</h2>
  <p>This website may reference or link to third-party resources, books, articles, or practitioners for informational purposes. Such references do not constitute endorsements, and Sacred Kompass Collective is not responsible for the accuracy, content, or practices of any third-party resource.</p>

  <h2>Testimonials</h2>
  <p>Testimonials shared on this website reflect individual experiences and are not representative of all clients. Individual results will differ from person to person.</p>

  <h2>Contact</h2>
  <p>
    <strong>Sacred Kompass Collective</strong><br>
    Email: <a href="mailto:collective@sacredkompass.org">collective@sacredkompass.org</a><br>
    Phone: <a href="tel:+6584343915">+65 84343915</a><br>
    557 Bedok North St. 3, Singapore
  </p>

  <div class="legal-note">
    <p>This Disclaimer may be updated from time to time. Continued use of sacredkompass.org constitutes your acceptance of the current version.</p>
  </div>
<?php
    $content = ob_get_clean();
}
?>

<section class="legal-hero">
  <div class="wrap">
    <div class="eyebrow eyebrow-center"><?php echo esc_html($eyebrow); ?></div>
    <h1><?php echo esc_html($title); ?></h1>
    <p class="legal-meta">
      <?php echo esc_html__('Effective Date:', 'sacred-kompass'); ?> <?php echo esc_html($effective_date); ?>
      &nbsp;·&nbsp; <?php echo esc_html($location); ?>
    </p>
  </div>
</section>

<article class="legal-body">
  <?php echo wp_kses_post($content); ?>
</article>

<?php get_footer(); ?>
