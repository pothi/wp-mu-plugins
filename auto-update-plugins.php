<?php

/*
 * Plugin Name: Auto plugin updates
 * Plugin URI:  https://github.com/pothi/wordpress-mu-plugins
 * Version:     0.1
 * Description: Automatically update all plugins
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */


add_filter( 'auto_update_plugin', '__return_true' );
