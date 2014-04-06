<?php

/*
 * Plugin Name: Disable checking for plugin updates
 * Plugin URI:  https://github.com/pothi/wordpress-mu-plugins
 * Version:     0.1
 * Description: Disable checking for plugin updates while browsing through WP dashboard
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */

remove_action( 'load-plugins.php', 'wp_update_plugins' );
remove_action( 'load-update.php', 'wp_update_plugins' );
remove_action( 'admin_init', '_maybe_update_plugins' );
remove_action( 'wp_update_plugins', 'wp_update_plugins' );
remove_action( 'load-update-core.php', 'wp_update_plugins' );

add_filter( 'pre_transient_update_plugins', '__return_zero' );
add_filter( 'pre_site_transient_update_plugins', '__return_zero' );
