<?php
/**
 * Sacred Kompass v5 — Fallback template.
 * WordPress requires this file. Elementor / front-page.php takes over for home.
 */
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
        the_content();
    endwhile;
endif;
get_footer();
