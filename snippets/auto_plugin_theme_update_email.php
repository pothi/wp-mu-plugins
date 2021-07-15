<?php

// Ref: https://make.wordpress.org/core/2020/07/30/controlling-plugin-and-theme-auto-update-email-notifications-and-site-health-infos-in-wp-5-5/

function tinywp_auto_plugin_theme_update_email( $email, $type, $successful_updates, $failed_updates ) {
    // You may change the email recipient. Default is site admin email.
    // $email['to'] = 'yourname@example.com';
    // You may change the email subject when updates failed
    if ( 'fail' === $type ) {
        $email['subject'] = __( 'ATTN: IT Department – SOME AUTO-UPDATES WENT WRONG!', 'my-plugin' );
    }

    return $email;
}
add_filter( 'auto_plugin_theme_update_email', 'tinywp_auto_plugin_theme_update_email', 10, 4 );
