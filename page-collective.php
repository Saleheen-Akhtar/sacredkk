<?php
/**
 * Template Name: The Collective
 *
 * Dedicated page for Sacred Kompass team members.
 * Founders: full-width editorial magazine rows (photo left/right alternating + rich copy).
 * Other members: clean card grid.
 */

get_header();

// ── Editable strings (#20–#29) ──────────────────────────────────────────────
$col_hero_eyebrow   = sk_option('collective_hero_eyebrow',   'Sacred Kompass');
$col_hero_sub       = sk_option('collective_hero_sub',       'Guides, teachers, and practitioners united by one vision: to help individuals, leaders, and organisations reconnect with their inner compass.');
$col_founders_eyebrow = sk_option('collective_founders_eyebrow', 'The Founders');
$col_founder_badge  = sk_option('collective_founder_badge',  'Founder');
$col_founder_cta    = sk_option('collective_founder_cta',    'Book a Session');
$col_team_eyebrow   = sk_option('collective_team_eyebrow',   'The Team');
$col_cta_eyebrow    = sk_option('collective_cta_eyebrow',    'Ready to begin?');
$col_cta_heading_1  = sk_option('collective_cta_heading_1',  'Work with');
$col_cta_heading_em = sk_option('collective_cta_heading_em', 'our Guides');
$col_cta_body       = sk_option('collective_cta_body',       'Every guide in the Collective offers sessions tailored to your journey. Reach out and we\'ll match you with the right person.');
$col_cta_button     = sk_option('collective_cta_button',     'Book a Discovery Call');
// ────────────────────────────────────────────────────────────────────────────

$team_posts = get_posts([
  'post_type'              => 'sk_team',
  'post_status'            => 'publish',
  'posts_per_page'         => 40,
  'orderby'                => 'menu_order',
  'order'                  => 'ASC',
  'no_found_rows'          => true,
  'update_post_meta_cache' => true,
  'update_post_term_cache' => false,
]);

$founders = [];
$others   = [];
foreach ($team_posts as $tp) {
  $member = [
    'id'      => $tp->ID,
    'image'   => get_post_thumbnail_url($tp->ID, 'large') ?: get_post_meta($tp->ID, 'team_image', true) ?: '',
    'name'    => trim((get_post_meta($tp->ID, 'team_first_name', true) ?: get_the_title($tp)) . ' ' . (get_post_meta($tp->ID, 'team_last_name', true) ?: '')),
    'role'    => get_post_meta($tp->ID, 'team_role',   true) ?: '',
    'origin'  => get_post_meta($tp->ID, 'team_origin', true) ?: '',
    'bio'     => get_post_meta($tp->ID, 'team_bio',    true) ?: '',
    'tags'    => array_filter(array_map('trim', explode("\n", get_post_meta($tp->ID, 'team_tags', true) ?: ''))),
  ];
  if (get_post_meta($tp->ID, 'team_is_founder', true)) {
    $founders[] = $member;
  } else {
    $others[] = $member;
  }
}

if (empty($founders)) {
  $founders = [
    [
      'id'     => 0,
      'image'  => '',
      'name'   => 'Kalai Somoo',
      'role'   => 'Founder and Lead Guide',
      'origin' => 'Singapore',
      'bio'    => 'Kalai founded Sacred Kompass with a vision to reconnect people to their inner wisdom. With deep roots in Vedic philosophy, sacred feminine practices, Jyotish astrology, and women\'s empowerment, she guides individuals and organisations through transformative, inside-out growth.',
      'tags'   => ["Women's Wellness", 'Vedic Philosophy', 'Jyotish Astrology', 'Sacred Feminine', 'Coaching'],
    ],
    [
      'id'     => 0,
      'image'  => '',
      'name'   => 'Christophe Grigri',
      'role'   => 'International Coordination and Communication',
      'origin' => 'France',
      'bio'    => 'Christophe brings decades of international experience bridging cultures through compassionate dialogue and conscious leadership. Trained in Gandhian non-violence and NVC, he coordinates Sacred Kompass\'s global outreach and shapes the communicative heart of the collective.',
      'tags'   => ['NVC', 'Gandhian Non-Violence', 'International Coordination', 'Conscious Leadership'],
    ],
  ];
}

$all_members = array_merge($founders, $others);
?>

<!-- ── Page Hero ── -->
<section class="sk-collective-hero">
  <div class="wrap sk-collective-hero-inner">
    <p class="eyebrow eyebrow-c reveal"><?php echo esc_html($col_hero_eyebrow); ?></p>
    <h1 class="display-xl reveal" data-delay="0.1">The <em>Collective</em></h1>
    <p class="body-serif sk-collective-hero-sub reveal" data-delay="0.2">
      <?php echo esc_html($col_hero_sub); ?>
    </p>
  </div>
  <div class="sk-home-hero-ornament" aria-hidden="true">Collective</div>
</section>

<!-- ── Founders — 3-column testimonial slider ── -->
<?php if (!empty($founders)): ?>
<section class="sk-collective-founders-section">
  <div class="wrap">
    <div class="eyebrow reveal sk-collective-section-eyebrow">
      <?php echo esc_html($col_founders_eyebrow); ?>
    </div>

    <!-- 3-col slider: thumbnails | photo | text+nav -->
    <div class="sk-fts" id="sk-fts" aria-label="<?php esc_attr_e('Founders','sacred-kompass'); ?>">

      <!-- COL 1: pagination + thumbnail stack -->
      <div class="sk-fts-col sk-fts-col--meta">

        <div class="sk-fts-meta-top">
          <!-- Pagination counter -->
          <span class="sk-fts-count" id="sk-fts-count" aria-live="polite">
            <span id="sk-fts-cur">01</span> / <span id="sk-fts-total"><?php echo sprintf('%02d', count($founders)); ?></span>
          </span>
          <!-- Rotated label -->
          <span class="sk-fts-label" aria-hidden="true"><?php esc_html_e('Founders','sacred-kompass'); ?></span>
        </div>

        <!-- Thumbnail strip -->
        <div class="sk-fts-thumbs" id="sk-fts-thumbs" aria-label="<?php esc_attr_e('Navigate to founder','sacred-kompass'); ?>">
          <?php foreach ($founders as $idx => $m):
            $thumb_src = $m['image'] ?: '';
            $initial   = esc_html(strtoupper(mb_substr($m['name'], 0, 1)));
          ?>
          <button class="sk-fts-thumb<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                  data-slide="<?php echo $idx; ?>"
                  aria-label="<?php echo esc_attr($m['name']); ?>"
                  type="button">
            <?php if ($thumb_src): ?>
              <img src="<?php echo esc_url($thumb_src); ?>" alt="<?php echo esc_attr($m['name']); ?>" loading="lazy" />
            <?php else: ?>
              <span class="sk-fts-thumb-initial"><?php echo $initial; ?></span>
            <?php endif; ?>
          </button>
          <?php endforeach; ?>
        </div>

      </div><!-- /sk-fts-col--meta -->

      <!-- COL 2: main photo -->
      <div class="sk-fts-col sk-fts-col--photo">
        <div class="sk-fts-photo-stage" id="sk-fts-photo-stage" aria-hidden="true">
          <?php foreach ($founders as $idx => $m):
            $src     = $m['image'] ?: '';
            $initial = esc_html(strtoupper(mb_substr($m['name'], 0, 1)));
          ?>
          <div class="sk-fts-photo-frame<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $idx; ?>">
            <?php if ($src): ?>
              <img src="<?php echo esc_url($src); ?>" alt="<?php echo esc_attr($m['name']); ?>" loading="lazy" />
            <?php else: ?>
              <div class="sk-fts-photo-initial"><?php echo $initial; ?></div>
            <?php endif; ?>
            <div class="sk-fts-photo-badge"><?php echo esc_html($col_founder_badge); ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="sk-fts-swipe-hint" aria-hidden="true">← swipe →</div>
      </div><!-- /sk-fts-col--photo -->

      <!-- COL 3: text content + navigation -->
      <div class="sk-fts-col sk-fts-col--text">

        <!-- Animated text panel -->
        <div class="sk-fts-text-stage" id="sk-fts-text-stage">
          <?php foreach ($founders as $idx => $m): ?>
          <div class="sk-fts-panel<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $idx; ?>">

            <?php if ($m['origin']): ?>
            <span class="sk-fts-origin">
              <svg width="8" height="8" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                <circle cx="5" cy="5" r="4" stroke="currentColor" stroke-width="1.2"/>
                <circle cx="5" cy="5" r="1.5" fill="currentColor"/>
              </svg>
              <?php echo esc_html($m['origin']); ?>
            </span>
            <?php endif; ?>

            <h2 class="sk-fts-name"><?php echo esc_html($m['name']); ?></h2>

            <?php if ($m['role']): ?>
            <span class="sk-fts-role"><?php echo esc_html($m['role']); ?></span>
            <?php endif; ?>

            <?php if ($m['bio']): ?>
            <blockquote class="sk-fts-bio"><?php echo esc_html($m['bio']); ?></blockquote>
            <?php endif; ?>

            <?php if (!empty($m['tags'])): ?>
            <div class="sk-collective-tags sk-fts-tags">
              <?php foreach ($m['tags'] as $tag): ?>
                <span class="trad-tag"><?php echo esc_html($tag); ?></span>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

          </div>
          <?php endforeach; ?>
        </div><!-- /sk-fts-text-stage -->



      </div><!-- /sk-fts-col--text -->

    </div><!-- /sk-fts -->

  </div>
</section>

<?php endif; ?>

<!-- ── Other Members ── -->
<?php if (!empty($others)): ?>
<div class="sk-collective-divider"></div>

<section class="sk-collective-section" style="padding: 5rem 0 7rem;">
  <div class="wrap">
    <div class="eyebrow reveal" style="text-align:center; margin-bottom:3.5rem;">
      <?php echo esc_html($col_team_eyebrow); ?>
    </div>
    <div class="sk-collective-grid sk-collective-grid--team stagger-children">
      <?php foreach ($others as $idx => $m):
        $initial = esc_html(strtoupper(mb_substr($m['name'], 0, 1)));
      ?>
      <article class="sk-collective-card reveal d<?php echo ($idx % 3) + 1; ?>">

        <div class="sk-collective-photo">
          <?php if ($m['image']): ?>
            <img src="<?php echo esc_url($m['image']); ?>"
                 alt="<?php echo esc_attr($m['name']); ?>"
                 loading="lazy" />
          <?php else: ?>
            <div class="sk-collective-initial"><span><?php echo $initial; ?></span></div>
          <?php endif; ?>
        </div>

        <!-- Default name strip (visible) -->
        <div class="sk-collective-body">
          <div class="sk-collective-name--strip"><?php echo esc_html($m['name']); ?></div>
          <?php if ($m['role']): ?>
            <span class="sk-collective-role--strip"><?php echo esc_html($m['role']); ?></span>
          <?php endif; ?>
        </div>

        <!-- Hover overlay (full info) -->
        <div class="sk-collective-hover-overlay">
          <h3 class="sk-collective-name"><?php echo esc_html($m['name']); ?></h3>
          <?php if ($m['role']): ?>
            <span class="sk-collective-role"><?php echo esc_html($m['role']); ?></span>
          <?php endif; ?>
          <?php if ($m['bio']): ?>
            <p class="sk-collective-bio"><?php echo esc_html($m['bio']); ?></p>
          <?php endif; ?>
          <?php if (!empty($m['tags'])): ?>
            <div class="sk-collective-tags">
              <?php foreach ($m['tags'] as $tag): ?>
                <span class="trad-tag"><?php echo esc_html($tag); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>


<?php get_footer(); ?>

