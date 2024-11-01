<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   WP Sticky Social
 * @author    Vladislav Musilek
 * @license   GPL-2.0+
 * @copyright 2013 Vladislav Musilek
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// We dont uninstal function, option is delete by deactivate plugin 