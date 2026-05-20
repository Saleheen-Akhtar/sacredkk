<?php
/**
 * Stirred By Stories Section — homepage teaser
 *
 * Shows a prose introduction paragraph + CTA linking to /stories/
 * Optionally previews up to 3 featured sk_story posts.
 * Background: identical to the about section (lotus PNG + ivory overlay).
 */

$eyebrow    = sk_option( 'values_eyebrow',    '' );
$heading    = sk_option( 'values_heading',    'Stirred By' );
$heading_em = sk_option( 'values_heading_em', 'Stories' );
$cta_label  = sk_option( 'values_cta_label',  'Read All Stories' );
$cta_url    = sk_option( 'values_cta_url',    home_url( '/stories/' ) );

/* ── Query up to 3 featured sk_story posts for preview cards ── */
$story_query = new WP_Query( [
    'post_type'      => 'sk_story',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'meta_key'       => 'story_featured',
    'meta_value'     => '1',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
] );

/* Fallback: latest 3 if none featured */
if ( ! $story_query->have_posts() ) {
    $story_query = new WP_Query( [
        'post_type'      => 'sk_story',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ] );
}

$stories = [];
if ( $story_query->have_posts() ) {
    while ( $story_query->have_posts() ) {
        $story_query->the_post();
        $id = get_the_ID();
        $stories[] = [
            'title'    => get_the_title(),
            'excerpt'  => get_post_meta( $id, 'story_pull_quote', true ) ?: wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 20, '…' ),
            'category' => get_post_meta( $id, 'story_category', true ),
            'author'   => get_post_meta( $id, 'story_author_name', true ) ?: get_the_author(),
            'cover'    => get_post_meta( $id, 'story_cover_image_url', true ) ?: ( has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium' ) : '' ),
            'url'      => get_permalink(),
        ];
    }
    wp_reset_postdata();
}

$has_stories = ! empty( $stories );
?>

<section class="values-section sk-stories-teaser" id="values" aria-labelledby="stories-teaser-heading">
  <div class="wrap">

    <div class="values-header sk-stories-teaser__header">
      <?php if ( $eyebrow ) : ?>
      <div class="eyebrow reveal"><?php echo esc_html( $eyebrow ); ?></div>
      <?php endif; ?>
      <h2 class="display-h2 reveal" id="stories-teaser-heading">
        <?php echo esc_html( $heading ); ?> <em><?php echo esc_html( $heading_em ); ?></em>
      </h2>
    </div>

    <div class="sk-stories-teaser__prose reveal d2">
      <p>Some stories arrive as medicine. They carry wisdom of lived experience, which can awaken courage in another.</p>
      <p>Stories comfort us, guide us through another person's experience. They nourish the soul, with insight and sometimes reveal new perspectives, offering new possible horizons.</p>
    </div>

    <?php if ( $has_stories ) : ?>
    <div class="sk-stories-teaser__grid reveal d3">
      <?php foreach ( $stories as $s ) : ?>
      <a class="sk-story-preview-card" href="<?php echo esc_url( $s['url'] ); ?>">
        <?php if ( $s['cover'] ) : ?>
        <div class="sk-story-preview-card__img">
          <img src="<?php echo esc_url( $s['cover'] ); ?>" alt="" loading="lazy" />
        </div>
        <?php endif; ?>
        <div class="sk-story-preview-card__body">
          <?php if ( $s['category'] ) : ?>
          <span class="sk-story-preview-card__cat"><?php echo esc_html( $s['category'] ); ?></span>
          <?php endif; ?>
          <h3><?php echo esc_html( $s['title'] ); ?></h3>
          <?php if ( $s['excerpt'] ) : ?>
          <p class="sk-story-preview-card__excerpt"><?php echo esc_html( $s['excerpt'] ); ?></p>
          <?php endif; ?>
          <div class="sk-story-preview-card__footer">
            <?php if ( $s['author'] ) : ?>
            <span class="sk-story-preview-card__author"><?php echo esc_html( $s['author'] ); ?></span>
            <?php endif; ?>
            <span class="sk-story-preview-card__arrow" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ( $cta_label ) : ?>
    <div class="sk-stories-teaser__cta reveal <?php echo $has_stories ? 'd4' : 'd3'; ?>">
      <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-primary sk-stories-teaser__btn">
        <?php echo esc_html( $cta_label ); ?>
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
    <?php endif; ?>

  </div>
</section>
