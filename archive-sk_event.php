<?php
/**
 * Archive template for sk_event CPT.
 * URL: /events/
 *
 * Displays upcoming events first (sorted by event_date ASC),
 * then past events below a divider.
 */
get_header();

$today = date('Y-m-d');

// Upcoming events
$upcoming = new WP_Query([
    'post_type'      => 'sk_event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [[
        'key'     => 'event_date',
        'value'   => $today,
        'compare' => '>=',
        'type'    => 'DATE',
    ]],
]);

// Past events
$past = new WP_Query([
    'post_type'      => 'sk_event',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'meta_query'     => [[
        'key'     => 'event_date',
        'value'   => $today,
        'compare' => '<',
        'type'    => 'DATE',
    ]],
]);

/**
 * Helper: render a single event card.
 */
function sk_render_event_card( WP_Post $post, bool $past = false ): void {
    $id           = $post->ID;
    $date         = get_post_meta( $id, 'event_date',        true );
    $time         = get_post_meta( $id, 'event_time',        true );
    $end_time     = get_post_meta( $id, 'event_end_time',    true );
    $location     = get_post_meta( $id, 'event_location',    true );
    $location_url = get_post_meta( $id, 'event_location_url',true );
    $format       = get_post_meta( $id, 'event_format',      true ) ?: 'inperson';
    $zoom_url     = get_post_meta( $id, 'event_zoom_url',    true );
    $price        = get_post_meta( $id, 'event_price',       true );
    $reg_url      = get_post_meta( $id, 'event_reg_url',     true );
    $description  = get_post_meta( $id, 'event_description', true );
    $tag          = get_post_meta( $id, 'event_tag',         true );
    $sold_out     = (bool) get_post_meta( $id, 'event_sold_out', true );
    $capacity     = get_post_meta( $id, 'event_capacity',    true );
    $thumb        = get_the_post_thumbnail_url( $id, 'large' );
    $permalink    = get_permalink( $id );
    $title        = get_the_title( $id );

    $date_fmt  = $date ? date_i18n( 'D, j M Y', strtotime( $date ) ) : '';
    $month_abbr = $date ? date_i18n( 'M', strtotime( $date ) ) : '';
    $day_num    = $date ? date_i18n( 'j', strtotime( $date ) ) : '';

    $format_label = match( $format ) {
        'online'  => 'Online',
        'hybrid'  => 'Hybrid',
        default   => 'In Person',
    };
    ?>
    <article class="sk-event-card<?php echo $past ? ' sk-event-card--past' : ''; ?> reveal">
      <?php if ( $thumb ) : ?>
        <a href="<?php echo esc_url( $permalink ); ?>" class="sk-event-card__img-wrap" tabindex="-1" aria-hidden="true">
          <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" class="sk-event-card__img" />
        </a>
      <?php endif; ?>

      <div class="sk-event-card__body">
        <div class="sk-event-card__meta-row">
          <?php if ( $date ) : ?>
          <div class="sk-event-card__date-badge" aria-label="<?php echo esc_attr( $date_fmt ); ?>">
            <span class="sk-event-badge-month"><?php echo esc_html( $month_abbr ); ?></span>
            <span class="sk-event-badge-day"><?php echo esc_html( $day_num ); ?></span>
          </div>
          <?php endif; ?>

          <div class="sk-event-card__tags">
            <?php if ( $tag ) : ?>
              <span class="sk-event-pill sk-event-pill--tag"><?php echo esc_html( $tag ); ?></span>
            <?php endif; ?>
            <span class="sk-event-pill sk-event-pill--format"><?php echo esc_html( $format_label ); ?></span>
            <?php if ( $sold_out ) : ?>
              <span class="sk-event-pill sk-event-pill--soldout"><?php esc_html_e( 'Sold Out', 'sacred-kompass' ); ?></span>
            <?php endif; ?>
          </div>
        </div>

        <h3 class="sk-event-card__title">
          <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
        </h3>

        <?php if ( $description ) : ?>
          <p class="sk-event-card__desc"><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>

        <div class="sk-event-card__details">
          <?php if ( $time ) : ?>
            <span class="sk-event-detail">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              <?php echo esc_html( date_i18n( 'g:i A', strtotime( $time ) ) );
                    if ( $end_time ) echo ' – ' . esc_html( date_i18n( 'g:i A', strtotime( $end_time ) ) ); ?>
            </span>
          <?php endif; ?>

          <?php if ( $location && $format !== 'online' ) : ?>
            <span class="sk-event-detail">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
              <?php if ( $location_url ) : ?>
                <a href="<?php echo esc_url( $location_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $location ); ?></a>
              <?php else : ?>
                <?php echo esc_html( $location ); ?>
              <?php endif; ?>
            </span>
          <?php endif; ?>

          <?php if ( $price ) : ?>
            <span class="sk-event-detail sk-event-detail--price">
              <?php echo esc_html( $price ); ?>
            </span>
          <?php endif; ?>
        </div>

        <?php if ( ! $past && $reg_url && ! $sold_out ) : ?>
          <a href="<?php echo esc_url( $reg_url ); ?>" class="btn btn-primary sk-event-register-btn" target="_blank" rel="noopener">
            <?php esc_html_e( 'Reserve Your Spot', 'sacred-kompass' ); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        <?php elseif ( ! $past && $sold_out ) : ?>
          <span class="sk-event-soldout-label"><?php esc_html_e( 'This event is fully booked', 'sacred-kompass' ); ?></span>
        <?php endif; ?>
      </div>
    </article>
    <?php
}
?>

<div class="sk-events-page">

  <!-- ── Page Hero ── -->
  <div class="sk-events-hero">
    <div class="wrap">
      <div class="eyebrow eyebrow-light"><?php esc_html_e( 'Gatherings & Workshops', 'sacred-kompass' ); ?></div>
      <h1 class="display-h2"><?php esc_html_e( 'Upcoming', 'sacred-kompass' ); ?> <em><?php esc_html_e( 'Events', 'sacred-kompass' ); ?></em></h1>
      <p class="sk-events-hero__sub"><?php esc_html_e( 'Join us in person or online for immersive experiences rooted in ancient wisdom.', 'sacred-kompass' ); ?></p>
    </div>
  </div>

  <div class="wrap sk-events-wrap">

    <!-- ── Upcoming ── -->
    <?php if ( $upcoming->have_posts() ) : ?>
      <div class="sk-events-grid" id="upcoming-events">
        <?php while ( $upcoming->have_posts() ) : $upcoming->the_post(); ?>
          <?php sk_render_event_card( get_post() ); ?>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>

    <?php else : ?>
      <div class="sk-events-empty">
        <p><?php esc_html_e( 'No upcoming events at this time. Check back soon — we\'re always planning something meaningful.', 'sacred-kompass' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn-primary">
          <?php esc_html_e( 'Join the Waitlist', 'sacred-kompass' ); ?>
        </a>
      </div>
    <?php endif; ?>

    <!-- ── Past Events ── -->
    <?php if ( $past->have_posts() ) : ?>
      <div class="sk-events-past-divider">
        <span><?php esc_html_e( 'Past Events', 'sacred-kompass' ); ?></span>
      </div>
      <div class="sk-events-grid sk-events-grid--past">
        <?php while ( $past->have_posts() ) : $past->the_post(); ?>
          <?php sk_render_event_card( get_post(), true ); ?>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>

  </div><!-- /wrap -->
</div>

<?php get_footer(); ?>
