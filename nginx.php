<?php

/*
 * Plugin Name: Nginx Compatibility
 * Plugin URI:  https://github.com/pothi/wordpress-mu-plugins
 * Version:     0.1
 * Description: To let WP know that Nginx support rewrites.
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */

add_filter( 'got_rewrite', '__return_true' );
