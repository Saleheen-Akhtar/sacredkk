<?php
/**
 * Quote Band — reads from ACF options page (sk-settings).
 *
 * TYPOGRAPHY v11: Added .display-impact phrase above the blockquote.
 * This creates the second visual crescendo after the hero — a single large-format
 * phrase using the weight-as-emotion technique (ultra-light italic mass vs bold accent).
 * ACF field: quote_impact_phrase (defaults to inline fallback).
 */
$eyebrow        = sk_option('quote_eyebrow',   'Our Vision');
$impact_phrase  = sk_option('quote_impact_phrase', ''); // e.g. "Remember." or "Come home."
$quote          = sk_option('quote_text',      "We envision a world where well-being and performance coexist harmoniously. By reconnecting people to their inner compass, we help them navigate life's complexities with purpose and alignment.");
$highlight = sk_option('quote_highlight', 'inner compass');
$attr      = sk_option('quote_attr',      'Sacred Kompass Collective, Vision Statement');

$rendered = esc_html($quote);
if ($highlight && str_contains($quote, $highlight)) {
    $rendered = str_replace(
        esc_html($highlight),
        '<span class="qa">' . esc_html($highlight) . '</span>',
        esc_html($quote)
    );
}
?>
<div class="quote-band" aria-label="<?php echo esc_attr($eyebrow); ?>">
  <span class="quote-band-large-q" aria-hidden="true">&ldquo;</span>
  <div class="wrap-narrow" style="position:relative;z-index:1;">
    <?php if ($impact_phrase) : ?>
    <p class="display-impact quote-band-impact" aria-hidden="true"><?php echo esc_html($impact_phrase); ?></p>
    <?php endif; ?>
    <div class="eyebrow eyebrow-c eyebrow-light"><?php echo esc_html($eyebrow); ?></div>
    <blockquote class=""><?php echo $rendered; ?></blockquote>
    <?php if ($attr) : ?><p class="quote-by"><?php echo esc_html($attr); ?></p><?php endif; ?>
  </div>
</div>
