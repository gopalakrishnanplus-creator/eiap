<?php
/**
 * Plugin Name: URL Field Remover From Comment Box
 * Author: WP Tracer
 * Author URI: https://wptracer.com/
 * Description: This plugin helps remove the URL field parameter from comment form in every WordPress theme easily.
 * Plugin URI: https://wptracer.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version: 3.1.1
 * Text Domain: url-field-remover-from-comment-box
 */

// Register the settings and set the default value
add_action('admin_init', 'wptracer_cfr_register_settings');
function wptracer_cfr_register_settings() {
    register_setting('wptracer_cfr_settings', 'wptracer_cfr_enable', array(
        'default' => 1
    ));
}

// Add the options page under "Tools"
add_action('admin_menu', 'wptracer_cfr_add_options_page');
function wptracer_cfr_add_options_page() {
    add_submenu_page(
        'tools.php',            // Parent slug (Tools menu)
        'URL Remover',          // Page title
        'URL Remover',          // Menu title
        'manage_options',       // Capability
        'wptracer-url-remover', // Menu slug
        'wptracer_cfr_menu_page'// Function to display the page content
    );
}

// Define the options page content
function wptracer_cfr_menu_page() {
    $enable = get_option('wptracer_cfr_enable');
    ?>
    <div class="wrap">
        <h1>URL Field Remover From Comment Box</h1>

        <form method="post" action="options.php">
            <?php settings_fields('wptracer_cfr_settings'); ?>

            <h2 class="title">Enable/Disable URL Field Remover</h2>
            <label>
                <input type="checkbox" name="wptracer_cfr_enable" value="1" <?php checked($enable, 1); ?>>
                Enable URL Field Remover
            </label>

            <?php submit_button(); ?>
        </form>

        <hr>

        <h2>Thanks for Installing URL Field Remover From Comment Box</h2>
        <p>Sit tight and relax, you do not have to do anything after installing the plugin. The plugin does everything for you.</p>
        <p>If you loved my work feel free to rate my plugin <a href="https://wordpress.org/plugins/url-field-remover-from-comment-box/" target="_blank">From Here.</a></p>
        <p>Donate me so that I can build more plugins for Free: <a href="https://www.buymeacoffee.com/bimalrajpaudel" target="_blank">DONATE.</a></p>
        <p>Get free WordPress beginner resources: <a href="https://wptracer.com/" target="_blank">WP Tracer.</a></p>
    </div>
    <?php
}

// Add the filter to remove the URL field
add_action('after_setup_theme', 'wptracer_cfr_add_comment_url_filter');
function wptracer_cfr_add_comment_url_filter() {
    $enable = get_option('wptracer_cfr_enable');
    if ($enable) {
        add_filter('comment_form_default_fields', 'wptracer_cfr_disable_comment_url', 20);
    }
}

// Define the filter callback
function wptracer_cfr_disable_comment_url($fields) {
    unset($fields['url']);
    return $fields;
}
