<?php
/*
 * Plugin Name: Maintenance Mode
 * Plugin URI: https://www.nosegraze.com
 * Description: Displays a coming soon page for anyone who's not logged in.
 * Version: 1.0
 * Author: Nose Graze
 * Author URI: https://www.nosegraze.com
 * License: GPL2
 *
 * @package maintenance-mode
 * @copyright Copyright (c) 2015, Ashley Evans
 * @license GPL2+
 */

/**
 * Maintenance Page
 *
 * Displays the coming soon page for anyone who's not logged in.
 * The login page gets excluded so that you can login if necessary.
 *
 * @return void
 */
function ng_maintenance_mode()
{
    global $pagenow;
    if (
        $pagenow !== 'wp-login.php' &&
        !current_user_can('manage_options') &&
        !is_admin()
    ) {
        header('Content-Type: text/html; charset=utf-8');
        if (
            file_exists(plugin_dir_path(__FILE__) . 'templates/maintenance.php')
        ) {
            require_once plugin_dir_path(__FILE__) .
                'templates/maintenance.php';
        }
        die();
    }
}

add_action('wp_loaded', 'ng_maintenance_mode');
