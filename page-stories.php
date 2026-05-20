<?php
/**
 * Template Name: Stories Archive
 *
 * Interactive magazine/newspaper-style stories page.
 * Hero story spans full width; remaining stories in responsive grid.
 * JS category filter — no page reload.
 */

get_header();

/* ── Order: featured stories first, then by date ── */
$all_stories_query = new WP_Query( [
    'post_type'      => 'sk_story',
    'post_status'    => 'publish',
    'posts_per_page' => 50,
    'orderby'        => [ 'date' => 'DESC' ],
    'no_found_rows'  => true,
] );

$all_stories = [];
$categories  = [];

if ( $all_stories_query->have_posts() ) {
    while ( $all_stories_query->have_posts() ) {
        $all_stories_query->the_post();
        $id  = get_the_ID();
        $cat = get_post_meta( $id, 'story_category', true );
        if ( $cat && ! in_array( $cat, $categories, true ) ) {
            $categories[] = $cat;
        }
        $all_stories[] = [
            'id'           => $id,
            'title'        => get_the_title(),
            'pull_quote'   => get_post_meta( $id, 'story_pull_quote',      true ),
            'excerpt'      => wp_trim_words( get_the_excerpt() ?: strip_tags( get_the_content() ), 30, '…' ),
            'category'     => $cat,
            'author_name'  => get_post_meta( $id, 'story_author_name',  true ) ?: get_the_author(),
            'author_title' => get_post_meta( $id, 'story_author_title', true ),
            'cover'        => get_post_meta( $id, 'story_cover_image_url', true ) ?: ( has_post_thumbnail() ? get_the_post_thumbnail_url( $id, 'large' ) : '' ),
            'read_time'    => get_post_meta( $id, 'story_read_time',    true ),
            'date'         => get_the_date( 'M Y' ),
            'url'          => get_permalink(),
            'featured'     => (bool) get_post_meta( $id, 'story_featured', true ),
        ];
    }
    wp_reset_postdata();
}

$hero    = ! empty( $all_stories ) ? $all_stories[0] : null;
$rest    = array_slice( $all_stories, 1 );
$has_stories = ! empty( $all_stories );
?>

<main class="sk-stories-page" id="stories-archive">

  <!-- Page masthead -->
  <header class="sk-stories-masthead">
    <div class="wrap">
      <div class="sk-stories-masthead__inner">
        <div class="sk-stories-masthead__left">
          <p class="eyebrow">Sacred Kompass</p>
          <h1 class="sk-stories-masthead__title">Stories</h1>
        </div>
        <div class="sk-stories-masthead__right">
          <p class="sk-stories-masthead__sub">Wisdom from lived experience.<br>Medicine for the journey ahead.</p>
        </div>
      </div>
      <div class="sk-stories-masthead__rule" aria-hidden="true">
        <span class="sk-stories-masthead__rule-line"></span>
        <span class="sk-stories-masthead__rule-ornament">✦</span>
        <span class="sk-stories-masthead__rule-line"></span>
      </div>
    </div>
  </header>

  <?php if ( ! $has_stories ) : ?>
  <!-- Empty state -->
  <div class="wrap sk-stories-empty">
    <p>Stories are being gathered. Return soon.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Back to Home</a>
  </div>

  <?php else : ?>

  <!-- Category filter bar -->
  <?php if ( ! empty( $categories ) ) : ?>
  <div class="sk-stories-filter-wrap">
    <div class="wrap">
      <nav class="sk-stories-filter" aria-label="Filter stories by category">
        <button class="sk-filter-btn active" data-filter="all">All Stories</button>
        <?php foreach ( $categories as $cat ) : ?>
        <button class="sk-filter-btn" data-filter="<?php echo esc_attr( sanitize_title( $cat ) ); ?>"><?php echo esc_html( $cat ); ?></button>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>
  <?php endif; ?>

  <div class="wrap sk-stories-body" id="sk-stories-body">

    <!-- Hero story — full width feature -->
    <?php if ( $hero ) : ?>
    <article class="sk-story-hero" data-category="<?php echo esc_attr( sanitize_title( $hero['category'] ) ); ?>" data-story-item>
      <?php if ( $hero['cover'] ) : ?>
      <a href="<?php echo esc_url( $hero['url'] ); ?>" class="sk-story-hero__img-link">
        <div class="sk-story-hero__img">
          <img src="<?php echo esc_url( $hero['cover'] ); ?>" alt="" loading="eager" />
          <div class="sk-story-hero__img-overlay"></div>
        </div>
      </a>
      <?php endif; ?>
      <div class="sk-story-hero__content">
        <div class="sk-story-hero__meta">
          <?php if ( $hero['category'] ) : ?>
          <span class="sk-story-cat"><?php echo esc_html( $hero['category'] ); ?></span>
          <?php endif; ?>
          <?php if ( $hero['read_time'] ) : ?>
          <span class="sk-story-readtime"><?php echo esc_html( $hero['read_time'] ); ?></span>
          <?php endif; ?>
        </div>
        <h2 class="sk-story-hero__title">
          <a href="<?php echo esc_url( $hero['url'] ); ?>"><?php echo esc_html( $hero['title'] ); ?></a>
        </h2>
        <?php $hero_text = $hero['pull_quote'] ?: $hero['excerpt']; ?>
        <?php if ( $hero_text ) : ?>
        <p class="sk-story-hero__excerpt"><?php echo esc_html( $hero_text ); ?></p>
        <?php endif; ?>
        <div class="sk-story-hero__byline">
          <span class="sk-story-author"><?php echo esc_html( $hero['author_name'] ); ?></span>
          <?php if ( $hero['author_title'] ) : ?>
          <span class="sk-story-author-title"><?php echo esc_html( $hero['author_title'] ); ?></span>
          <?php endif; ?>
          <span class="sk-story-date"><?php echo esc_html( $hero['date'] ); ?></span>
        </div>
        <a href="<?php echo esc_url( $hero['url'] ); ?>" class="sk-story-read-link">
          Read the story
          <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </article>
    <?php endif; ?>

    <!-- Divider -->
    <?php if ( ! empty( $rest ) ) : ?>
    <div class="sk-stories-divider" aria-hidden="true">
      <span class="sk-stories-divider__line"></span>
      <span class="sk-stories-divider__text">More Stories</span>
      <span class="sk-stories-divider__line"></span>
    </div>

    <!-- Story grid -->
    <div class="sk-stories-grid" id="sk-stories-grid">
      <?php foreach ( $rest as $s ) : ?>
      <article class="sk-story-card" data-category="<?php echo esc_attr( sanitize_title( $s['category'] ) ); ?>" data-story-item>
        <?php if ( $s['cover'] ) : ?>
        <a href="<?php echo esc_url( $s['url'] ); ?>" class="sk-story-card__img-link" tabindex="-1">
          <div class="sk-story-card__img">
            <img src="<?php echo esc_url( $s['cover'] ); ?>" alt="" loading="lazy" />
          </div>
        </a>
        <?php endif; ?>
        <div class="sk-story-card__body">
          <div class="sk-story-card__meta">
            <?php if ( $s['category'] ) : ?>
            <span class="sk-story-cat"><?php echo esc_html( $s['category'] ); ?></span>
            <?php endif; ?>
            <?php if ( $s['read_time'] ) : ?>
            <span class="sk-story-readtime"><?php echo esc_html( $s['read_time'] ); ?></span>
            <?php endif; ?>
          </div>
          <h3 class="sk-story-card__title">
            <a href="<?php echo esc_url( $s['url'] ); ?>"><?php echo esc_html( $s['title'] ); ?></a>
          </h3>
          <?php $card_text = $s['pull_quote'] ?: $s['excerpt']; ?>
          <?php if ( $card_text ) : ?>
          <p class="sk-story-card__excerpt"><?php echo esc_html( $card_text ); ?></p>
          <?php endif; ?>
          <div class="sk-story-card__footer">
            <div class="sk-story-card__byline">
              <span class="sk-story-author"><?php echo esc_html( $s['author_name'] ); ?></span>
              <span class="sk-story-date"><?php echo esc_html( $s['date'] ); ?></span>
            </div>
            <a href="<?php echo esc_url( $s['url'] ); ?>" class="sk-story-read-link sk-story-read-link--sm">
              Read
              <svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div><!-- /sk-stories-grid -->
    <?php endif; ?>

    <!-- No results (filter) -->
    <div class="sk-stories-no-results" id="sk-stories-no-results" style="display:none">
      <p>No stories in this category yet.</p>
    </div>

  </div><!-- /wrap -->
  <?php endif; ?>

</main>

<?php get_footer(); ?>

<script>
(function () {
  'use strict';

  var filterBtns = document.querySelectorAll('.sk-filter-btn');
  var items      = document.querySelectorAll('[data-story-item]');
  var noResults  = document.getElementById('sk-stories-no-results');
  var heroEl     = document.querySelector('.sk-story-hero');

  if (!filterBtns.length) return;

  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var filter = this.getAttribute('data-filter');

      // Update active button
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      this.classList.add('active');

      var visible = 0;

      items.forEach(function (item) {
        var cat = item.getAttribute('data-category');
        var show = filter === 'all' || cat === filter;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    });
  });
})();
</script>
