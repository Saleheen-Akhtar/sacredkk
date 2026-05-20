<?php
/**
 * Journal Preview — Homepage Section v10
 * Layout: 1 large featured card (left) + 2 stacked cards (right)
 * Editorial magazine grid. Gold/serif aesthetic.
 *
 * POSITION: Footer-adjacent (last section before footer).
 * Intentionally de-emphasised — further reading for committed visitors,
 * not a peer section in the trust/conversion funnel.
 */

$journal_heading  = sk_option('journal_preview_heading',  'From the Journal');
$journal_eyebrow  = sk_option('journal_preview_eyebrow',  'Journal');
$journal_see_all  = sk_option('journal_preview_see_all',  'See all posts');
$journal_url     = home_url('/journal/');

$journal_posts = new WP_Query([
  'post_type'              => 'post',
  'post_status'            => 'publish',
  'posts_per_page'         => 3,
  'orderby'                => 'date',
  'order'                  => 'DESC',
  'no_found_rows'          => true,
  'update_post_meta_cache' => true,
  'update_post_term_cache' => true,
  'ignore_sticky_posts'    => true,
]);

if (!$journal_posts->have_posts()) {
  wp_reset_postdata();
  return;
}

$site_name = get_bloginfo('name');
$logo_url  = function_exists('sk_logo_url') ? sk_logo_url() : get_site_icon_url(512);

$posts = [];
while ($journal_posts->have_posts()):
  $journal_posts->the_post();
  $post_id = get_the_ID();
  $cats    = get_the_category($post_id);
  $posts[] = [
    'id'        => $post_id,
    'permalink' => get_permalink(),
    'title'     => get_the_title(),
    'excerpt'   => get_the_excerpt(),
    'thumb'     => get_the_post_thumbnail_url($post_id, 'large'),
    'thumb_full'=> get_the_post_thumbnail_url($post_id, 'full'),
    'date_raw'  => get_the_date('c'),
    'date_fmt'  => get_the_date('M j, Y'),
    'cat'       => (!empty($cats)) ? $cats[0] : null,
    'read_time' => function_exists('sk_reading_time') ? sk_reading_time($post_id) : '5 min read',
    'initial'   => esc_html(mb_substr(get_the_title(), 0, 1)),
    'modified'  => get_the_modified_date('c', $post_id),
    'author'    => get_the_author_meta('display_name', get_post_field('post_author', $post_id)),
  ];
endwhile;
wp_reset_postdata();

$featured = $posts[0] ?? null;
$side     = array_slice($posts, 1);

// Determine grid layout based on available post count
$post_count     = count($posts);
$grid_modifier  = '';
if ($post_count === 1) $grid_modifier = 'sk-jp-grid--single';
elseif ($post_count === 2) $grid_modifier = 'sk-jp-grid--two';
?>

<section
  class="sk-journal-preview-section"
  id="journal-preview"
  aria-labelledby="jp-heading"
  itemscope
  itemtype="https://schema.org/Blog"
>
  <meta itemprop="name" content="<?php echo esc_attr($site_name); ?> Journal" />
  <meta itemprop="url"  content="<?php echo esc_url($journal_url); ?>" />

  <div class="wrap">

    <!-- ── Masthead ── -->
    <div class="sk-jp-masthead reveal">
      <div>
        <div class="eyebrow"><?php echo esc_html($journal_eyebrow); ?></div>
        <h2 class="display-h2" id="jp-heading" style="margin-top:0.6rem;margin-bottom:0">
          <?php echo esc_html($journal_heading); ?>
        </h2>
      </div>
      <a href="<?php echo esc_url($journal_url); ?>" class="sk-jp-see-all">
        <?php echo esc_html($journal_see_all); ?>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>

    <!-- ── Editorial grid ── -->
    <div class="sk-jp-editorial-grid <?php echo esc_attr($grid_modifier); ?>">

      <?php if ($featured): ?>
      <!-- Large featured card -->
      <article
        class="sk-jp-featured sk-jp-card--animated"
        data-delay="0"
        itemscope itemtype="https://schema.org/BlogPosting"
      >
        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url($featured['permalink']); ?>" />
        <meta itemprop="dateModified"     content="<?php echo esc_attr($featured['modified']); ?>" />
        <?php if ($featured['author']): ?><span itemprop="author" itemscope itemtype="https://schema.org/Person" hidden><meta itemprop="name" content="<?php echo esc_attr($featured['author']); ?>" /></span><?php endif; ?>

        <a href="<?php echo esc_url($featured['permalink']); ?>" class="sk-jp-featured-link" aria-label="<?php echo esc_attr($featured['title']); ?>">
          <?php if ($featured['thumb']): ?>
            <img src="<?php echo esc_url($featured['thumb']); ?>"
                 <?php
                   $feat_thumb_id = get_post_thumbnail_id($featured['id']);
                   $feat_srcset   = $feat_thumb_id ? wp_get_attachment_image_srcset($feat_thumb_id, 'large') : '';
                   if ($feat_srcset) echo 'srcset="' . esc_attr($feat_srcset) . '" ';
                 ?>
                 sizes="(max-width:960px) 100vw, 60vw"
                 alt="<?php echo esc_attr($featured['title']); ?>"
                 loading="eager" fetchpriority="high" decoding="async"
                 class="sk-jp-featured-img"
                 itemprop="image" />
          <?php else: ?>
            <div class="sk-jp-featured-img sk-jp-featured-placeholder"><span><?php echo $featured['initial']; ?></span></div>
          <?php endif; ?>

          <div class="sk-jp-feat-overlay">
            <div class="sk-jp-feat-top">
              <time datetime="<?php echo esc_attr($featured['date_raw']); ?>" class="sk-jp-feat-date" itemprop="datePublished">
                <?php echo esc_html($featured['date_fmt']); ?>
              </time>
              <?php if ($featured['read_time']): ?>
                <span class="sk-jp-feat-readtime"><?php echo esc_html($featured['read_time']); ?></span>
              <?php endif; ?>
              <?php if ($featured['cat']): ?>
                <span class="sk-jp-feat-badge" itemprop="keywords">
                  <span class="sk-jp-badge-dot"></span><?php echo esc_html($featured['cat']->name); ?>
                </span>
              <?php endif; ?>
            </div>

            <div class="sk-jp-feat-bottom">
              <h3 class="sk-jp-feat-title" itemprop="headline"><?php echo esc_html($featured['title']); ?></h3>
              <span class="sk-jp-feat-arrow" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg>
              </span>
            </div>
          </div>
        </a>
      </article>
      <?php endif; ?>

      <!-- Stacked side cards -->
      <?php if (!empty($side)): ?>
      <div class="sk-jp-side-stack">
        <?php foreach ($side as $i => $p): ?>
        <article
          class="sk-jp-side-card sk-jp-card--animated"
          data-delay="<?php echo ($i + 1) * 140; ?>"
          itemscope itemtype="https://schema.org/BlogPosting"
        >
          <meta itemprop="mainEntityOfPage" content="<?php echo esc_url($p['permalink']); ?>" />
          <meta itemprop="dateModified"     content="<?php echo esc_attr($p['modified']); ?>" />
          <?php if ($p['author']): ?><span itemprop="author" itemscope itemtype="https://schema.org/Person" hidden><meta itemprop="name" content="<?php echo esc_attr($p['author']); ?>" /></span><?php endif; ?>

          <a href="<?php echo esc_url($p['permalink']); ?>" class="sk-jp-side-card-link" aria-label="<?php echo esc_attr($p['title']); ?>">
            <?php if ($p['thumb']): ?>
              <div class="sk-jp-side-img-wrap">
                <img src="<?php echo esc_url($p['thumb']); ?>"
                     alt="<?php echo esc_attr($p['title']); ?>"
                     loading="lazy" decoding="async"
                     class="sk-jp-side-img"
                     itemprop="image" />
              </div>
            <?php else: ?>
              <div class="sk-jp-side-img-wrap sk-jp-side-placeholder"><span><?php echo $p['initial']; ?></span></div>
            <?php endif; ?>

            <div class="sk-jp-side-body">
              <div class="sk-jp-side-meta">
                <?php if ($p['cat']): ?>
                  <span class="sk-jp-side-badge" itemprop="keywords">
                    <span class="sk-jp-badge-dot"></span><?php echo esc_html($p['cat']->name); ?>
                  </span>
                <?php endif; ?>
                <time datetime="<?php echo esc_attr($p['date_raw']); ?>"
                      class="sk-jp-side-date"
                      itemprop="datePublished">
                  <?php echo esc_html($p['date_fmt']); ?>
                </time>
              </div>
              <h3 class="sk-jp-side-title" itemprop="headline">
                <?php echo esc_html($p['title']); ?>
              </h3>
              <p class="sk-jp-side-excerpt" itemprop="description"><?php echo esc_html($p['excerpt']); ?></p>
            </div>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    </div><!-- /sk-jp-editorial-grid -->

  </div><!-- /wrap -->
</section>

