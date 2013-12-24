<?php

/*
 * Plugin Name: Major Core Auto Updates
 * Plugin URI:  https://github.com/pothi/wordpress-mu-plugins
 * Version:     0.1
 * Description: Automatically update WordPress core, including major versions
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */


add_filter( 'allow_major_auto_core_updates', '__return_true' );
