<?php
/**
 * Single Story — immersive editorial reading template
 */
get_header();

if ( have_posts() ) :
  while ( have_posts() ) :
    the_post();
    $id           = get_the_ID();
    $pull_quote   = get_post_meta( $id, 'story_pull_quote',      true );
    $category     = get_post_meta( $id, 'story_category',        true );
    $author_name  = get_post_meta( $id, 'story_author_name',     true ) ?: get_the_author();
    $author_title = get_post_meta( $id, 'story_author_title',    true );
    $cover        = get_post_meta( $id, 'story_cover_image_url', true ) ?: ( has_post_thumbnail() ? get_the_post_thumbnail_url( $id, 'full' ) : '' );
    $read_time    = get_post_meta( $id, 'story_read_time',       true );
?>
<article class="sk-story-single" id="story-<?php echo $id; ?>">

  <!-- Hero cover -->
  <?php if ( $cover ) : ?>
  <div class="sk-story-single__cover">
    <img src="<?php echo esc_url( $cover ); ?>" alt="" loading="eager" />
    <div class="sk-story-single__cover-overlay"></div>
  </div>
  <?php endif; ?>

  <!-- Story header -->
  <header class="sk-story-single__header">
    <div class="wrap-narrow">
      <div class="sk-story-single__header-meta">
        <?php if ( $category ) : ?>
        <a href="<?php echo esc_url( home_url( '/stories/' ) ); ?>" class="sk-story-cat"><?php echo esc_html( $category ); ?></a>
        <?php endif; ?>
        <?php if ( $read_time ) : ?>
        <span class="sk-story-readtime"><?php echo esc_html( $read_time ); ?></span>
        <?php endif; ?>
      </div>
      <h1 class="sk-story-single__title"><?php the_title(); ?></h1>
      <?php if ( $pull_quote ) : ?>
      <p class="sk-story-single__pullquote"><?php echo esc_html( $pull_quote ); ?></p>
      <?php endif; ?>
      <div class="sk-story-single__byline">
        <span class="sk-story-author"><?php echo esc_html( $author_name ); ?></span>
        <?php if ( $author_title ) : ?>
        <span class="sk-story-author-title"><?php echo esc_html( $author_title ); ?></span>
        <?php endif; ?>
        <span class="sk-story-date"><?php echo get_the_date( 'F j, Y' ); ?></span>
      </div>
    </div>
  </header>

  <!-- Body — Gutenberg content -->
  <div class="sk-story-single__body">
    <div class="wrap-narrow sk-story-single__content">
      <?php the_content(); ?>
    </div>
  </div>

  <!-- Back link -->
  <div class="wrap-narrow sk-story-single__back">
    <a href="<?php echo esc_url( home_url( '/stories/' ) ); ?>" class="sk-story-back-link">
      <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      All Stories
    </a>
  </div>

</article>
<?php
  endwhile;
endif;
get_footer();
?>
