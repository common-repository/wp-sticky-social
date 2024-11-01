<?php
/**
 *
 * Plugin Name:       WP Sticky Social
 * Plugin URI:        http://musilda.cz/wp-sticky-social/
 * Description:       Add sticky bar with social icons
 * Version:           1.0.2
 * Author:            Vladislav Musilek
 * Author URI:        http://musilda.cz        
 * Text Domain:       wp-sticky-social
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 * @package   WP Sticky Social
 * @author    Vladislav Musilek
 * @license   GPL-2.0+
 * @copyright 2023 Vladislav Musilek
 *  
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-sticky-social.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WP_Sticky_Social', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Sticky_Social', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WP_Sticky_Social', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-sticky-social-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_Sticky_Social_Admin', 'get_instance' ) );

}
