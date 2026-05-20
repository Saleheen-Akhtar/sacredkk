<?php
/**
 * Sacred Kompass v5.3 — Custom Post Types
 * Native meta boxes — no ACF required.
 */
defined('ABSPATH') || exit;

add_action('init', 'sk_register_post_types', 10);
function sk_register_post_types(): void {
    register_post_type('sk_offering', [
        'labels' => ['name'=>'Offerings','singular_name'=>'Offering','add_new_item'=>'Add New Offering','edit_item'=>'Edit Offering'],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'supports'        => ['title','thumbnail','page-attributes'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => true,
    ]);
    register_post_type('sk_faq', [
        'labels' => ['name'=>'FAQ','singular_name'=>'FAQ Item','add_new_item'=>'Add New FAQ Item','edit_item'=>'Edit FAQ Item'],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'supports'        => ['title','page-attributes'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => true,
    ]);
}

/* ── Native meta boxes (no ACF needed) ── */
add_action('add_meta_boxes', 'sk_register_meta_boxes');
function sk_register_meta_boxes(): void {
    add_meta_box('sk_offering_details', 'Offering Details',    'sk_offering_meta_box',       'sk_offering', 'normal', 'high');
    add_meta_box('sk_offering_image',   '★ Card Image',        'sk_offering_image_meta_box',  'sk_offering', 'normal', 'high');
    add_meta_box('sk_faq_answer',       'FAQ Answer',          'sk_faq_meta_box',             'sk_faq',      'normal', 'high');
}

function sk_offering_meta_box(WP_Post $post): void {
    wp_nonce_field('sk_offering_save', 'sk_offering_nonce');
    $tag          = get_post_meta($post->ID, 'offering_tag',          true);
    $desc         = get_post_meta($post->ID, 'offering_desc',         true);
    $price        = get_post_meta($post->ID, 'offering_price',        true);
    $duration     = get_post_meta($post->ID, 'offering_duration',     true);
    $format       = get_post_meta($post->ID, 'offering_format',       true) ?: 'both';
    $capacity     = get_post_meta($post->ID, 'offering_capacity',     true);
    $availability = get_post_meta($post->ID, 'offering_availability', true);
    $cta_url      = get_post_meta($post->ID, 'offering_cta_url',      true);
    $format_opts  = ['inperson' => 'In-person', 'online' => 'Online', 'both' => 'Both'];
    ?>
    <table class="form-table" style="width:100%">
      <tr><th style="width:180px"><label>Category Tag</label></th>
          <td><input type="text" name="offering_tag" value="<?php echo esc_attr($tag); ?>" style="width:100%" placeholder="e.g. Personal · Guidance · Corporate" /></td></tr>
      <tr><th><label>Description</label></th>
          <td><textarea name="offering_desc" rows="4" style="width:100%"><?php echo esc_textarea($desc); ?></textarea></td></tr>
      <tr><th><label>Price (optional)</label></th>
          <td><input type="text" name="offering_price" value="<?php echo esc_attr($price); ?>" style="width:100%" placeholder="e.g. From SGD 150 — leave blank to hide" /></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>Session Duration</label></th>
          <td><input type="text" name="offering_duration" value="<?php echo esc_attr($duration); ?>" style="width:280px" placeholder="e.g. 90 min · 4 weeks · Half-day" />
          <p class="description" style="margin-top:4px;font-size:11px">Shown on the card and in the expanded modal.</p></td></tr>
      <tr><th><label>Format</label></th>
          <td><?php foreach ($format_opts as $val => $lbl): $chk = checked($format, $val, false); ?>
          <label style="display:inline-flex;align-items:center;gap:5px;margin-right:18px;cursor:pointer">
            <input type="radio" name="offering_format" value="<?php echo esc_attr($val); ?>"<?php echo $chk; ?>> <?php echo esc_html($lbl); ?>
          </label><?php endforeach; ?>
          <p class="description" style="margin-top:4px;font-size:11px">Displays as a small badge on the offering card.</p></td></tr>
      <tr><th><label>Max Group Size</label></th>
          <td><input type="text" name="offering_capacity" value="<?php echo esc_attr($capacity); ?>" style="width:280px" placeholder="e.g. 1-on-1 · Up to 8 people · Open group" /></td></tr>
      <tr><th><label>Availability Note</label></th>
          <td><input type="text" name="offering_availability" value="<?php echo esc_attr($availability); ?>" style="width:100%" placeholder="e.g. Next intake: June · Rolling enrolment · Waitlist open" />
          <p class="description" style="margin-top:4px;font-size:11px">Shown below price in modal. Leave blank to hide.</p></td></tr>
      <tr><th><label>Book This URL</label></th>
          <td><input type="url" name="offering_cta_url" value="<?php echo esc_attr($cta_url); ?>" style="width:100%" placeholder="https://… or /#contact — leave blank to use global enquiry form" />
          <p class="description" style="margin-top:4px;font-size:11px">If set, the &ldquo;Enquire&rdquo; button links here directly instead of the global contact section.</p></td></tr>
    </table>
    <?php
}
function sk_offering_image_meta_box(WP_Post $post): void {
    wp_nonce_field('sk_offering_image_save', 'sk_offering_image_nonce');
    $attachment_id = (int) get_post_meta($post->ID, 'offering_image_id', true);
    $img_url       = $attachment_id
        ? wp_get_attachment_image_url($attachment_id, 'medium')
        : get_post_meta($post->ID, 'offering_image', true);

    echo '<p style="font-size:12px;color:#666;margin-top:0">This image appears as the card background in the Offerings carousel. Click <strong>Upload / Select Image</strong> or drag a file directly onto the button to upload instantly.</p>';
    echo '<p style="font-size:11px;color:#888;margin:0 0 12px;padding:8px 10px;background:#f9f6f0;border-left:3px solid #c4a02a;border-radius:2px">Recommended: 800×600px landscape. If no image is set here, the card falls back to the standard <em>Featured Image</em>.</p>';
    echo '<input type="hidden" name="offering_image_id" id="sk_offering_image_id" value="' . esc_attr($attachment_id) . '" />';
    echo '<input type="hidden" name="offering_image"    id="sk_offering_image_url" value="' . esc_attr($img_url ?: '') . '" />';

    $preview_style = $img_url ? '' : 'display:none;';
    echo '<div id="sk-offering-preview-wrap" style="margin-bottom:10px;' . $preview_style . '">';
    echo '<img id="sk-offering-preview" src="' . esc_url($img_url ?: '') . '" style="width:100%;border-radius:6px;object-fit:cover;aspect-ratio:4/3;max-height:160px" />';
    echo '</div>';

    echo '<div style="display:flex;gap:8px;flex-wrap:wrap">';
    echo '<button type="button" id="sk-offering-upload-btn" class="button">'
       . '<span class="dashicons dashicons-upload" style="vertical-align:middle;margin-right:4px"></span>Upload / Select Image</button>';
    echo '<button type="button" id="sk-offering-remove-btn" class="button" style="color:#a00;' . ($img_url ? '' : 'display:none;') . '">Remove</button>';
    echo '</div>';
    echo '<p style="font-size:11px;color:#888;margin-top:8px">Order offerings via <strong>Page Attributes → Order</strong> (lower number = shown first in carousel).</p>';
    ?>
    <script>
    jQuery(function($){
        var frame;
        var $preview   = $('#sk-offering-preview');
        var $wrap      = $('#sk-offering-preview-wrap');
        var $idField   = $('#sk_offering_image_id');
        var $urlField  = $('#sk_offering_image_url');
        var $removeBtn = $('#sk-offering-remove-btn');

        function setImage(id, url) {
            $idField.val(id); $urlField.val(url);
            $preview.attr('src', url); $wrap.show(); $removeBtn.show();
        }

        $('#sk-offering-upload-btn').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Select Card Image', button: { text: 'Use this image' }, multiple: false, library: { type: 'image' } });
            frame.on('select', function(){
                var att = frame.state().get('selection').first().toJSON();
                var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                setImage(att.id, url);
            });
            frame.open();
        });

        $('#sk-offering-upload-btn').on('dragover', function(e){
            e.preventDefault(); $(this).css('background','#f0f6ff');
        }).on('dragleave', function(){
            $(this).css('background','');
        }).on('drop', function(e){
            e.preventDefault(); $(this).css('background','');
            var file = e.originalEvent.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            var fd = new FormData();
            fd.append('action','upload-attachment'); fd.append('async-upload', file);
            fd.append('name', file.name); fd.append('_wpnonce','<?php echo wp_create_nonce('media-form'); ?>');
            fd.append('post_id','<?php echo (int) $post->ID; ?>');
            $.ajax({ url: ajaxurl, type: 'POST', data: fd, processData: false, contentType: false,
                success: function(res){
                    if (res && res.success && res.data && res.data.id) {
                        var url = (res.data.sizes && res.data.sizes.medium) ? res.data.sizes.medium.url : res.data.url;
                        setImage(res.data.id, url);
                    }
                }
            });
        });

        $removeBtn.on('click', function(e){
            e.preventDefault(); $idField.val(''); $urlField.val('');
            $preview.attr('src',''); $wrap.hide(); $(this).hide();
        });
    });
    </script>
    <?php
}

function sk_faq_meta_box(WP_Post $post): void {
    wp_nonce_field('sk_faq_save', 'sk_faq_nonce');
    $answer = get_post_meta($post->ID, 'faq_answer', true);
    $group  = get_post_meta($post->ID, 'faq_group',  true);
    ?>
    <table class="form-table" style="width:100%">
      <tr><th style="width:180px"><label>Group / Category</label></th>
          <td><input type="text" name="faq_group" value="<?php echo esc_attr($group); ?>" style="width:280px" placeholder="e.g. General, Jyotish, Corporate" />
          <p class="description" style="margin-top:4px;font-size:11px">FAQs are visually separated by group with a heading. Leave blank to show ungrouped.</p></td></tr>
      <tr><th><label>Answer</label></th>
          <td><textarea name="faq_answer" rows="6" style="width:100%"><?php echo esc_textarea($answer); ?></textarea></td></tr>
    </table>
    <p style="font-size:12px;color:#666;margin-top:12px">💡 <strong>Ordering tip:</strong> Use <em>Page Attributes → Order</em> (right sidebar) to control which FAQ appears first. Lower number = displayed first. If you don't see it, click <strong>Screen Options</strong> at the top of the page and enable "Page Attributes".</p>
    <?php
}
add_action('save_post', 'sk_save_meta_boxes');
function sk_save_meta_boxes(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (get_post_type($post_id) === 'sk_offering') {
        if (!isset($_POST['sk_offering_nonce']) || !wp_verify_nonce($_POST['sk_offering_nonce'], 'sk_offering_save')) return;
        update_post_meta($post_id, 'offering_tag',          sanitize_text_field($_POST['offering_tag']          ?? ''));
        update_post_meta($post_id, 'offering_desc',         sanitize_textarea_field($_POST['offering_desc']         ?? ''));
        update_post_meta($post_id, 'offering_price',        sanitize_text_field($_POST['offering_price']        ?? ''));
        update_post_meta($post_id, 'offering_duration',     sanitize_text_field($_POST['offering_duration']     ?? ''));
        $fmt_allowed = ['inperson', 'online', 'both'];
        $fmt = $_POST['offering_format'] ?? 'both';
        update_post_meta($post_id, 'offering_format',       in_array($fmt, $fmt_allowed) ? $fmt : 'both');
        update_post_meta($post_id, 'offering_capacity',     sanitize_text_field($_POST['offering_capacity']     ?? ''));
        update_post_meta($post_id, 'offering_availability', sanitize_text_field($_POST['offering_availability'] ?? ''));
        update_post_meta($post_id, 'offering_cta_url',      esc_url_raw($_POST['offering_cta_url']             ?? ''));

        // Card image — from wp.media picker (nonce checked separately)
        if (isset($_POST['sk_offering_image_nonce']) && wp_verify_nonce($_POST['sk_offering_image_nonce'], 'sk_offering_image_save')) {
            $img_id = (int) ($_POST['offering_image_id'] ?? 0);
            if ($img_id > 0) {
                update_post_meta($post_id, 'offering_image_id', $img_id);
                $url = wp_get_attachment_image_url($img_id, 'large') ?: '';
                if ($url) update_post_meta($post_id, 'offering_image', $url);
            } elseif (isset($_POST['offering_image_id']) && $_POST['offering_image_id'] === '') {
                update_post_meta($post_id, 'offering_image_id', '');
                update_post_meta($post_id, 'offering_image', '');
            }
        }
    }

    if (get_post_type($post_id) === 'sk_faq') {
        if (!isset($_POST['sk_faq_nonce']) || !wp_verify_nonce($_POST['sk_faq_nonce'], 'sk_faq_save')) return;
        update_post_meta($post_id, 'faq_answer', sanitize_textarea_field($_POST['faq_answer'] ?? ''));
        update_post_meta($post_id, 'faq_group',  sanitize_text_field($_POST['faq_group']  ?? ''));
    }
}

/* ══════════════════════════════════════════════════════════
   TEAM / FOUNDERS — sk_team CPT
   Client can Add / Remove / Reorder via wp-admin › Team Members
   ══════════════════════════════════════════════════════════ */
add_action('init', 'sk_register_team_cpt', 10);
function sk_register_team_cpt(): void {
    register_post_type('sk_team', [
        'labels' => [
            'name'          => 'Team Members',
            'singular_name' => 'Team Member',
            'add_new_item'  => 'Add New Team Member',
            'edit_item'     => 'Edit Team Member',
            'menu_name'     => 'Team Members',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,            // registered via sk_nest_cpt_menus — prevents duplicate entry
        'supports'        => ['title', 'thumbnail', 'page-attributes'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-groups',
    ]);
}

/* ── Team member meta boxes ── */
add_action('add_meta_boxes', 'sk_register_team_meta_boxes');
function sk_register_team_meta_boxes(): void {
    add_meta_box(
        'sk_team_details',
        '★ Team Member Details',
        'sk_team_meta_box_cb',
        'sk_team',
        'normal',
        'high'
    );
    add_meta_box(
        'sk_team_image_url',
        '★ Portrait Photo (URL or Upload)',
        'sk_team_image_meta_box_cb',
        'sk_team',
        'side',
        'high'
    );
    add_meta_box(
        'sk_team_founder_flag',
        '⚑ Founder Card',
        'sk_team_founder_flag_cb',
        'sk_team',
        'side',
        'high'
    );
}

function sk_team_image_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_team_image_save', 'sk_team_image_nonce');
    $attachment_id = (int) get_post_meta($post->ID, 'team_image_id', true);
    $img_url       = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'medium') : get_post_meta($post->ID, 'team_image', true);

    echo '<p style="font-size:12px;color:#666;margin-top:0">Upload or select a portrait photo from the Media Library. Drag &amp; drop onto the button below, or click to browse.</p>';

    // Hidden fields
    echo '<input type="hidden" name="team_image_id" id="sk_team_image_id" value="' . esc_attr($attachment_id) . '" />';
    echo '<input type="hidden" name="team_image"    id="sk_team_image_url" value="' . esc_attr($img_url ?: '') . '" />';

    // Preview
    $preview_style = $img_url ? '' : 'display:none;';
    echo '<div id="sk-team-preview-wrap" style="margin-bottom:10px;' . $preview_style . '">';
    echo '<img id="sk-team-preview" src="' . esc_url($img_url ?: '') . '" style="width:100%;border-radius:6px;object-fit:cover;aspect-ratio:3/4;max-height:240px" />';
    echo '</div>';

    // Buttons
    echo '<div style="display:flex;gap:8px;flex-wrap:wrap">';
    echo '<button type="button" id="sk-team-upload-btn" class="button">'
       . '<span class="dashicons dashicons-upload" style="vertical-align:middle;margin-right:4px"></span>Upload / Select Photo</button>';
    echo '<button type="button" id="sk-team-remove-btn" class="button" style="color:#a00;' . ($img_url ? '' : 'display:none;') . '">Remove Photo</button>';
    echo '</div>';

    echo '<p style="font-size:11px;color:#888;margin-top:8px">Min 520×700px portrait recommended. '
       . 'Drag a file directly onto the <em>Upload / Select Photo</em> button to upload without opening the picker. '
       . 'Order members with the <strong>Order</strong> field (Page Attributes box).</p>';

    // wp.media JS — loaded only on sk_team edit screens
    ?>
    <script>
    jQuery(function($){
        var frame;
        var $preview  = $('#sk-team-preview');
        var $wrap     = $('#sk-team-preview-wrap');
        var $idField  = $('#sk_team_image_id');
        var $urlField = $('#sk_team_image_url');
        var $removeBtn= $('#sk-team-remove-btn');

        function setImage(id, url) {
            $idField.val(id);
            $urlField.val(url);
            $preview.attr('src', url);
            $wrap.show();
            $removeBtn.show();
        }

        // Open media picker
        $('#sk-team-upload-btn').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title:    'Select Portrait Photo',
                button:   { text: 'Use this photo' },
                multiple: false,
                library:  { type: 'image' },
            });
            frame.on('select', function(){
                var att = frame.state().get('selection').first().toJSON();
                var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                setImage(att.id, url);
            });
            frame.open();
        });

        // Drag & drop onto the upload button
        $('#sk-team-upload-btn').on('dragover', function(e){
            e.preventDefault();
            $(this).css('background', '#f0f6ff');
        }).on('dragleave', function(){
            $(this).css('background', '');
        }).on('drop', function(e){
            e.preventDefault();
            $(this).css('background', '');
            var file = e.originalEvent.dataTransfer.files[0];
            if (!file || !file.type.startsWith('image/')) return;

            var formData = new FormData();
            formData.append('action',   'upload-attachment');
            formData.append('async-upload', file);
            formData.append('name',     file.name);
            formData.append('_wpnonce', '<?php echo wp_create_nonce('media-form'); ?>');
            formData.append('post_id',  '<?php echo (int) $post->ID; ?>');

            $.ajax({
                url:         ajaxurl,
                type:        'POST',
                data:        formData,
                processData: false,
                contentType: false,
                success: function(res){
                    if (res && res.success && res.data && res.data.id) {
                        var url = (res.data.sizes && res.data.sizes.medium) ? res.data.sizes.medium.url : res.data.url;
                        setImage(res.data.id, url);
                    }
                }
            });
        });

        // Remove
        $removeBtn.on('click', function(e){
            e.preventDefault();
            $idField.val('');
            $urlField.val('');
            $preview.attr('src', '');
            $wrap.hide();
            $(this).hide();
        });
    });
    </script>
    <?php
}

function sk_team_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_team_save', 'sk_team_nonce');
    $first     = get_post_meta($post->ID, 'team_first_name', true);
    $last      = get_post_meta($post->ID, 'team_last_name',  true);
    $origin    = get_post_meta($post->ID, 'team_origin',     true);
    $role      = get_post_meta($post->ID, 'team_role',       true);
    $bio       = get_post_meta($post->ID, 'team_bio',        true);
    $tags      = get_post_meta($post->ID, 'team_tags',       true);
    $linkedin  = get_post_meta($post->ID, 'team_linkedin',   true);
    $instagram = get_post_meta($post->ID, 'team_instagram',  true);
    ?>
    <table class="form-table" style="width:100%">
      <tr><th style="width:160px"><label>First Name</label></th>
          <td><input type="text" name="team_first_name" value="<?php echo esc_attr($first); ?>" style="width:100%" /></td></tr>
      <tr><th><label>Last Name</label></th>
          <td><input type="text" name="team_last_name" value="<?php echo esc_attr($last); ?>" style="width:100%" /></td></tr>
      <tr><th><label>Origin / Country</label></th>
          <td><input type="text" name="team_origin" value="<?php echo esc_attr($origin); ?>" placeholder="e.g. Singapore" style="width:100%" /></td></tr>
      <tr><th><label>Role / Title</label></th>
          <td><input type="text" name="team_role" value="<?php echo esc_attr($role); ?>" placeholder="e.g. Founder and Lead Guide" style="width:100%" /></td></tr>
      <tr><th><label>Bio</label></th>
          <td><textarea name="team_bio" rows="5" style="width:100%"><?php echo esc_textarea($bio); ?></textarea></td></tr>
      <tr><th><label>Expertise Tags<br><small style="font-weight:300">(one per line)</small></label></th>
          <td><textarea name="team_tags" rows="4" style="width:100%" placeholder="Vedic Philosophy&#10;Jyotish Astrology&#10;Coaching"><?php echo esc_textarea($tags); ?></textarea></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>LinkedIn URL</label></th>
          <td><input type="url" name="team_linkedin" value="<?php echo esc_attr($linkedin); ?>" style="width:100%" placeholder="https://linkedin.com/in/username (optional)" /></td></tr>
      <tr><th><label>Instagram URL</label></th>
          <td><input type="url" name="team_instagram" value="<?php echo esc_attr($instagram); ?>" style="width:100%" placeholder="https://instagram.com/username (optional)" /></td></tr>
    </table>
    <p style="font-size:12px;color:#666;margin-top:12px">💡 <strong>Tip:</strong> Use <em>Page Attributes → Order</em> (right sidebar) to control display order. Lower number = displayed first (big card).</p>
    <?php
}
function sk_team_founder_flag_cb(WP_Post $post): void {
    wp_nonce_field('sk_team_founder_flag_save', 'sk_team_founder_flag_nonce');
    $is_founder = (bool) get_post_meta($post->ID, 'team_is_founder', true);
    echo '<p style="font-size:12px;color:#666;margin:0 0 10px">Mark this person as a Founder. Founders always appear in the two dedicated founder cards (right column of the Founders section), regardless of menu order.</p>';
    echo '<label style="display:flex;align-items:center;gap:8px;font-weight:600;cursor:pointer">';
    echo '<input type="checkbox" name="team_is_founder" value="1"' . checked($is_founder, true, false) . ' style="width:16px;height:16px" />';
    echo 'This is a Founder</label>';
    echo '<p style="font-size:11px;color:#888;margin-top:8px">Only two founder cards are shown. If more than two members are marked as founders, the first two (by Order) will appear.</p>';
}

add_action('save_post_sk_team', 'sk_save_team_founder_flag', 10, 1);
add_action('save_post_sk_team', 'sk_save_team_meta',         20, 1);
function sk_save_team_founder_flag(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sk_team_founder_flag_nonce']) || !wp_verify_nonce($_POST['sk_team_founder_flag_nonce'], 'sk_team_founder_flag_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    update_post_meta($post_id, 'team_is_founder', isset($_POST['team_is_founder']) ? '1' : '');
}
function sk_save_team_meta(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sk_team_nonce']) || !wp_verify_nonce($_POST['sk_team_nonce'], 'sk_team_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['team_first_name','team_last_name','team_origin','team_role'];
    foreach ($fields as $f) {
        update_post_meta($post_id, $f, sanitize_text_field($_POST[$f] ?? ''));
    }
    update_post_meta($post_id, 'team_bio',  sanitize_textarea_field($_POST['team_bio']  ?? ''));
    update_post_meta($post_id, 'team_tags', sanitize_textarea_field($_POST['team_tags'] ?? ''));

    // Image — saved from wp.media picker (attachment ID + URL)
    $img_id = (int) ($_POST['team_image_id'] ?? 0);
    if ($img_id > 0) {
        update_post_meta($post_id, 'team_image_id', $img_id);
        $url = wp_get_attachment_image_url($img_id, 'large') ?: '';
        if ($url) update_post_meta($post_id, 'team_image', $url);
    } elseif (isset($_POST['team_image_id']) && $_POST['team_image_id'] === '') {
        // Removal
        update_post_meta($post_id, 'team_image_id', '');
        update_post_meta($post_id, 'team_image', '');
    } elseif (!empty($_POST['team_image']) && str_starts_with($_POST['team_image'], 'http')) {
        // Legacy: plain URL pasted without using the picker
        update_post_meta($post_id, 'team_image', esc_url_raw($_POST['team_image']));
    }
    update_post_meta($post_id, 'team_linkedin',  esc_url_raw($_POST['team_linkedin']  ?? ''));
    update_post_meta($post_id, 'team_instagram', esc_url_raw($_POST['team_instagram'] ?? ''));
}

/* Helper so founders.php can get thumbnail URL */
if (!function_exists('get_post_thumbnail_url')) {
    function get_post_thumbnail_url(int $post_id, string $size = 'large'): string {
        $id = get_post_thumbnail_id($post_id);
        return $id ? (wp_get_attachment_image_url($id, $size) ?: '') : '';
    }
}


/* ══════════════════════════════════════════════════════════
   LEGAL PAGES — sk_legal CPT
   Admin can edit Privacy Policy, Terms of Use, and Disclaimer
   directly from WP Admin › Sacred Kompass › Legal Pages.
   The three page templates read content from these posts.
   ══════════════════════════════════════════════════════════ */

add_action('init', 'sk_register_legal_cpt', 10);
function sk_register_legal_cpt(): void {
    register_post_type('sk_legal', [
        'labels' => [
            'name'          => 'Legal Pages',
            'singular_name' => 'Legal Page',
            'edit_item'     => 'Edit Legal Page',
            'menu_name'     => 'Legal Pages',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,            // registered via sk_nest_legal_menu — prevents duplicate entry
        'supports'        => ['title'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => false,
        'menu_icon'       => 'dashicons-media-document',
    ]);
}

add_action('admin_menu', 'sk_nest_legal_menu', 100);
function sk_nest_legal_menu(): void {
    add_submenu_page(
        'sk-settings',
        'Legal Pages',
        '✦ Legal Pages',
        'edit_posts',
        'edit.php?post_type=sk_legal'
    );
}

/* ── Meta box: one rich textarea per legal page ── */
add_action('add_meta_boxes', 'sk_register_legal_meta_boxes');
function sk_register_legal_meta_boxes(): void {
    add_meta_box(
        'sk_legal_content',
        '★ Page Content (HTML allowed)',
        'sk_legal_meta_box_cb',
        'sk_legal',
        'normal',
        'high'
    );
    add_meta_box(
        'sk_legal_meta',
        '★ Header Details',
        'sk_legal_header_meta_box_cb',
        'sk_legal',
        'normal',
        'high'
    );
}

function sk_legal_header_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_legal_header_save', 'sk_legal_header_nonce');
    $effective_date = get_post_meta($post->ID, 'legal_effective_date', true) ?: '24 March 2026';
    $location       = get_post_meta($post->ID, 'legal_location',       true) ?: 'Singapore';
    $eyebrow        = get_post_meta($post->ID, 'legal_eyebrow',        true) ?: 'Sacred Kompass Collective';

    echo '<table class="form-table" style="width:100%">';
    echo '<tr><th style="width:180px"><label>Eyebrow Text</label></th><td><input type="text" name="legal_eyebrow" value="' . esc_attr($eyebrow) . '" style="width:100%" /></td></tr>';
    echo '<tr><th><label>Effective Date</label></th><td><input type="text" name="legal_effective_date" value="' . esc_attr($effective_date) . '" placeholder="e.g. 24 March 2026" style="width:100%" /></td></tr>';
    echo '<tr><th><label>Location</label></th><td><input type="text" name="legal_location" value="' . esc_attr($location) . '" placeholder="e.g. Singapore" style="width:100%" /></td></tr>';
    echo '</table>';
    echo '<p style="font-size:12px;color:#666;margin-top:8px">These appear in the page header banner.</p>';
}

function sk_legal_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_legal_save', 'sk_legal_nonce');
    $content = get_post_meta($post->ID, 'legal_content', true) ?: '';

    echo '<p style="font-size:12px;color:#666;margin:0 0 10px">Use the editor below to write and format the page content. '
       . 'Switch to <strong>Text</strong> mode (top-right of editor) to paste or edit raw HTML. '
       . 'The <code>&lt;div class="legal-note"&gt;&lt;p&gt;…&lt;/p&gt;&lt;/div&gt;</code> wrapper creates the gold-bordered callout box at the bottom.</p>';

    wp_editor(
        $content,
        'legal_content',
        [
            'textarea_name' => 'legal_content',
            'textarea_rows' => 24,
            'media_buttons' => false,
            'teeny'         => false,
            'tinymce'       => [
                'toolbar1' => 'formatselect bold italic | bullist numlist | link unlink | undo redo',
                'toolbar2' => 'blockquote hr removeformat | pastetext',
                'block_formats' => 'Paragraph=p; Heading 2=h2; Heading 3=h3',
            ],
            'quicktags'     => ['buttons' => 'strong,em,link,ul,ol,li,close'],
        ]
    );
}

add_action('save_post_sk_legal', 'sk_save_legal_meta');
function sk_save_legal_meta(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['sk_legal_nonce']) && wp_verify_nonce($_POST['sk_legal_nonce'], 'sk_legal_save')) {
        $allowed_html = wp_kses_allowed_html('post');
        $content = wp_kses(stripslashes($_POST['legal_content'] ?? ''), $allowed_html);
        update_post_meta($post_id, 'legal_content', $content);
    }

    if (isset($_POST['sk_legal_header_nonce']) && wp_verify_nonce($_POST['sk_legal_header_nonce'], 'sk_legal_header_save')) {
        update_post_meta($post_id, 'legal_effective_date', sanitize_text_field($_POST['legal_effective_date'] ?? ''));
        update_post_meta($post_id, 'legal_location',       sanitize_text_field($_POST['legal_location']       ?? ''));
        update_post_meta($post_id, 'legal_eyebrow',        sanitize_text_field($_POST['legal_eyebrow']        ?? ''));
    }
}

/* ── Helper: read legal page content by slug (privacy-policy, terms, disclaimer) ── */
function sk_get_legal_page(string $slug): array {
    $q = new WP_Query([
        'post_type'      => 'sk_legal',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'name'           => $slug,
        'no_found_rows'  => true,
    ]);
    if ($q->have_posts()) {
        $p = $q->posts[0];
        return [
            'eyebrow'        => get_post_meta($p->ID, 'legal_eyebrow',        true) ?: 'Sacred Kompass Collective',
            'title'          => get_the_title($p),
            'effective_date' => get_post_meta($p->ID, 'legal_effective_date', true) ?: '24 March 2026',
            'location'       => get_post_meta($p->ID, 'legal_location',       true) ?: 'Singapore',
            'content'        => get_post_meta($p->ID, 'legal_content',        true) ?: '',
        ];
    }
    return [];
}


/* ══════════════════════════════════════════════════════════
   NAV ITEMS — sk_nav CPT
   Admin controls: label, URL, order, icon, highlight style.
   The header.php reads these (with static fallback).
   ══════════════════════════════════════════════════════════ */

add_action('init', 'sk_register_nav_cpt', 10);
function sk_register_nav_cpt(): void {
    register_post_type('sk_nav', [
        'labels' => [
            'name'          => 'Navigation Items',
            'singular_name' => 'Nav Item',
            'add_new_item'  => 'Add Nav Item',
            'edit_item'     => 'Edit Nav Item',
            'menu_name'     => 'Navigation',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,            // registered via sk_nest_nav_menu — prevents duplicate entry
        'supports'        => ['title', 'page-attributes'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-menu',
    ]);
}

add_action('admin_menu', 'sk_nest_nav_menu', 100);
function sk_nest_nav_menu(): void {
    add_submenu_page(
        'sk-settings',
        'Navigation Items',
        '☰ Navigation',
        'edit_posts',
        'edit.php?post_type=sk_nav'
    );
}

add_action('add_meta_boxes', 'sk_register_nav_meta_boxes');
function sk_register_nav_meta_boxes(): void {
    add_meta_box(
        'sk_nav_details',
        '☰ Nav Item Settings',
        'sk_nav_meta_box_cb',
        'sk_nav',
        'normal',
        'high'
    );
}

function sk_nav_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_nav_save', 'sk_nav_nonce');

    $url       = get_post_meta($post->ID, 'nav_url',       true) ?: '';
    $icon      = get_post_meta($post->ID, 'nav_icon',      true) ?: '';
    $highlight = get_post_meta($post->ID, 'nav_highlight', true) ?: 'none';
    $target    = get_post_meta($post->ID, 'nav_target',    true) ?: '_self';
    $mobile    = get_post_meta($post->ID, 'nav_show_mobile', true);
    $desktop   = get_post_meta($post->ID, 'nav_show_desktop', true);
    if ($mobile  === '') $mobile  = '1';
    if ($desktop === '') $desktop = '1';

    $highlight_opts = [
        'none'    => 'None (default link style)',
        'gold'    => 'Gold accent underline',
        'btn'     => 'Button (btn-primary)',
        'btn-ghost' => 'Button (ghost/outlined)',
    ];

    echo '<p style="font-size:12px;color:#666;margin:0 0 16px">Use <em>Page Attributes → Order</em> (right sidebar) to control menu order. Lower number = leftmost.</p>';
    echo '<table class="form-table" style="width:100%">';

    // Label (post title) — just remind them
    echo '<tr><th style="width:180px"><label>Label</label></th><td><p style="margin:0;color:#888;font-size:12px">Set via the <strong>Title</strong> field above (e.g. "About", "Our Services").</p></td></tr>';

    // URL
    echo '<tr><th><label for="sk_nav_url">URL / Anchor</label></th>';
    echo '<td><input type="text" id="sk_nav_url" name="nav_url" value="' . esc_attr($url) . '" style="width:100%" placeholder="e.g. /#about or /journal/ or https://…" /></td></tr>';

    // Icon (dashicon or emoji)
    echo '<tr><th><label for="sk_nav_icon">Icon (optional)</label></th>';
    echo '<td><input type="text" id="sk_nav_icon" name="nav_icon" value="' . esc_attr($icon) . '" style="width:200px" placeholder="✦ or dashicon name" />';
    echo '<p style="margin:4px 0 0;font-size:11px;color:#888">Paste an emoji/symbol, or a <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicon</a> class (e.g. <code>dashicons-heart</code>).</p></td></tr>';

    // Highlight style
    echo '<tr><th><label>Highlight Style</label></th><td>';
    foreach ($highlight_opts as $val => $label) {
        $checked = checked($highlight, $val, false);
        echo '<label style="display:inline-flex;align-items:center;gap:6px;margin-right:18px;cursor:pointer">';
        echo "<input type=\"radio\" name=\"nav_highlight\" value=\"{$val}\"{$checked} /> {$label}";
        echo '</label>';
    }
    echo '</td></tr>';

    // Target
    echo '<tr><th><label>Open in</label></th><td>';
    echo '<label style="margin-right:16px"><input type="radio" name="nav_target" value="_self"' . checked($target,'_self',false) . '> Same tab</label>';
    echo '<label><input type="radio" name="nav_target" value="_blank"' . checked($target,'_blank',false) . '> New tab</label>';
    echo '</td></tr>';

    // Visibility
    echo '<tr><th><label>Visibility</label></th><td style="display:flex;gap:20px;align-items:center">';
    echo '<label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="checkbox" name="nav_show_desktop" value="1"' . checked($desktop,'1',false) . ' /> Show on desktop</label>';
    echo '<label style="display:flex;align-items:center;gap:6px;cursor:pointer"><input type="checkbox" name="nav_show_mobile"  value="1"' . checked($mobile,'1',false)  . ' /> Show on mobile</label>';
    echo '</td></tr>';

    echo '</table>';
    echo '<p style="margin-top:14px;font-size:12px;color:#888">💡 <strong>Tip:</strong> The "Contact Us" CTA button in the nav is managed separately via <em>Sacred Kompass → Settings → General</em>.</p>';
}

add_action('save_post_sk_nav', 'sk_save_nav_meta');
function sk_save_nav_meta(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sk_nav_nonce']) || !wp_verify_nonce($_POST['sk_nav_nonce'], 'sk_nav_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, 'nav_url',          sanitize_text_field($_POST['nav_url']       ?? ''));
    update_post_meta($post_id, 'nav_icon',         sanitize_text_field($_POST['nav_icon']      ?? ''));
    update_post_meta($post_id, 'nav_highlight',    sanitize_key(       $_POST['nav_highlight'] ?? 'none'));
    update_post_meta($post_id, 'nav_target',       in_array($_POST['nav_target'] ?? '', ['_self','_blank']) ? $_POST['nav_target'] : '_self');
    update_post_meta($post_id, 'nav_show_desktop', isset($_POST['nav_show_desktop']) ? '1' : '');
    update_post_meta($post_id, 'nav_show_mobile',  isset($_POST['nav_show_mobile'])  ? '1' : '');

    // Bust nav cache
    delete_transient('sk_nav_items');
}

/**
 * Helper: get nav items from CPT (cached), fall back to static defaults.
 */
function sk_get_nav_items(): array {
    $cached = get_transient('sk_nav_items');
    if ($cached !== false) return $cached;

    // Build section-enabled map once
    $section_map     = function_exists('sk_nav_section_map') ? sk_nav_section_map() : [];
    $section_enabled = function(string $url) use ($section_map): bool {
        // Normalise: strip home_url prefix so we match relative fragments
        $rel = str_replace(rtrim(home_url(), '/'), '', $url);
        foreach ($section_map as $anchor => $key) {
            if ($rel === $anchor || $url === $anchor) {
                return function_exists('sk_section_enabled') ? sk_section_enabled($key) : true;
            }
        }
        return true; // external/unlisted links always shown
    };

    $posts = get_posts([
        'post_type'      => 'sk_nav',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);

    if (empty($posts)) {
        // Static fallback — filter by section state
        $fallback = [
            ['label'=>'About',          'url'=>'/#about',            'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
            ['label'=>'Astrology',      'url'=>'/#offerings',        'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
            ['label'=>'Art',            'url'=>'/#art',              'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
            ['label'=>'Articles',       'url'=>'/journal/',          'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
            ['label'=>'FAQ',            'url'=>'/#faq',              'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
            ['label'=>'The Collective', 'url'=>'/collective/',       'highlight'=>'none','target'=>'_self','desktop'=>true,'mobile'=>true,'icon'=>''],
        ];
        $items = array_values(array_filter($fallback, fn($i) => $section_enabled($i['url'])));
        set_transient('sk_nav_items', $items, 5 * MINUTE_IN_SECONDS);
        return $items;
    }

    $items = [];
    foreach ($posts as $p) {
        $url = get_post_meta($p->ID, 'nav_url', true) ?: '#';
        if (!$section_enabled($url)) continue; // skip disabled sections
        $items[] = [
            'label'     => get_the_title($p),
            'url'       => $url,
            'highlight' => get_post_meta($p->ID, 'nav_highlight',    true) ?: 'none',
            'target'    => get_post_meta($p->ID, 'nav_target',       true) ?: '_self',
            'desktop'   => (bool)(get_post_meta($p->ID, 'nav_show_desktop', true) !== ''),
            'mobile'    => (bool)(get_post_meta($p->ID, 'nav_show_mobile',  true) !== ''),
            'icon'      => get_post_meta($p->ID, 'nav_icon',         true) ?: '',
        ];
    }

    set_transient('sk_nav_items', $items, 5 * MINUTE_IN_SECONDS);
    return $items;
}
/* ══════════════════════════════════════════════════════════
   EVENTS — sk_event CPT
   Shown in homepage widget (upcoming 3) + standalone /events page.
   ══════════════════════════════════════════════════════════ */

add_action('init', 'sk_register_event_cpt', 10);
function sk_register_event_cpt(): void {
    register_post_type('sk_event', [
        'labels' => [
            'name'          => 'Events',
            'singular_name' => 'Event',
            'add_new_item'  => 'Add New Event',
            'edit_item'     => 'Edit Event',
            'menu_name'     => 'Events',
        ],
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'supports'        => ['title', 'thumbnail', 'page-attributes'],
        'rewrite'         => ['slug' => 'events'],
        'capability_type' => 'post',
        'has_archive'     => true,
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-calendar-alt',
    ]);
}

add_action('admin_menu', 'sk_nest_event_menu', 100);
function sk_nest_event_menu(): void {
    add_submenu_page(
        'sk-settings',
        'Events',
        '✦ Events',
        'edit_posts',
        'edit.php?post_type=sk_event'
    );
}

add_action('add_meta_boxes', 'sk_register_event_meta_boxes');
function sk_register_event_meta_boxes(): void {
    add_meta_box(
        'sk_event_details',
        '★ Event Details',
        'sk_event_meta_box_cb',
        'sk_event',
        'normal',
        'high'
    );
}

function sk_event_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_event_save', 'sk_event_nonce');
    $date         = get_post_meta($post->ID, 'event_date',         true);
    $time         = get_post_meta($post->ID, 'event_time',         true);
    $end_time     = get_post_meta($post->ID, 'event_end_time',     true);
    $location     = get_post_meta($post->ID, 'event_location',     true);
    $location_url = get_post_meta($post->ID, 'event_location_url', true);
    $format       = get_post_meta($post->ID, 'event_format',       true) ?: 'inperson';
    $zoom_url     = get_post_meta($post->ID, 'event_zoom_url',     true);
    $capacity     = get_post_meta($post->ID, 'event_capacity',     true);
    $price        = get_post_meta($post->ID, 'event_price',        true);
    $reg_url      = get_post_meta($post->ID, 'event_reg_url',      true);
    $description  = get_post_meta($post->ID, 'event_description',  true);
    $tag          = get_post_meta($post->ID, 'event_tag',          true);
    $sold_out     = (bool) get_post_meta($post->ID, 'event_sold_out', true);

    $format_opts = ['inperson' => 'In-person', 'online' => 'Online', 'hybrid' => 'Hybrid'];
    ?>
    <table class="form-table" style="width:100%">
      <tr><th style="width:180px"><label>Event Tag / Category</label></th>
          <td><input type="text" name="event_tag" value="<?php echo esc_attr($tag); ?>" style="width:260px" placeholder="e.g. Workshop · Retreat · Masterclass" /></td></tr>
      <tr><th><label>Short Description</label></th>
          <td><textarea name="event_description" rows="3" style="width:100%" placeholder="One or two lines shown on the event card."><?php echo esc_textarea($description); ?></textarea></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>Date</label></th>
          <td><input type="date" name="event_date" value="<?php echo esc_attr($date); ?>" style="width:200px" /></td></tr>
      <tr><th><label>Start Time</label></th>
          <td><input type="time" name="event_time" value="<?php echo esc_attr($time); ?>" style="width:140px" /></td></tr>
      <tr><th><label>End Time</label></th>
          <td><input type="time" name="event_end_time" value="<?php echo esc_attr($end_time); ?>" style="width:140px" />
          <p class="description" style="margin-top:4px;font-size:11px">Optional — leave blank to show start time only.</p></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>Format</label></th>
          <td><?php foreach ($format_opts as $val => $lbl): $chk = checked($format, $val, false); ?>
          <label style="display:inline-flex;align-items:center;gap:5px;margin-right:18px;cursor:pointer">
            <input type="radio" name="event_format" value="<?php echo esc_attr($val); ?>"<?php echo $chk; ?>> <?php echo esc_html($lbl); ?>
          </label><?php endforeach; ?></td></tr>
      <tr><th><label>Venue / Location</label></th>
          <td><input type="text" name="event_location" value="<?php echo esc_attr($location); ?>" style="width:100%" placeholder="e.g. The Hive, Carpenter Street, Singapore" /></td></tr>
      <tr><th><label>Google Maps URL</label></th>
          <td><input type="url" name="event_location_url" value="<?php echo esc_attr($location_url); ?>" style="width:100%" placeholder="https://maps.google.com/… (optional)" /></td></tr>
      <tr><th><label>Zoom / Online Link</label></th>
          <td><input type="url" name="event_zoom_url" value="<?php echo esc_attr($zoom_url); ?>" style="width:100%" placeholder="https://zoom.us/j/… — shown only for Online / Hybrid" /></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>Capacity</label></th>
          <td><input type="text" name="event_capacity" value="<?php echo esc_attr($capacity); ?>" style="width:200px" placeholder="e.g. 20 seats · Unlimited · 8 spots" /></td></tr>
      <tr><th><label>Price</label></th>
          <td><input type="text" name="event_price" value="<?php echo esc_attr($price); ?>" style="width:200px" placeholder="e.g. SGD 120 · Free · From SGD 80" /></td></tr>
      <tr><th><label>Registration URL</label></th>
          <td><input type="url" name="event_reg_url" value="<?php echo esc_attr($reg_url); ?>" style="width:100%" placeholder="https://… or /#contact for the enquiry form" /></td></tr>
      <tr><th><label>Sold Out</label></th>
          <td><label style="display:flex;align-items:center;gap:8px;cursor:pointer">
            <input type="checkbox" name="event_sold_out" value="1"<?php checked($sold_out); ?>>
            Mark as sold out (replaces register button with "Sold Out" badge)</label></td></tr>
    </table>
    <p style="font-size:12px;color:#666;margin-top:12px">💡 <strong>Tip:</strong> Past events (date before today) are automatically hidden from the homepage widget. They stay visible on the /events archive page for reference.</p>
    <?php
}

add_action('save_post_sk_event', 'sk_save_event_meta');
function sk_save_event_meta(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sk_event_nonce']) || !wp_verify_nonce($_POST['sk_event_nonce'], 'sk_event_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, 'event_tag',          sanitize_text_field($_POST['event_tag']         ?? ''));
    update_post_meta($post_id, 'event_description',  sanitize_textarea_field($_POST['event_description'] ?? ''));
    update_post_meta($post_id, 'event_date',         sanitize_text_field($_POST['event_date']         ?? ''));
    update_post_meta($post_id, 'event_time',         sanitize_text_field($_POST['event_time']         ?? ''));
    update_post_meta($post_id, 'event_end_time',     sanitize_text_field($_POST['event_end_time']     ?? ''));
    $fmt_allowed = ['inperson', 'online', 'hybrid'];
    $fmt = $_POST['event_format'] ?? 'inperson';
    update_post_meta($post_id, 'event_format',       in_array($fmt, $fmt_allowed) ? $fmt : 'inperson');
    update_post_meta($post_id, 'event_location',     sanitize_text_field($_POST['event_location']     ?? ''));
    update_post_meta($post_id, 'event_location_url', esc_url_raw($_POST['event_location_url']         ?? ''));
    update_post_meta($post_id, 'event_zoom_url',     esc_url_raw($_POST['event_zoom_url']             ?? ''));
    update_post_meta($post_id, 'event_capacity',     sanitize_text_field($_POST['event_capacity']     ?? ''));
    update_post_meta($post_id, 'event_price',        sanitize_text_field($_POST['event_price']        ?? ''));
    update_post_meta($post_id, 'event_reg_url',      esc_url_raw($_POST['event_reg_url']              ?? ''));
    update_post_meta($post_id, 'event_sold_out',     isset($_POST['event_sold_out']) ? '1' : '');
}

/**
 * Helper: get upcoming events for homepage widget (max 3, future dates only).
 */
function sk_get_upcoming_events(int $limit = 3): array {
    $today = date('Y-m-d');
    $posts = get_posts([
        'post_type'      => 'sk_event',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => 'meta_value',
        'meta_key'       => 'event_date',
        'order'          => 'ASC',
        'meta_query'     => [['key' => 'event_date', 'value' => $today, 'compare' => '>=', 'type' => 'DATE']],
        'no_found_rows'  => true,
    ]);

    $items = [];
    foreach ($posts as $p) {
        $date_raw = get_post_meta($p->ID, 'event_date', true);
        $items[] = [
            'title'        => get_the_title($p),
            'tag'          => get_post_meta($p->ID, 'event_tag',         true),
            'description'  => get_post_meta($p->ID, 'event_description', true),
            'date'         => $date_raw ? date_i18n('j F Y', strtotime($date_raw)) : '',
            'date_day'     => $date_raw ? date_i18n('j', strtotime($date_raw)) : '',
            'date_month'   => $date_raw ? date_i18n('M', strtotime($date_raw)) : '',
            'time'         => get_post_meta($p->ID, 'event_time',        true),
            'end_time'     => get_post_meta($p->ID, 'event_end_time',    true),
            'format'       => get_post_meta($p->ID, 'event_format',      true) ?: 'inperson',
            'location'     => get_post_meta($p->ID, 'event_location',    true),
            'price'        => get_post_meta($p->ID, 'event_price',       true),
            'reg_url'      => get_post_meta($p->ID, 'event_reg_url',     true) ?: home_url('/#contact'),
            'sold_out'     => (bool) get_post_meta($p->ID, 'event_sold_out', true),
            'img'          => get_the_post_thumbnail_url($p->ID, 'medium') ?: '',
        ];
    }
    return $items;
}


/* ══════════════════════════════════════════════════════════
   ANNOUNCEMENT BANNER — sk_announcement CPT
   Renders a dismissible bar above the header on every page.
   Only the most recently published/active announcement shows.
   ══════════════════════════════════════════════════════════ */

add_action('init', 'sk_register_announcement_cpt', 10);
function sk_register_announcement_cpt(): void {
    register_post_type('sk_announcement', [
        'labels' => [
            'name'          => 'Announcements',
            'singular_name' => 'Announcement',
            'add_new_item'  => 'Add New Announcement',
            'edit_item'     => 'Edit Announcement',
            'menu_name'     => 'Announcements',
        ],
        'public'          => false,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'supports'        => ['title'],
        'rewrite'         => false,
        'capability_type' => 'post',
        'has_archive'     => false,
        'show_in_rest'    => false,
        'menu_icon'       => 'dashicons-megaphone',
    ]);
}

add_action('admin_menu', 'sk_nest_announcement_menu', 100);
function sk_nest_announcement_menu(): void {
    add_submenu_page(
        'sk-settings',
        'Announcements',
        '📢 Announcements',
        'edit_posts',
        'edit.php?post_type=sk_announcement'
    );
}

add_action('add_meta_boxes', 'sk_register_announcement_meta_boxes');
function sk_register_announcement_meta_boxes(): void {
    add_meta_box(
        'sk_announcement_details',
        '📢 Announcement Bar Settings',
        'sk_announcement_meta_box_cb',
        'sk_announcement',
        'normal',
        'high'
    );
}

function sk_announcement_meta_box_cb(WP_Post $post): void {
    wp_nonce_field('sk_announcement_save', 'sk_announcement_nonce');
    $message     = get_post_meta($post->ID, 'ann_message',    true);
    $subtitle    = get_post_meta($post->ID, 'ann_subtitle',   true);
    $cta_text    = get_post_meta($post->ID, 'ann_cta_text',   true);
    $cta_url     = get_post_meta($post->ID, 'ann_cta_url',    true);
    $bg_color    = get_post_meta($post->ID, 'ann_bg_color',   true) ?: '#2c3e2d';
    $text_color  = get_post_meta($post->ID, 'ann_text_color', true) ?: '#f5f0e8';
    $dismissible = (bool) get_post_meta($post->ID, 'ann_dismissible', true);
    $countdown_end = get_post_meta($post->ID, 'ann_countdown_end', true); // ISO datetime string
    ?>
    <p style="font-size:12px;color:#666;margin:0 0 14px">
      The <strong>post title</strong> is your internal label (e.g. "June Retreat Promo"). Set status to <strong>Published</strong> to show the bar; <strong>Draft</strong> to hide it without deleting.
      Only the <strong>most recently published</strong> announcement shows. The bar appears above the nav on every page.
    </p>
    <table class="form-table" style="width:100%">
      <tr><th style="width:180px"><label>Main Message</label></th>
          <td><input type="text" name="ann_message" value="<?php echo esc_attr($message); ?>" style="width:100%" placeholder="e.g. 🔥 Flash Sale: June Retreat Special!" />
          <p class="description" style="margin-top:4px;font-size:11px">Emoji supported. Shown prominently. Keep under 80 characters.</p></td></tr>
      <tr><th><label>Subtitle / Detail</label></th>
          <td><input type="text" name="ann_subtitle" value="<?php echo esc_attr($subtitle); ?>" style="width:100%" placeholder="e.g. Get up to 30% off all retreats. Limited spaces available." />
          <p class="description" style="margin-top:4px;font-size:11px">Optional. Smaller line below the main message.</p></td></tr>
      <tr><th><label>CTA Button Text</label></th>
          <td><input type="text" name="ann_cta_text" value="<?php echo esc_attr($cta_text); ?>" style="width:260px" placeholder="e.g. Book Now (optional)" /></td></tr>
      <tr><th><label>CTA Button URL</label></th>
          <td><input type="url" name="ann_cta_url" value="<?php echo esc_attr($cta_url); ?>" style="width:100%" placeholder="https://… or /#contact" /></td></tr>
      <tr><th><label>Countdown Timer Ends</label></th>
          <td><input type="datetime-local" name="ann_countdown_end" value="<?php echo esc_attr($countdown_end); ?>" style="width:260px" />
          <p class="description" style="margin-top:4px;font-size:11px">Optional. When set, a live countdown timer appears in the bar. Leave blank to hide the timer. The bar hides automatically when the countdown expires.</p></td></tr>
      <tr><td colspan="2"><hr style="margin:8px 0;border:none;border-top:1px solid #f0f0f1"></td></tr>
      <tr><th><label>Background Colour</label></th>
          <td><div style="display:flex;align-items:center;gap:10px">
            <input type="color" name="ann_bg_color" value="<?php echo esc_attr($bg_color); ?>" style="width:48px;height:34px;border:1px solid #ddd;border-radius:4px;cursor:pointer" />
            <input type="text" name="ann_bg_color_hex" value="<?php echo esc_attr($bg_color); ?>" style="width:110px" placeholder="#2c3e2d" oninput="document.querySelector('[name=ann_bg_color]').value=this.value" />
          </div>
          <p class="description" style="margin-top:4px;font-size:11px">Default: dark forest green (#2c3e2d).</p></td></tr>
      <tr><th><label>Text Colour</label></th>
          <td><div style="display:flex;align-items:center;gap:10px">
            <input type="color" name="ann_text_color" value="<?php echo esc_attr($text_color); ?>" style="width:48px;height:34px;border:1px solid #ddd;border-radius:4px;cursor:pointer" />
            <input type="text" name="ann_text_color_hex" value="<?php echo esc_attr($text_color); ?>" style="width:110px" placeholder="#f5f0e8" oninput="document.querySelector('[name=ann_text_color]').value=this.value" />
          </div></td></tr>
      <tr><th><label>Dismissible</label></th>
          <td><label style="display:flex;align-items:center;gap:8px;cursor:pointer">
            <input type="checkbox" name="ann_dismissible" value="1"<?php checked($dismissible); ?>>
            Show a close (×) button so visitors can dismiss the bar for their session</label></td></tr>
    </table>
    <div style="margin-top:16px;padding:12px 16px;border-radius:4px;background:<?php echo esc_attr($bg_color); ?>;color:<?php echo esc_attr($text_color); ?>;font-size:13px;display:flex;align-items:center;justify-content:space-between;gap:12px" id="sk-ann-preview">
      <span id="sk-ann-preview-msg"><?php echo esc_html($message ?: 'Your announcement will appear here'); ?></span>
      <?php if ($cta_text): ?>
      <span style="border:1px solid currentColor;border-radius:3px;padding:3px 10px;white-space:nowrap;font-size:12px"><?php echo esc_html($cta_text); ?></span>
      <?php endif; ?>
    </div>
    <p style="font-size:11px;color:#888;margin-top:6px">Preview above updates on save.</p>
    <?php
}

add_action('save_post_sk_announcement', 'sk_save_announcement_meta');
function sk_save_announcement_meta(int $post_id): void {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['sk_announcement_nonce']) || !wp_verify_nonce($_POST['sk_announcement_nonce'], 'sk_announcement_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, 'ann_message',    sanitize_text_field($_POST['ann_message']    ?? ''));
    update_post_meta($post_id, 'ann_subtitle',   sanitize_text_field($_POST['ann_subtitle']   ?? ''));
    update_post_meta($post_id, 'ann_cta_text',   sanitize_text_field($_POST['ann_cta_text']   ?? ''));
    update_post_meta($post_id, 'ann_cta_url',    esc_url_raw($_POST['ann_cta_url']            ?? ''));
    // Validate hex colours
    $bg = preg_match('/^#[0-9a-fA-F]{3,6}$/', $_POST['ann_bg_color'] ?? '') ? $_POST['ann_bg_color'] : '#2c3e2d';
    $fg = preg_match('/^#[0-9a-fA-F]{3,6}$/', $_POST['ann_text_color'] ?? '') ? $_POST['ann_text_color'] : '#f5f0e8';
    update_post_meta($post_id, 'ann_bg_color',   $bg);
    update_post_meta($post_id, 'ann_text_color', $fg);
    update_post_meta($post_id, 'ann_dismissible', isset($_POST['ann_dismissible']) ? '1' : '');
    // Validate countdown end — must be a valid datetime string (YYYY-MM-DDTHH:MM)
    $countdown_raw = sanitize_text_field($_POST['ann_countdown_end'] ?? '');
    $countdown_end = (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $countdown_raw)) ? $countdown_raw : '';
    update_post_meta($post_id, 'ann_countdown_end', $countdown_end);

    // Bust cached announcement
    delete_transient('sk_active_announcement');
}

// Also bust transient when status changes (draft→publish, publish→trash, etc.)
add_action('transition_post_status', 'sk_bust_announcement_transient_on_status', 10, 3);
function sk_bust_announcement_transient_on_status(string $new, string $old, WP_Post $post): void {
    if ($post->post_type === 'sk_announcement') {
        delete_transient('sk_active_announcement');
    }
}

// Also bust on trash/delete
add_action('before_delete_post', 'sk_bust_announcement_on_delete');
add_action('wp_trash_post',      'sk_bust_announcement_on_delete');
function sk_bust_announcement_on_delete(int $post_id): void {
    if (get_post_type($post_id) === 'sk_announcement') {
        delete_transient('sk_active_announcement');
    }
}

/**
 * Helper: get active announcement (most recently published). Returns null if none.
 * Cached for 5 minutes — busted on save.
 */
function sk_get_active_announcement(): ?array {
    $cached = get_transient('sk_active_announcement');
    if ($cached !== false) return ($cached === [] || $cached === '') ? null : $cached;

    $posts = get_posts([
        'post_type'      => 'sk_announcement',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ]);

    if (empty($posts)) {
        // Cache empty result for only 1 minute so a new announcement appears quickly
        set_transient('sk_active_announcement', [], 1 * MINUTE_IN_SECONDS);
        return null;
    }

    $p   = $posts[0];
    // Only return if message field is actually filled — otherwise banner won't show
    $msg = get_post_meta($p->ID, 'ann_message', true);
    if (empty($msg)) {
        set_transient('sk_active_announcement', [], 1 * MINUTE_IN_SECONDS);
        return null;
    }

    $ann = [
        'message'      => $msg,
        'subtitle'     => get_post_meta($p->ID, 'ann_subtitle',     true),
        'cta_text'     => get_post_meta($p->ID, 'ann_cta_text',     true),
        'cta_url'      => get_post_meta($p->ID, 'ann_cta_url',      true),
        'bg_color'     => get_post_meta($p->ID, 'ann_bg_color',     true) ?: '#2c3e2d',
        'text_color'   => get_post_meta($p->ID, 'ann_text_color',   true) ?: '#f5f0e8',
        'dismissible'  => (bool) get_post_meta($p->ID, 'ann_dismissible',  true),
        'countdown_end'=> get_post_meta($p->ID, 'ann_countdown_end', true),
        'id'           => $p->ID,
    ];

    set_transient('sk_active_announcement', $ann, 5 * MINUTE_IN_SECONDS);
    return $ann;
}


/* ══════════════════════════════════════════════════════════════
   STORIES — sk_story CPT
   Full Gutenberg editor + structured sidebar meta fields.
   Admin: Sacred Kompass → Stories
   ══════════════════════════════════════════════════════════════ */

// Flush rewrite rules once after sk_story CPT is registered (handles existing installs)
add_action( 'wp_loaded', function(): void {
    if ( get_option( 'sk_story_cpt_rewrite_flushed' ) !== '1' ) {
        flush_rewrite_rules( false );
        update_option( 'sk_story_cpt_rewrite_flushed', '1' );
    }
} );

add_action( 'init', 'sk_register_story_cpt', 10 );
function sk_register_story_cpt(): void {
    register_post_type( 'sk_story', [
        'labels' => [
            'name'          => 'Stories',
            'singular_name' => 'Story',
            'add_new_item'  => 'Add New Story',
            'edit_item'     => 'Edit Story',
            'menu_name'     => 'Stories',
        ],
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => false,
        'show_in_rest'    => true,        // Enables Gutenberg
        'supports'        => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
        'rewrite'         => [ 'slug' => 'stories', 'with_front' => false ],
        'capability_type' => 'post',
        'has_archive'     => true,
        'menu_icon'       => 'dashicons-book-alt',
    ] );
}

add_action( 'admin_menu', 'sk_nest_story_menu', 100 );
function sk_nest_story_menu(): void {
    add_submenu_page(
        'sk-settings',
        'Stories',
        '✦ Stories',
        'edit_posts',
        'edit.php?post_type=sk_story'
    );
}

/* ── Structured meta box (sidebar fields alongside Gutenberg) ── */
add_action( 'add_meta_boxes', 'sk_register_story_meta_boxes' );
function sk_register_story_meta_boxes(): void {
    add_meta_box(
        'sk_story_details',
        '✦ Story Details',
        'sk_story_meta_box_cb',
        'sk_story',
        'side',   // sidebar — Gutenberg shows this in the right panel
        'high'
    );
}

function sk_story_meta_box_cb( WP_Post $post ): void {
    wp_nonce_field( 'sk_story_save', 'sk_story_nonce' );
    $pull_quote   = get_post_meta( $post->ID, 'story_pull_quote',      true );
    $category     = get_post_meta( $post->ID, 'story_category',        true );
    $author_name  = get_post_meta( $post->ID, 'story_author_name',     true );
    $author_title = get_post_meta( $post->ID, 'story_author_title',    true );
    $cover_url    = get_post_meta( $post->ID, 'story_cover_image_url', true );
    $cover_id     = (int) get_post_meta( $post->ID, 'story_cover_image_id', true );
    $read_time    = get_post_meta( $post->ID, 'story_read_time',       true );
    $featured     = (bool) get_post_meta( $post->ID, 'story_featured', true );
    $cover_display = $cover_id ? wp_get_attachment_image_url( $cover_id, 'thumbnail' ) : $cover_url;
    ?>
    <p style="font-size:11px;color:#666;margin:0 0 12px">The <strong>title</strong> and <strong>body</strong> are edited in the main Gutenberg area. These fields add structured metadata shown on cards and the stories page.</p>
    <table style="width:100%;border-collapse:collapse">
      <tr style="margin-bottom:10px;display:block">
        <td style="display:block;padding:0 0 4px">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Pull Quote <small style="font-weight:400;color:#888">(shown on cards)</small></label>
          <textarea name="story_pull_quote" rows="3" style="width:100%;font-size:12px;resize:vertical" placeholder="One evocative sentence from the story — shown as excerpt on cards."><?php echo esc_textarea( $pull_quote ); ?></textarea>
        </td>
      </tr>
      <tr style="display:block;margin-bottom:10px">
        <td style="display:block">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Category / Tag</label>
          <input type="text" name="story_category" value="<?php echo esc_attr( $category ); ?>" style="width:100%;font-size:12px" placeholder="e.g. Healing · Grief · Leadership" />
          <p style="font-size:10px;color:#888;margin:3px 0 0">Badge shown on card. Keep brief.</p>
        </td>
      </tr>
      <tr style="display:block;margin-bottom:10px">
        <td style="display:block">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Story By (name)</label>
          <input type="text" name="story_author_name" value="<?php echo esc_attr( $author_name ); ?>" style="width:100%;font-size:12px" placeholder="e.g. Saleheen Akhtar" />
        </td>
      </tr>
      <tr style="display:block;margin-bottom:10px">
        <td style="display:block">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Author Title / Role <small style="font-weight:400;color:#888">(optional)</small></label>
          <input type="text" name="story_author_title" value="<?php echo esc_attr( $author_title ); ?>" style="width:100%;font-size:12px" placeholder="e.g. Founder · Jyotishi" />
        </td>
      </tr>
      <tr style="display:block;margin-bottom:10px">
        <td style="display:block">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Reading Time <small style="font-weight:400;color:#888">(optional)</small></label>
          <input type="text" name="story_read_time" value="<?php echo esc_attr( $read_time ); ?>" style="width:100%;font-size:12px" placeholder="e.g. 5 min read" />
        </td>
      </tr>
      <tr style="display:block;margin-bottom:10px">
        <td style="display:block">
          <label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px">Cover Image</label>
          <div id="sk-story-cover-preview-wrap" style="<?php echo $cover_display ? '' : 'display:none;'; ?>margin-bottom:8px">
            <img id="sk-story-cover-preview" src="<?php echo esc_url( $cover_display ?: '' ); ?>" style="width:100%;height:80px;object-fit:cover;border-radius:4px;border:1px solid #ddd" />
          </div>
          <input type="hidden" name="story_cover_image_id"  id="sk_story_cover_id"  value="<?php echo esc_attr( $cover_id ); ?>" />
          <input type="hidden" name="story_cover_image_url" id="sk_story_cover_url" value="<?php echo esc_attr( $cover_display ?: $cover_url ); ?>" />
          <div style="display:flex;gap:6px">
            <button type="button" id="sk-story-cover-btn"    class="button button-small">Select Image</button>
            <button type="button" id="sk-story-cover-remove" class="button button-small" style="color:#a00;<?php echo $cover_display ? '' : 'display:none;'; ?>">Remove</button>
          </div>
          <script>
          jQuery(function($){
            var frame;
            var $preview = $('#sk-story-cover-preview');
            var $wrap    = $('#sk-story-cover-preview-wrap');
            var $idF     = $('#sk_story_cover_id');
            var $urlF    = $('#sk_story_cover_url');
            var $rmv     = $('#sk-story-cover-remove');
            function setImg(id,url){ $idF.val(id); $urlF.val(url); $preview.attr('src',url); $wrap.show(); $rmv.show(); }
            $('#sk-story-cover-btn').on('click',function(e){
              e.preventDefault();
              if(frame){frame.open();return;}
              frame=wp.media({title:'Select Cover',button:{text:'Use this image'},multiple:false,library:{type:'image'}});
              frame.on('select',function(){ var a=frame.state().get('selection').first().toJSON(); var u=(a.sizes&&a.sizes.medium)?a.sizes.medium.url:a.url; setImg(a.id,u); });
              frame.open();
            });
            $rmv.on('click',function(e){ e.preventDefault(); $idF.val(''); $urlF.val(''); $preview.attr('src',''); $wrap.hide(); $(this).hide(); });
          });
          </script>
        </td>
      </tr>
      <tr style="display:block;margin-bottom:6px">
        <td style="display:block">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:12px;font-weight:600">
            <input type="checkbox" name="story_featured" value="1"<?php checked( $featured ); ?>>
            Feature on homepage (shows in teaser grid)
          </label>
        </td>
      </tr>
    </table>
    <?php
}

add_action( 'save_post_sk_story', 'sk_save_story_meta' );
function sk_save_story_meta( int $post_id ): void {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! isset( $_POST['sk_story_nonce'] ) || ! wp_verify_nonce( $_POST['sk_story_nonce'], 'sk_story_save' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    update_post_meta( $post_id, 'story_pull_quote',      sanitize_textarea_field( $_POST['story_pull_quote']      ?? '' ) );
    update_post_meta( $post_id, 'story_category',        sanitize_text_field(     $_POST['story_category']        ?? '' ) );
    update_post_meta( $post_id, 'story_author_name',     sanitize_text_field(     $_POST['story_author_name']     ?? '' ) );
    update_post_meta( $post_id, 'story_author_title',    sanitize_text_field(     $_POST['story_author_title']    ?? '' ) );
    update_post_meta( $post_id, 'story_read_time',       sanitize_text_field(     $_POST['story_read_time']       ?? '' ) );
    update_post_meta( $post_id, 'story_featured',        isset( $_POST['story_featured'] ) ? '1' : '' );

    $cover_id = (int) ( $_POST['story_cover_image_id'] ?? 0 );
    if ( $cover_id > 0 ) {
        update_post_meta( $post_id, 'story_cover_image_id',  $cover_id );
        $url = wp_get_attachment_image_url( $cover_id, 'medium' ) ?: '';
        update_post_meta( $post_id, 'story_cover_image_url', $url );
    } elseif ( isset( $_POST['story_cover_image_id'] ) && $_POST['story_cover_image_id'] === '' ) {
        update_post_meta( $post_id, 'story_cover_image_id',  '' );
        update_post_meta( $post_id, 'story_cover_image_url', '' );
    } else {
        update_post_meta( $post_id, 'story_cover_image_url', esc_url_raw( $_POST['story_cover_image_url'] ?? '' ) );
    }
}
