<?php
/**
 * Sacred Kompass v5.2 — Generic page template
 *
 * Used by all WP Pages (About, Offerings, Founders, FAQ, Contact,
 * and any new pages you create).
 *
 * ELEMENTOR: the_content() is called unconditionally.
 * This is non-negotiable — Elementor hooks into the_content filter
 * on every page load including the first editor open. Any condition
 * that skips the_content() causes the "content area not found" error.
 *
 * The <main> wrapper is present for non-Elementor pages. Elementor
 * overrides the visual output via its filter and applies its own
 * full-width canvas when active.
 */
get_header();
?>
<main class="sk-page-main" style="padding:10rem 0 6rem;min-height:60vh;">
  <div class="wrap-narrow">
    <?php
    while (have_posts()) {
        the_post();
        the_content(); // Required by Elementor — never remove or wrap.
    }
    ?>
  </div>
</main>
<?php
get_footer();
