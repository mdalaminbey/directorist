<?php
/*This file will contain most common actions that will help other developer extends / modify our plugin settings or design */
function atbdp_after_new_listing_button()
{
    do_action('atbdp_after_new_listing_button');
}


function atbdp_create_picvacyAndTerms_pages(){

    $create_permission = apply_filters('atbdp_create_required_pages', true);
    if ($create_permission) {
        $options = get_option('atbdp_option');
        $page_exists = get_option('atbdp_picvacyAndTerms_pages');
        // $op_name is the page option name in the database.
        // if we do not have the page id assigned in the settings with the given page option name, then create an page
        // and update the option.
        $id = array();
        $Old_terms = get_directorist_option('listing_terms_condition_text');
        $pages = apply_filters('atbdp_create_picvacyAndTerms_pages', array(
            'privacy_policy' => array(
                'title' => __('Privacy Policy', 'directorist'),
                'content' => '<!-- wp:heading -->
<h2>Who we are</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website address is: '.home_url().'.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What personal data we collect and why we collect it</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Comments</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When visitors leave comments on the site we collect the data shown in the registration form.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Media</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Contact forms</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Embedded content from other websites</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics</h3>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Who we share your data with</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>How long we retain your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What rights you have over your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Where we send your data</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Your contact information</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Additional information</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>How we protect your data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What data breach procedures we have in place</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What third parties we receive data from</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What automated decision making and/or profiling we do with user data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Industry regulatory disclosure requirements</h3>
<!-- /wp:heading -->'
            ),
            'terms_conditions' => array(
                'title' => __('Terms and Conditions', 'directorist'),
                'content' => $Old_terms
            ),
        ));
        if (!$page_exists) {
            foreach ($pages as $op_name => $page_settings) {
                $id = wp_insert_post(
                    array(
                        'post_title' => $page_settings['title'],
                        'post_content' => $page_settings['content'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'comment_status' => 'closed'
                    )
                );
                $options[$op_name] = (int)$id;
            }
        }
        // if we have new options then lets update the options with new option values.
        if ($id) {
            update_option('atbdp_picvacyAndTerms_pages', 1);
            update_option('atbdp_option', $options);
        };
    }
}
// fire up the functionality for one time
if (!get_option('atbdp_picvacyAndTerms_pages')){
    add_action('wp_loaded', 'atbdp_create_picvacyAndTerms_pages');
}

function atbdp_handle_attachment($file_handler,$post_id,$set_thu=false) {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $post_id );
    if ( is_numeric( $attach_id ) ) {
        update_post_meta( $post_id, '_atbdp_listing_images', $attach_id );
    }
    return $attach_id;
}

function atbdp_get_preview_button(){
    if (isset($_GET['redirect'])){
        $payment = !empty($_GET['payment'])?$_GET['payment']:'';
        return '<a href="' . esc_url($_GET['redirect']) . '" class="btn btn-success">
                            <span class="' . atbdp_icon_type() . '-edit"></span>' . apply_filters('atbdp_listing_preview_btn_text', !empty($payment)?esc_html__(' Pay & Submit', 'directorist'):esc_html__(' Submit', 'directorist')) . '</a>';
    }
}
