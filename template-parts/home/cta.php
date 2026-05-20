<?php
/**
 * Sacred Kompass — CTA / Contact Section (Upgraded Editorial Version)
 */
$email           = sk_option('footer_email',           'collective@sacredkompass.org');
$phone           = sk_option('footer_phone',           '+65 84343915');
$phone_clean     = preg_replace('/[^+0-9]/', '', $phone);
$eyebrow         = sk_option('cta_eyebrow', '');
$heading_raw     = sk_option('cta_heading',            '');
$sub             = sk_option('cta_sub', '');
$form_id         = sk_option('forminator_form_id',     '');
$cta_default_l1  = sk_option('cta_default_heading_l1', '');
$cta_default_l2  = sk_option('cta_default_heading_l2', '');
$cta_default_l3  = sk_option('cta_default_heading_em', '');
$card_eyebrow    = sk_option('cta_card_eyebrow', '');
$card_subh1      = sk_option('cta_card_subheading_1', '');
$card_subh2      = sk_option('cta_card_subheading_em', '');
$ff_name         = sk_option('cta_ff_name_label', 'Your Name');
$ff_email        = sk_option('cta_ff_email_label',      'Email Address');
$ff_msg          = sk_option('cta_ff_msg_label',        'Your Message');
$ff_submit       = sk_option('cta_ff_submit_label', 'Send');
$ff_note         = sk_option('cta_ff_note', '');
?>

<section class="cta-section" id="contact" aria-labelledby="cta-heading-el">
  <div class="wrap">
    <div class="cta-layout">
      
      <div class="cta-text-col reveal-cta">
        <?php if ( $eyebrow ) : ?><div class="eyebrow eyebrow-light"><?php echo esc_html($eyebrow); ?></div><?php endif; ?>
        <h2 class="display-h2 cta-h2" id="cta-heading-el">
  <?php if ($heading_raw) : echo wp_kses_post($heading_raw);
  else : ?>
    Contact <em>us</em>
  <?php endif; ?>
</h2>
        <?php if ( $sub ) : ?><p class="cta-sub"><?php echo esc_html($sub); ?></p><?php endif; ?>
        
        <?php if ( $email || $phone ) : ?>
        <div class="sk-contact-info-links">
            <?php if ( $email ) : ?><a href="mailto:<?php echo esc_attr($email); ?>" class="sk-info-link"><?php echo esc_html($email); ?></a><?php endif; ?>
            <?php if ( $phone ) : ?><a href="tel:<?php echo esc_attr($phone_clean); ?>" class="sk-info-link"><?php echo esc_html($phone); ?></a><?php endif; ?>
        </div>
        <?php endif; ?>


      </div>

      <div class="cta-form-col reveal-cta">
        <div class="sk-contact-card">
          <div class="sk-contact-card-header">
            <?php if ( $card_eyebrow ) : ?><p class="eyebrow eyebrow-c"><?php echo esc_html($card_eyebrow); ?></p><?php endif; ?>
            <h3 class="display-h2"><?php echo esc_html($card_subh1); ?> <em><?php echo esc_html($card_subh2); ?></em></h3>
          </div>

          <?php if ($form_id && shortcode_exists('forminator_form')) :
            echo do_shortcode('[forminator_form id="' . absint($form_id) . '"]');
          else : ?>
            <div class="sk-contact-fallback-form">
                <div class="sk-form-row-double">
                  <div class="sk-form-row">
                    <label for="sk-cf-name"><?php echo esc_html($ff_name); ?></label>
                    <input type="text" id="sk-cf-name" placeholder="<?php esc_attr_e('Full Name','sacred-kompass'); ?>" />
                  </div>
                  <div class="sk-form-row">
                    <label for="sk-cf-email"><?php echo esc_html($ff_email); ?></label>
                    <input type="email" id="sk-cf-email" placeholder="<?php esc_attr_e('your@email.com','sacred-kompass'); ?>" />
                  </div>
                </div>
                <div class="sk-form-row">
                  <label for="sk-cf-msg"><?php echo esc_html($ff_msg); ?></label>
                  <textarea id="sk-cf-msg" placeholder="<?php esc_attr_e('Share what brings you here…','sacred-kompass'); ?>"></textarea>
                </div>
                <div class="sk-form-submit">
                  <button type="submit" class="btn btn-primary"><?php echo esc_html($ff_submit); ?> <span>→</span></button>
                  <?php if ( $ff_note ) : ?><span class="sk-form-note"><?php echo esc_html($ff_note); ?></span><?php endif; ?>
                </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>



