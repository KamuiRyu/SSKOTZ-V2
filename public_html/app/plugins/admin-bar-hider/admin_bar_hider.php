<?php
/**
 * Plugin Name: Admin Bar Hider
 * Description: Very simple and easy to use plugin for hiding admin bar from front end.
 * Version: 1.0.0
 * Author: Dev02ali
 * Author URI: https://dev02ali.wordpress.com/
 * Author Email: dev02ali@gmail.com
 * License: GPLv2
 */
?>
<?php
    if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
      }
     add_filter('show_admin_bar', '__return_false');
 ?>