<?php
/**
 * category.php — Generic category archive with unified tab interface
 * 
 * Fallback for all categories except journal
 * Shows category tabs for easy switching between categories
 */

get_header();

// Get all categories
$all_cats    = get_categories(['orderby'=>'name','order'=>'ASC','hide_empty'=>true]);
$current_cat = get_queried_object();
$is_all      = false; // On a category page, never "All"

// Get current category name for display
$page_title = is_object($current_cat) ? $current_cat->name : 'Journal';
?>

<section class="sk-home-hero">
  <div class="wrap sk-home-hero-inner">
    <p class="eyebrow eyebrow-c reveal"><?php esc_html_e('Insights &amp; Wisdom','sacred-kompass'); ?></p>
    <h1 class="display-xl reveal" data-delay="0.12">The Sacred <em><?php echo esc_html($page_title); ?></em></h1>
    <p class="body-serif sk-home-hero-sub reveal" data-delay="0.22">
      <?php echo esc_html(sk_option('blog_tagline','Reflections on transformation, ancient wisdom, and the art of living well.')); ?>
    </p>
  </div>
  <div class="sk-home-hero-ornament" aria-hidden="true"><?php echo esc_html($page_title); ?></div>
</section>

<section class="sk-journal-tabs-wrap">
  <div class="wrap">
    <?php get_search_form(); ?>
    <nav class="sk-journal-tabs" aria-label="Filter by category">

      <!-- "All" tab - links to blog posts page -->
      <a href="<?php echo esc_url(home_url('/journal/')); ?>"
         class="sk-journal-tab<?php echo $is_all ? ' sk-journal-tab--active' : ''; ?>">
        <?php esc_html_e('All','sacred-kompass'); ?>
      </a>

      <!-- Category tabs - show all categories except journal -->
      <?php 
      if (is_array($all_cats) && !empty($all_cats)) {
          foreach ($all_cats as $cat) {
              // Skip journal category (legacy, no longer used as a tab)
              if ($cat->slug === 'journal') continue;
              
              $active = (!$is_all && is_object($current_cat) && (int)$current_cat->term_id === (int)$cat->term_id);
              $cat_link = get_category_link($cat->term_id);
              
              if (!is_wp_error($cat_link)) {
                  echo '<a href="' . esc_url($cat_link) . '" class="sk-journal-tab' . ($active ? ' sk-journal-tab--active' : '') . '">';
                  echo esc_html($cat->name);
                  echo '</a>';
              }
          }
      }
      ?>

    </nav>
  </div>
</section>

<section class="sk-home-posts-section">
  <div class="wrap">
    <?php if (have_posts()) : ?>
      <?php
      $post_num = 0;
      while (have_posts()) : the_post();
        $post_num++;
        $post_id   = get_the_ID();
        $cats      = get_the_category($post_id);
        $primary   = (is_array($cats) && !empty($cats)) ? $cats[0] : null;
        $read_time = function_exists('sk_reading_time') ? sk_reading_time($post_id) : '1 min read';
        $thumb     = get_the_post_thumbnail_url($post_id,'large');
        $is_hero   = ($post_num === 1 && !is_paged());
      ?>

      <?php if ($is_hero) : ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('sk-home-featured reveal'); ?>
          data-search="<?php echo esc_attr(strtolower(get_the_title() . ' ' . ($primary ? $primary->name : '') . ' ' . wp_strip_all_tags(get_the_excerpt()))); ?>">
          <?php if ($thumb) : ?>
            <a href="<?php the_permalink(); ?>" class="sk-home-featured-img-wrap" tabindex="-1" aria-hidden="true">
              <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
              <div class="sk-home-featured-img-overlay"></div>
            </a>
          <?php endif; ?>
          <div class="sk-home-featured-body">
            <div class="sk-blog-meta">
              <?php if (is_object($primary)) : ?><span class="sk-blog-cat-badge"><?php echo esc_html($primary->name); ?></span><?php endif; ?>
              <span class="sk-blog-read-time">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html($read_time); ?>
              </span>
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="sk-blog-date"><?php echo get_the_date('M j, Y'); ?></time>
            </div>
            <h2 class="display-h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="body-serif sk-home-featured-excerpt"><?php echo wp_trim_words(get_the_excerpt(),32,'…'); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-ghost sk-home-read-more">
              <?php esc_html_e('Read Article','sacred-kompass'); ?>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          </div>
        </article>

      <?php else : ?>
        <?php if ($post_num === 2) : ?><div class="sk-blog-grid stagger-children"><?php endif; ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('sk-blog-card'); ?>
          data-search="<?php echo esc_attr(strtolower(get_the_title() . ' ' . ($primary ? $primary->name : '') . ' ' . wp_strip_all_tags(get_the_excerpt()))); ?>">
          <?php if ($thumb) : ?>
            <a href="<?php the_permalink(); ?>" class="sk-blog-card-img-wrap" tabindex="-1" aria-hidden="true">
              <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
            </a>
          <?php else : ?>
            <a href="<?php the_permalink(); ?>" class="sk-blog-card-img-wrap sk-blog-card-img-placeholder" tabindex="-1" aria-hidden="true">
              <span class="sk-blog-card-placeholder-letter"><?php echo esc_html(mb_substr(get_the_title(),0,1)); ?></span>
            </a>
          <?php endif; ?>
          <div class="sk-blog-card-body">
            <div class="sk-blog-meta">
              <?php if (is_object($primary)) : ?><span class="sk-blog-cat-badge"><?php echo esc_html($primary->name); ?></span><?php endif; ?>
              <span class="sk-blog-read-time">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php echo esc_html($read_time); ?>
              </span>
            </div>
            <h3 class="sk-blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p class="sk-blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(),20,'…'); ?></p>
            <div class="sk-blog-card-foot">
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="sk-blog-date"><?php echo get_the_date('M j, Y'); ?></time>
              <a href="<?php the_permalink(); ?>" class="sk-blog-card-arrow" aria-label="<?php the_title_attribute(); ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </article>
        <?php if ($post_num === $GLOBALS['wp_query']->post_count) : ?></div><?php endif; ?>
      <?php endif; ?>

      <?php endwhile; ?>

      <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?>
        <nav class="sk-home-pagination reveal" aria-label="Category pagination">
          <?php echo paginate_links(['total'=>$GLOBALS['wp_query']->max_num_pages,'current'=>max(1,get_query_var('paged')),'prev_text'=>'← '.__('Previous','sacred-kompass'),'next_text'=>__('Next','sacred-kompass').' →']); ?>
        </nav>
      <?php endif; ?>

    <?php else : ?>
      <div class="sk-home-empty reveal">
        <p class="display-h3"><?php esc_html_e('No articles yet.','sacred-kompass'); ?></p>
        <p class="body-serif"><?php esc_html_e('Check back soon — wisdom is on its way.','sacred-kompass'); ?></p>
      </div>
    <?php endif; ?>

    <!-- Live-filter empty state -->
    <div class="sk-search-no-results" id="sk-no-results" aria-live="polite">
      <p class="display-h3" style="color:var(--ink-muted);"><?php esc_html_e('No articles found.','sacred-kompass'); ?></p>
      <p class="body-serif"><?php esc_html_e('Try a different word or clear the search.','sacred-kompass'); ?></p>
    </div>
  </div>
</section>

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
