<?php
/*
Plugin Name: Only CMS
Description: Only CMS is a simple plugin to disable the WordPress front end, redirecting to the admin panel when accessing the home page. It's useful for projects where WordPress is used as a headless CMS.
Version: 1.0
Author: Kilimanjjjaro
Author URI: https://kilimanjjjaro.com
*/

// Load languages files
function load_languagues() {
    load_plugin_textdomain('only-cms', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'load_languagues');

// Add a configuration option to the admin panel
function settings_page() {
    add_options_page(__('Only CMS Settings', 'only-cms'), __('Only CMS', 'only-cms'), 'manage_options', 'only-cms', 'settings_page_content');
}
add_action("admin_menu", "settings_page");

// Add content to the configuration page
function settings_page_content() {
?>
    <div class="wrap">
        <h1><?php _e('Only CMS Settings', 'only-cms'); ?></h1>
        <p><?php _e('Enable to disable the WordPress frontend.', 'only-cms'); ?></p>
        <form method="post" action="options.php">
            <?php
    settings_fields("section");
    do_settings_sections("only-cms");
    submit_button();
?>
        </form>
    </div>
    <?php
}

// Add a checkbox to the configuration page
function checkbox_field() {
?>
    <input type="checkbox" name="only_cms_value" value="1" <?php checked(1, get_option('only_cms_value'), true); ?> />
    <?php
}

// Add configuration fields to the admin panel
function settings() {
    add_settings_section("section", null, null, "only-cms");
    add_settings_field("only_cms_value", __("Enable", "only-cms"), "checkbox_field", "only-cms", "section");
    register_setting("section", "only_cms_value"); // Fixed: Changed "redirect" to "only_cms_value"
}
add_action("admin_init", "settings");

// Perform the redirect if the option is enabled
function redirect() {
    if (get_option('only_cms_value') == 1 && !is_admin()) { // Fixed: Changed "redirect" to "only_cms"
        wp_redirect(admin_url());
        exit;
    }
}
add_action('template_redirect', 'redirect');
