<?php
/*
 * Plugin Name: Auto update everything in a WordPress site
 * Plugin URI:  https://github.com/pothi/wp-mu-plugins
 * Version:     0.2
 * Description: Automatically update WP core (including major versions), themes, and plugins
 * Author:      Pothi Kalimuthu
 * Author URI:  https://tinywp.com
 * License:     GPL
 */

add_filter( 'auto_update_theme', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'allow_major_auto_core_updates', '__return_true' );
