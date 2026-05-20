<?php
/**
 * Single Event template — sk_event CPT.
 * URL: /events/{slug}/
 *
 * Replaces WordPress's default "event not found" 404 fallback.
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    return;
}

the_post();
$id           = get_the_ID();
$date         = get_post_meta( $id, 'event_date',         true );
$time         = get_post_meta( $id, 'event_time',         true );
$end_time     = get_post_meta( $id, 'event_end_time',     true );
$location     = get_post_meta( $id, 'event_location',     true );
$location_url = get_post_meta( $id, 'event_location_url', true );
$format       = get_post_meta( $id, 'event_format',       true ) ?: 'inperson';
$zoom_url     = get_post_meta( $id, 'event_zoom_url',     true );
$capacity     = get_post_meta( $id, 'event_capacity',     true );
$price        = get_post_meta( $id, 'event_price',        true );
$reg_url      = get_post_meta( $id, 'event_reg_url',      true );
$description  = get_post_meta( $id, 'event_description',  true );
$tag          = get_post_meta( $id, 'event_tag',          true );
$sold_out     = (bool) get_post_meta( $id, 'event_sold_out', true );
$thumb        = get_the_post_thumbnail_url( $id, 'full' );
$title        = get_the_title();
$content      = get_the_content();

$today        = date( 'Y-m-d' );
$is_past      = $date && $date < $today;

$date_fmt     = $date ? date_i18n( 'l, j F Y', strtotime( $date ) ) : '';
$format_label = match( $format ) {
    'online'  => 'Online',
    'hybrid'  => 'In Person & Online',
    default   => 'In Person',
};
?>

<div class="sk-single-event">

  <!-- ── Event Hero ── -->
  <div class="sk-event-single-hero<?php echo $thumb ? ' sk-event-single-hero--has-img' : ''; ?>">
    <?php if ( $thumb ) : ?>
      <div class="sk-event-single-hero__bg" aria-hidden="true">
        <img src="<?php echo esc_url( $thumb ); ?>" alt="" role="presentation" loading="eager" />
        <div class="sk-event-single-hero__overlay"></div>
      </div>
    <?php endif; ?>
    <div class="wrap sk-event-single-hero__inner">
      <a href="<?php echo esc_url( get_post_type_archive_link( 'sk_event' ) ); ?>" class="sk-event-back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        <?php esc_html_e( 'All Events', 'sacred-kompass' ); ?>
      </a>
      <?php if ( $tag ) : ?>
        <div class="eyebrow eyebrow-light" style="margin-top:1.5rem"><?php echo esc_html( $tag ); ?></div>
      <?php endif; ?>
      <h1 class="sk-event-single-title"><?php echo esc_html( $title ); ?></h1>
      <?php if ( $is_past ) : ?>
        <span class="sk-event-pill sk-event-pill--past"><?php esc_html_e( 'Past Event', 'sacred-kompass' ); ?></span>
      <?php endif; ?>
    </div>
  </div>

  <!-- ── Event Content ── -->
  <div class="wrap sk-event-single-wrap">
    <div class="sk-event-single-layout">

      <!-- Main body -->
      <div class="sk-event-single-body">
        <?php if ( $description ) : ?>
          <p class="sk-event-single-desc body-serif"><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>

        <?php if ( $content ) : ?>
          <div class="sk-event-single-content entry-content">
            <?php echo wp_kses_post( $content ); ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar / details card -->
      <aside class="sk-event-single-sidebar">
        <div class="sk-event-details-card">

          <?php if ( $date ) : ?>
          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Date', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value"><?php echo esc_html( $date_fmt ); ?></div>
            </div>
          </div>
          <?php endif; ?>

          <?php if ( $time ) : ?>
          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Time', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value">
                <?php echo esc_html( date_i18n( 'g:i A', strtotime( $time ) ) );
                      if ( $end_time ) echo ' – ' . esc_html( date_i18n( 'g:i A', strtotime( $end_time ) ) ); ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Format', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value"><?php echo esc_html( $format_label ); ?></div>
            </div>
          </div>

          <?php if ( $location && $format !== 'online' ) : ?>
          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Location', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value">
                <?php if ( $location_url ) : ?>
                  <a href="<?php echo esc_url( $location_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $location ); ?></a>
                <?php else : ?>
                  <?php echo esc_html( $location ); ?>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <?php if ( $price ) : ?>
          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Investment', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value"><?php echo esc_html( $price ); ?></div>
            </div>
          </div>
          <?php endif; ?>

          <?php if ( $capacity ) : ?>
          <div class="sk-event-detail-row">
            <div class="sk-event-detail-icon" aria-hidden="true">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
              <div class="sk-event-detail-label"><?php esc_html_e( 'Capacity', 'sacred-kompass' ); ?></div>
              <div class="sk-event-detail-value"><?php echo esc_html( $capacity ); ?> <?php esc_html_e( 'seats', 'sacred-kompass' ); ?></div>
            </div>
          </div>
          <?php endif; ?>

          <!-- CTA -->
          <?php if ( ! $is_past ) : ?>
            <?php if ( $sold_out ) : ?>
              <div class="sk-event-soldout-block"><?php esc_html_e( 'This event is fully booked.', 'sacred-kompass' ); ?></div>
            <?php elseif ( $reg_url ) : ?>
              <a href="<?php echo esc_url( $reg_url ); ?>" class="btn btn-primary sk-event-register-btn" target="_blank" rel="noopener">
                <?php esc_html_e( 'Reserve Your Spot', 'sacred-kompass' ); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </a>
            <?php endif; ?>
          <?php endif; ?>

        </div><!-- /sk-event-details-card -->
      </aside>

    </div><!-- /sk-event-single-layout -->
  </div><!-- /wrap -->

</div><!-- /sk-single-event -->

<?php get_footer(); ?>
