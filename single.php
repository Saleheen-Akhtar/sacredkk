<?php
/**
 * single.php — Sacred Kompass blog post
 */

if (!is_singular('post')) {
    get_template_part('page');
    return;
}

get_header();
the_post();

$post_id    = get_the_ID();
$cats       = get_the_category($post_id);
$primary    = $cats ? $cats[0] : null;
$read_time  = sk_reading_time( $post_id );
$thumb      = get_the_post_thumbnail_url($post_id, 'full');
$blog_url   = home_url('/journal/');
?>

<!-- ── POST HERO ──────────────────────────────────────── -->
<article id="post-<?php the_ID(); ?>" <?php post_class('sk-post'); ?> itemscope itemtype="https://schema.org/BlogPosting">

  <header class="sk-post-hero">
    <div class="wrap-narrow">
      <div class="sk-blog-meta sk-post-meta reveal">
        <a href="<?php echo esc_url($blog_url); ?>" class="sk-blog-back">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          <?php esc_html_e('Journal', 'sacred-kompass'); ?>
        </a>
        <?php if ($primary) : ?>
          <a href="<?php echo esc_url(add_query_arg('cat', $primary->slug, $blog_url)); ?>" class="sk-blog-cat-badge"><?php echo esc_html($primary->name); ?></a>
        <?php endif; ?>
        <span class="sk-blog-read-time">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <?php echo esc_html($read_time); ?>
        </span>
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="sk-blog-date" itemprop="datePublished"><?php echo get_the_date('F j, Y'); ?></time>
      </div>

      <h1 class="display-xl sk-post-title reveal" data-delay="0.1" itemprop="headline"><?php the_title(); ?></h1>

      <?php if (has_excerpt()) : ?>
        <p class="body-serif sk-post-deck reveal" data-delay="0.2" itemprop="description"><?php the_excerpt(); ?></p>
      <?php endif; ?>

      <!-- Share bar -->
      <div class="sk-post-share reveal" data-delay="0.28">
        <span class="sk-share-label"><?php esc_html_e('Share', 'sacred-kompass'); ?></span>
        <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode(get_the_permalink()); ?>&text=<?php echo rawurlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="sk-share-btn" aria-label="Share on X/Twitter">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63L18.244 2.25Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode(get_the_permalink()); ?>&title=<?php echo rawurlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="sk-share-btn" aria-label="Share on LinkedIn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
        <button class="sk-share-btn sk-share-copy" data-url="<?php echo esc_attr(get_the_permalink()); ?>" aria-label="<?php esc_attr_e('Copy link', 'sacred-kompass'); ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
        </button>
      </div>
    </div>

    <?php if ($thumb) : ?>
      <div class="sk-post-hero-img reveal" data-delay="0.18">
        <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image" loading="eager">
      </div>
    <?php endif; ?>
  </header>

  <!-- ── POST BODY ──────────────────────────────────────── -->
  <div class="sk-post-body wrap-narrow" itemprop="articleBody">
    <?php the_content(); ?>
  </div>

  <!-- ── POST FOOTER ────────────────────────────────────── -->
  <footer class="sk-post-footer wrap-narrow">
    <?php if ($cats) : ?>
      <div class="sk-post-tags">
        <span class="sk-tags-label"><?php esc_html_e('Topics', 'sacred-kompass'); ?></span>
        <?php foreach ($cats as $cat) : ?>
          <a href="<?php echo esc_url(add_query_arg('cat', $cat->slug, $blog_url)); ?>" class="sk-blog-cat-badge"><?php echo esc_html($cat->name); ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="sk-post-back-wrap">
      <a href="<?php echo esc_url($blog_url); ?>" class="btn btn-ghost">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        <?php esc_html_e('Back to Journal', 'sacred-kompass'); ?>
      </a>
    </div>
  </footer>

</article>

<!-- ── RELATED POSTS ──────────────────────────────────── -->
<?php
if ($primary) {
    $related = new WP_Query([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'post__not_in'        => [$post_id],
        'category_name'       => $primary->slug,
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',   // rand = full table scan on every load; date uses the index
        'order'               => 'DESC',
        'no_found_rows'       => true,     // skip COUNT(*) — we don't need pagination here
    ]);
}
if (!empty($related) && $related->have_posts()) : ?>
<section class="sk-related-section">
  <div class="wrap">
    <p class="eyebrow"><?php esc_html_e('Continue Reading', 'sacred-kompass'); ?></p>
    <h2 class="display-h2 reveal">
      <?php esc_html_e('Related', 'sacred-kompass'); ?>
      <em><?php esc_html_e('Articles', 'sacred-kompass'); ?></em>
    </h2>
    <div class="sk-blog-grid sk-related-grid stagger-children">
      <?php while ($related->have_posts()) : $related->the_post();
        $rid   = get_the_ID();
        $rthumb = get_the_post_thumbnail_url($rid, 'medium_large');
        $rcats  = get_the_category($rid);
        $rcat   = $rcats ? $rcats[0] : null;
        $rtime  = sk_reading_time( get_the_ID() );
      ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('sk-blog-card'); ?>>
          <?php if ($rthumb) : ?>
            <a href="<?php the_permalink(); ?>" class="sk-blog-card-img-wrap" tabindex="-1" aria-hidden="true">
              <img src="<?php echo esc_url($rthumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
            </a>
          <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="sk-blog-card-img-wrap sk-blog-card-img-placeholder" tabindex="-1" aria-hidden="true">
              <span class="sk-blog-card-placeholder-letter"><?php echo esc_html(mb_substr(get_the_title(), 0, 1)); ?></span>
            </a>
          <?php endif; ?>
          <div class="sk-blog-card-body">
            <div class="sk-blog-meta">
              <?php if ($rcat) : ?><span class="sk-blog-cat-badge"><?php echo esc_html($rcat->name); ?></span><?php endif; ?>
              <span class="sk-blog-read-time">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html($rtime); ?>
              </span>
            </div>
            <h3 class="sk-blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="sk-blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18, '…'); ?></p>
            <div class="sk-blog-card-foot">
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="sk-blog-date"><?php echo get_the_date('M j, Y'); ?></time>
              <a href="<?php the_permalink(); ?>" class="sk-blog-card-arrow" aria-label="<?php the_title_attribute(); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="sk-newsletter-section reveal">
  <div class="wrap-narrow">
    <div class="sk-newsletter-card">
      <div class="sk-newsletter-deco" aria-hidden="true">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="60" cy="60" r="58" stroke="currentColor" stroke-width="1" stroke-dasharray="4 6" opacity="0.35"/>
          <circle cx="60" cy="60" r="38" stroke="currentColor" stroke-width="1" opacity="0.18"/>
          <path d="M60 22 L62 58 L60 60 L58 58 Z" fill="currentColor" opacity="0.3"/>
          <circle cx="60" cy="60" r="4" fill="currentColor" opacity="0.4"/>
        </svg>
      </div>
      <div class="sk-newsletter-content">
        <p class="eyebrow eyebrow-c">Stay Connected</p>
        <h2 class="sk-newsletter-heading">Join the <em>Inner Circle</em></h2>
        <p class="sk-newsletter-body">
          Gentle reminders of stillness, ancient wisdom, and our latest journal entries — delivered quietly to your inbox.
        </p>
        <div class="sk-newsletter-form-wrap">
          <?php echo do_shortcode('[forminator_form id="929"]'); ?>
        </div>
        <p class="sk-newsletter-disclaimer">Sacred Kompass respects your privacy. Unsubscribe anytime.</p>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
