<?php
/**
 * Stories Preview — Homepage Section
 *
 * Reads from the sk_story CPT (featured stories).
 * Background image is editable via Sacred Kompass > Settings > Stories Preview.
 * Auto-hides when no published sk_story posts are marked as featured.
 */

/* ── Section copy — editable via Settings ── */
$sp_eyebrow    = sk_option( 'stories_preview_eyebrow', 'Client Stories' );
$sp_heading    = sk_option( 'stories_preview_heading', 'Real Journeys,' );
$sp_heading_em = sk_option( 'stories_preview_heading_em', 'Real Change' );
$sp_sub        = sk_option( 'stories_preview_sub', 'Stories of transformation, written by those who walked the path.' );
$sp_see_all    = sk_option( 'stories_preview_see_all', 'Read all stories' );
$sp_bg_image   = sk_option( 'stories_preview_bg_image', '' );

/* ── Query featured sk_story posts ── */
$story_query = new WP_Query( [
    'post_type'           => 'sk_story',
    'post_status'         => 'publish',
    'posts_per_page'      => 4,
    'meta_query'          => [ [
        'key'   => 'story_featured',
        'value' => '1',
    ] ],
    'orderby'             => 'menu_order',
    'order'               => 'ASC',
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
] );

/* Fall back to latest 4 published stories if no featured ones ── */
if ( ! $story_query->have_posts() ) {
    wp_reset_postdata();
    $story_query = new WP_Query( [
        'post_type'      => 'sk_story',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
        'ignore_sticky_posts' => true,
    ] );
}

if ( ! $story_query->have_posts() ) {
    wp_reset_postdata();
    return; // Nothing to show — hide section silently.
}

$stories = [];
while ( $story_query->have_posts() ) {
    $story_query->the_post();
    $id = get_the_ID();

    $cover_id  = (int) get_post_meta( $id, 'story_cover_image_id', true );
    $cover_url = $cover_id
        ? wp_get_attachment_image_url( $cover_id, 'medium_large' )
        : get_post_meta( $id, 'story_cover_image_url', true );
    // Final fallback: featured image
    if ( ! $cover_url && has_post_thumbnail( $id ) ) {
        $cover_url = get_the_post_thumbnail_url( $id, 'medium_large' );
    }

    $stories[] = [
        'id'          => $id,
        'permalink'   => get_permalink(),
        'title'       => get_the_title(),
        'pull_quote'  => get_post_meta( $id, 'story_pull_quote',   true ),
        'excerpt'     => wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 20, '…' ),
        'category'    => get_post_meta( $id, 'story_category',     true ),
        'author_name' => get_post_meta( $id, 'story_author_name',  true ) ?: get_the_author(),
        'read_time'   => get_post_meta( $id, 'story_read_time',    true ),
        'cover'       => $cover_url,
        'date'        => get_the_date( 'M j, Y' ),
    ];
}
wp_reset_postdata();
?>

<section
  class="sk-stories-preview-section"
  id="stories-preview"
  aria-labelledby="sp-heading"
  <?php if ( $sp_bg_image ) : ?>
  style="--stories-bg: url('<?php echo esc_url( $sp_bg_image ); ?>'); --sp-bg: url('<?php echo esc_url( $sp_bg_image ); ?>');"
  <?php endif; ?>
>
  <div class="sk-sp-bg-overlay" aria-hidden="true"></div>

  <div class="wrap sk-sp-inner">

    <!-- Section header -->
    <header class="sk-sp-header reveal">
      <p class="eyebrow eyebrow-c"><?php echo esc_html( $sp_eyebrow ); ?></p>
      <h2 class="display-h2 sk-sp-heading" id="sp-heading">
        <?php echo esc_html( $sp_heading ); ?>
        <em><?php echo esc_html( $sp_heading_em ); ?></em>
      </h2>
      <?php if ( $sp_sub ) : ?>
      <p class="sk-sp-sub"><?php echo esc_html( $sp_sub ); ?></p>
      <?php endif; ?>
    </header>

    <!-- Story cards grid -->
    <div class="sk-sp-grid" role="list">
      <?php foreach ( $stories as $i => $s ) : ?>
      <article
        class="sk-sp-card reveal d<?php echo min( $i + 1, 4 ); ?>"
        role="listitem"
      >
        <a href="<?php echo esc_url( $s['permalink'] ); ?>" class="sk-sp-card-inner" aria-label="<?php echo esc_attr( $s['title'] ); ?>">

          <!-- Cover image -->
          <div class="sk-sp-card-img">
            <?php if ( $s['cover'] ) : ?>
            <img
              src="<?php echo esc_url( $s['cover'] ); ?>"
              alt=""
              loading="lazy"
              width="480"
              height="300"
            />
            <?php else : ?>
            <div class="sk-sp-card-img-placeholder" aria-hidden="true">
              <span><?php echo esc_html( mb_substr( $s['title'], 0, 1 ) ); ?></span>
            </div>
            <?php endif; ?>
            <?php if ( $s['category'] ) : ?>
            <span class="sk-sp-cat"><?php echo esc_html( $s['category'] ); ?></span>
            <?php endif; ?>
          </div>

          <!-- Card body -->
          <div class="sk-sp-card-body">
            <h3 class="sk-sp-card-title"><?php echo esc_html( $s['title'] ); ?></h3>
            <?php $card_text = $s['pull_quote'] ?: $s['excerpt']; ?>
            <?php if ( $card_text ) : ?>
            <p class="sk-sp-card-excerpt"><?php echo esc_html( $card_text ); ?></p>
            <?php endif; ?>
            <footer class="sk-sp-card-meta">
              <?php if ( $s['author_name'] ) : ?>
              <span class="sk-sp-card-author"><?php echo esc_html( $s['author_name'] ); ?></span>
              <?php endif; ?>
              <?php if ( $s['read_time'] ) : ?>
              <span class="sk-sp-card-readtime"><?php echo esc_html( $s['read_time'] ); ?></span>
              <?php endif; ?>
            </footer>
          </div>

        </a>
      </article>
      <?php endforeach; ?>
    </div><!-- /grid -->

    <!-- See all link -->
    <div class="sk-sp-see-all reveal">
      <a href="<?php echo esc_url( home_url( '/stories/' ) ); ?>" class="btn btn-ghost">
        <?php echo esc_html( $sp_see_all ); ?>
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>

  </div><!-- /inner -->
</section>
