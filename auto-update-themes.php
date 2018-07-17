<?php

/*
 * Plugin Name: Auto theme updates
 * Plugin URI:  https://github.com/pothi/wordpress-mu-plugins
 * Version:     0.1
 * Description: Automatically update all themes
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */

add_filter( 'auto_update_theme', '__return_true' );
