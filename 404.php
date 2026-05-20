<?php get_header(); ?>
<section class="sk-home-hero" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
  <div class="wrap sk-home-hero-inner">
    <p class="eyebrow eyebrow-c reveal">Error 404</p>
    <h1 class="display-xl reveal">Page Not <em>Found</em></h1>
    <p class="body-serif sk-home-hero-sub reveal">The path you are looking for has been moved or no longer exists.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="margin-top: 2rem;">Return Home</a>
  </div>
</section>
<?php get_footer(); ?>
