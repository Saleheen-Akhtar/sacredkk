<?php
/**
 * home.php — Sacred Kompass Journal archive (All posts)
 * Layout: Aceternity-style — large cover story hero card + "From the archive" grid
 */

get_header();

$all_cats = get_categories(['orderby'=>'name','order'=>'ASC','hide_empty'=>true]);
?>

<!-- Journal Header -->
<section class="sk-home-hero">
  <div class="wrap sk-home-hero-inner">
    <p class="eyebrow eyebrow-c reveal"><?php esc_html_e('Insights &amp; Wisdom','sacred-kompass'); ?></p>
    <h1 class="display-xl reveal" data-delay="0.12">The Sacred <em>Journal</em></h1>
    <p class="body-serif sk-home-hero-sub reveal" data-delay="0.22">
      <?php echo esc_html(sk_option('blog_tagline','Reflections on transformation, ancient wisdom, and the art of living well.')); ?>
    </p>
  </div>
  <div class="sk-home-hero-ornament" aria-hidden="true">Journal</div>
</section>

<!-- Category Tabs -->
<section class="sk-journal-tabs-wrap">
  <div class="wrap">
    <nav class="sk-journal-tabs" aria-label="Filter by category">
      <a href="<?php echo esc_url(home_url('/journal/')); ?>" class="sk-journal-tab sk-journal-tab--active">
        <?php esc_html_e('All','sacred-kompass'); ?>
      </a>
      <?php
      if (is_array($all_cats) && !empty($all_cats)) {
          foreach ($all_cats as $cat) {
              if ($cat->slug === 'journal') continue;
              $cat_link = get_category_link($cat->term_id);
              if (!is_wp_error($cat_link)) {
                  echo '<a href="' . esc_url($cat_link) . '" class="sk-journal-tab">' . esc_html($cat->name) . '</a>';
              }
          }
      }
      ?>
    </nav>
  </div>
</section>

<!-- Posts -->
<section class="sk-home-posts-section">
  <div class="wrap">
    <?php if (have_posts()) : ?>
      <?php
      $post_num = 0;
      $all_posts_data = [];
      while (have_posts()) : the_post();
        $post_num++;
        $post_id   = get_the_ID();
        $cats      = get_the_category($post_id);
        $primary   = (is_array($cats) && !empty($cats)) ? $cats[0] : null;
        $read_time = function_exists('sk_reading_time') ? sk_reading_time($post_id) : '1 min read';
        $thumb     = get_the_post_thumbnail_url($post_id,'large');
        $all_posts_data[] = [
          'id'           => $post_id,
          'num'          => $post_num,
          'title'        => get_the_title(),
          'permalink'    => get_permalink(),
          'thumb'        => $thumb,
          'excerpt'      => wp_trim_words(get_the_excerpt(), 28, '…'),
          'excerpt_short'=> wp_trim_words(get_the_excerpt(), 16, '…'),
          'date_fmt'     => get_the_date('M j, Y'),
          'date_raw'     => get_the_date('c'),
          'cat'          => $primary,
          'read_time'    => $read_time,
          'author_name'  => get_the_author_meta('display_name', get_post_field('post_author', $post_id)),
          'author_avatar'=> get_avatar_url(get_post_field('post_author', $post_id), ['size'=>64]),
          'initial'      => mb_substr(get_the_title(), 0, 1),
          'search_attr'  => esc_attr(strtolower(get_the_title() . ' ' . ($primary ? $primary->name : '') . ' ' . wp_strip_all_tags(get_the_excerpt()))),
        ];
      endwhile;

      $hero = $all_posts_data[0] ?? null;
      $grid = array_slice($all_posts_data, 1);
      ?>

      <?php if ($hero && !is_paged()) : ?>
      <!-- COVER STORY -->
      <article class="sk-cover-story reveal" data-search="<?php echo $hero['search_attr']; ?>" itemscope itemtype="https://schema.org/BlogPosting">
        <?php if ($hero['thumb']) : ?>
          <a href="<?php echo esc_url($hero['permalink']); ?>" class="sk-cover-img-wrap">
            <img src="<?php echo esc_url($hero['thumb']); ?>" alt="<?php echo esc_attr($hero['title']); ?>" loading="eager" decoding="async" itemprop="image" />
            <div class="sk-cover-gradient"></div>
          </a>
        <?php else : ?>
          <a href="<?php echo esc_url($hero['permalink']); ?>" class="sk-cover-img-wrap sk-cover-img-placeholder">
            <span><?php echo esc_html($hero['initial']); ?></span>
            <div class="sk-cover-gradient"></div>
          </a>
        <?php endif; ?>

        <div class="sk-cover-top-meta">
          <?php if ($hero['cat']) : ?>
            <span class="sk-cover-badge"><span class="sk-jp-badge-dot"></span><?php echo esc_html($hero['cat']->name); ?></span>
          <?php endif; ?>
          <span class="sk-cover-label">Cover story</span>
        </div>

        <div class="sk-cover-body">
          <time datetime="<?php echo esc_attr($hero['date_raw']); ?>" class="sk-cover-date"><?php echo esc_html($hero['date_fmt']); ?></time>
          <h2 class="sk-cover-title" itemprop="headline">
            <a href="<?php echo esc_url($hero['permalink']); ?>"><?php echo esc_html($hero['title']); ?></a>
          </h2>
          <p class="sk-cover-excerpt" itemprop="description"><?php echo esc_html($hero['excerpt']); ?></p>
          <div class="sk-cover-author-row">
            <?php if ($hero['author_avatar']) : ?>
              <img src="<?php echo esc_url($hero['author_avatar']); ?>" alt="<?php echo esc_attr($hero['author_name']); ?>" class="sk-cover-avatar" />
            <?php endif; ?>
            <span class="sk-cover-author-name"><?php echo esc_html($hero['author_name']); ?></span>
            <span class="sk-cover-meta-sep">·</span>
            <span class="sk-cover-read-time"><?php echo esc_html($hero['date_fmt']); ?></span>
          </div>
        </div>
      </article>
      <?php endif; ?>

      <!-- FROM THE ARCHIVE -->
      <?php if (!empty($grid) || is_paged()) : ?>
      <div class="sk-archive-header reveal">
        <h2 class="sk-archive-heading">From the archive</h2>
        <?php get_search_form(); ?>
      </div>

      <div class="sk-blog-grid stagger-children">
        <?php
        $cards = is_paged() ? $all_posts_data : $grid;
        foreach ($cards as $p) :
        ?>
        <article class="sk-blog-card" data-search="<?php echo $p['search_attr']; ?>" itemscope itemtype="https://schema.org/BlogPosting">
          <?php if ($p['thumb']) : ?>
            <a href="<?php echo esc_url($p['permalink']); ?>" class="sk-blog-card-img-wrap" tabindex="-1" aria-hidden="true">
              <img src="<?php echo esc_url($p['thumb']); ?>" alt="<?php echo esc_attr($p['title']); ?>" loading="lazy" decoding="async" itemprop="image" />
            </a>
          <?php else : ?>
            <a href="<?php echo esc_url($p['permalink']); ?>" class="sk-blog-card-img-wrap sk-blog-card-img-placeholder" tabindex="-1" aria-hidden="true">
              <span class="sk-blog-card-placeholder-letter"><?php echo esc_html($p['initial']); ?></span>
            </a>
          <?php endif; ?>
          <div class="sk-blog-card-body">
            <div class="sk-blog-meta">
              <?php if (is_object($p['cat'])) : ?>
                <span class="sk-blog-cat-badge"><?php echo esc_html($p['cat']->name); ?></span>
              <?php endif; ?>
              <span class="sk-blog-read-time">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html($p['read_time']); ?>
              </span>
            </div>
            <h3 class="sk-blog-card-title" itemprop="headline">
              <a href="<?php echo esc_url($p['permalink']); ?>" itemprop="url"><?php echo esc_html($p['title']); ?></a>
            </h3>
            <p class="sk-blog-card-excerpt" itemprop="description"><?php echo esc_html($p['excerpt_short']); ?></p>
            <div class="sk-blog-card-foot">
              <time datetime="<?php echo esc_attr($p['date_raw']); ?>" class="sk-blog-date" itemprop="datePublished"><?php echo esc_html($p['date_fmt']); ?></time>
              <a href="<?php echo esc_url($p['permalink']); ?>" class="sk-blog-card-arrow" aria-label="<?php echo esc_attr($p['title']); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?>
        <nav class="sk-home-pagination reveal" aria-label="Journal pagination">
          <?php echo paginate_links(['total'=>$GLOBALS['wp_query']->max_num_pages,'current'=>max(1,get_query_var('paged')),'prev_text'=>'← '.__('Previous','sacred-kompass'),'next_text'=>__('Next','sacred-kompass').' →']); ?>
        </nav>
      <?php endif; ?>

    <?php else : ?>
      <div class="sk-home-empty reveal">
        <p class="display-h3"><?php esc_html_e('No articles yet.','sacred-kompass'); ?></p>
        <p class="body-serif"><?php esc_html_e('Check back soon — wisdom is on its way.','sacred-kompass'); ?></p>
      </div>
    <?php endif; ?>

    <div class="sk-search-no-results" id="sk-no-results" aria-live="polite">
      <p class="display-h3" style="color:var(--ink-muted);"><?php esc_html_e('No articles found.','sacred-kompass'); ?></p>
      <p class="body-serif"><?php esc_html_e('Try a different word or clear the search.','sacred-kompass'); ?></p>
    </div>
  </div>
</section>

<!-- Newsletter -->
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
