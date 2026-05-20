<?php
/**
 * Template Name: Privacy Policy
 * Content is now editable via WP Admin → ★ Sacred Kompass → Legal Pages
 * (post slug: privacy-policy)
 *
 * Falls back to hardcoded content if no sk_legal post exists yet.
 */
get_header();

$legal = sk_get_legal_page('privacy-policy');

$eyebrow        = $legal['eyebrow']        ?? 'Sacred Kompass Collective';
$title          = $legal['title']          ?: 'Privacy Policy';
$effective_date = $legal['effective_date'] ?? '24 March 2026';
$location       = $legal['location']       ?? 'Singapore';
$content        = $legal['content']        ?? '';

/* ── Fallback hardcoded content if the CPT post hasn't been created yet ── */
if (empty($content)) {
    ob_start(); ?>
  <p>Sacred Kompass Collective ("we", "our", or "us") is committed to protecting your personal data in accordance with the Singapore Personal Data Protection Act 2012 (PDPA). This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you visit sacredkompass.org or engage with our services.</p>

  <h2>1. Data We Collect</h2>
  <p>When you submit our contact form or enquire about our services, we may collect:</p>
  <ul>
    <li>Full name (family name and first name)</li>
    <li>Email address</li>
    <li>Phone number (if provided voluntarily)</li>
    <li>Message content and any other information you choose to share</li>
  </ul>
  <p>We may also automatically collect standard website usage data such as IP address, browser type, and pages visited via analytics tools.</p>

  <h2>2. How We Use Your Data</h2>
  <p>We use the personal data you provide solely to:</p>
  <ul>
    <li>Respond to your enquiries and schedule discovery calls</li>
    <li>Deliver the services you have engaged us for</li>
    <li>Send relevant updates or follow-up communications (with your consent)</li>
    <li>Improve our website and service offerings</li>
  </ul>
  <p>We do not sell, rent, or trade your personal data to any third parties.</p>

  <h2>3. Data Storage</h2>
  <p>Your contact form submissions are processed via Forminator (a WordPress plugin) and may be saved to a secured Google Sheet accessible only to the Sacred Kompass team. Data is retained only as long as necessary to fulfil the purpose for which it was collected, or as required by law.</p>

  <h2>4. Cookies &amp; Analytics</h2>
  <p>Our website may use cookies and third-party analytics tools (such as Google Analytics) to understand how visitors engage with our content. You may disable cookies through your browser settings. By continuing to use the site with cookies enabled, you consent to their use.</p>

  <h2>5. Your Rights Under PDPA</h2>
  <p>Under the Singapore PDPA, you have the right to:</p>
  <ul>
    <li>Request access to the personal data we hold about you</li>
    <li>Request correction of any inaccurate personal data</li>
    <li>Withdraw your consent to our use of your data at any time</li>
  </ul>
  <p>To exercise any of these rights, please contact us at <a href="mailto:collective@sacredkompass.org">collective@sacredkompass.org</a>.</p>

  <h2>6. Third-Party Links</h2>
  <p>Our website may contain links to external websites. We are not responsible for the privacy practices or content of those sites. We encourage you to review their privacy policies independently.</p>

  <h2>7. Contact Us</h2>
  <p>
    <strong>Sacred Kompass Collective</strong><br>
    Email: <a href="mailto:collective@sacredkompass.org">collective@sacredkompass.org</a><br>
    Phone: <a href="tel:+6584343915">+65 84343915</a><br>
    557 Bedok North St. 3, Singapore
  </p>

  <div class="legal-note">
    <p>This Privacy Policy may be updated from time to time. The most current version will always be available at sacredkompass.org/privacy-policy.</p>
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
