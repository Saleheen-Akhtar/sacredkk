<?php
/**
 * Sacred Kompass v10 — Homepage template
 *
 * Single scrollable page. Each section is a separate WP Page
 * (About, Offerings, Founders, FAQ, Contact) included here as
 * one seamless scroll. Anchor links like /#about or /about work.
 *
 * ELEMENTOR: the_content() is called unconditionally — always.
 * This is required so Elementor can intercept it on every load,
 * including the very first time you open the page in the editor.
 * Never wrap this in a condition.
 *
 * HOW THE TWO MODES WORK:
 * - First visit / no Elementor: the_content() returns empty string
 *   (the Home page has no post_content). The theme sections below
 *   render the full page.
 * - After you publish with Elementor: _elementor_edit_mode = 'builder'
 *   is set. The theme sections are hidden; Elementor's output comes
 *   through the_content() filter.
 */
get_header();

// ALWAYS call the_content() — Elementor requires this unconditionally.
if (have_posts()) {
    while (have_posts()) {
        the_post();
        the_content();
    }
}

// Show coded theme sections only when Elementor has NOT taken over.
$home_id           = (int) get_option('page_on_front');
$elementor_active  = $home_id
    && get_post_meta($home_id, '_elementor_edit_mode', true) === 'builder';

if (!$elementor_active) {
    // ── Dynamic section render — controlled via Appearance > Homepage Sections ──
    // Order, visibility, and custom sections are all managed from the admin panel.
    // The helper sk_get_section_render_order() returns the saved key order.

    $builtin     = sk_builtin_sections();   // key → [ label, template ]
    $custom_raw  = get_option( 'sk_custom_sections', '[]' );
    $custom_list = json_decode( $custom_raw, true ) ?: [];
    $custom_map  = [];
    foreach ( $custom_list as $c ) { $custom_map[ $c['id'] ] = $c; }

    $render_order = sk_get_section_render_order();

    foreach ( $render_order as $key ) {
        if ( str_starts_with( $key, 'custom_' ) ) {
            // Custom HTML section
            $cid = substr( $key, 7 );
            if ( isset( $custom_map[ $cid ] ) && ! empty( $custom_map[ $cid ]['enabled'] ) ) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitised on save via wp_kses_post
                echo $custom_map[ $cid ]['content'];
            }
        } else {
            // Built-in section
            if ( ! isset( $builtin[ $key ] ) ) continue;
            $sk_show_val = get_option( 'sk_show_' . $key, null );
            if ( $sk_show_val !== null && $sk_show_val !== false && ! (bool) $sk_show_val ) continue;
            // Admin-only: skip for public visitors
            if ( (bool) get_option( 'sk_admin_only_' . $key, false ) && ! current_user_can( 'edit_posts' ) ) continue;
            get_template_part( $builtin[ $key ]['template'] );
        }
    }
}

get_footer();
