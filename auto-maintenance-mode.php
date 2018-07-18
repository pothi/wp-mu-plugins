<?php
/*
Plugin Name: Auto Maintenance Mode
Version: 1.0.2
Plugin URI: https://github.com/pothi/auto-maintenance-mode
Author: pothi
Author URI: https://www.tinywp.in
Description: A plugin to enable maintenance mode automatically upon lack of activity on development / staging / test sites.
Text Domain: auto-maintenance-mode
Domain Path: /languages
*/

// disable executing this script directly
if(!defined('ABSPATH')) exit;

if(!class_exists('AUTO_MAINTENANCE_MODE'))
{
    class AUTO_MAINTENANCE_MODE
    {
        var $plugin_version = '1.0.3';
        var $plugin_url;
        var $plugin_path;
        function __construct()
        {
            define('AUTO_MAINTENANCE_MODE_VERSION', $this->plugin_version);
            define('AUTO_MAINTENANCE_MODE_SITE_URL',site_url());
            define('AUTO_MAINTENANCE_MODE_URL', $this->plugin_url());
            define('AUTO_MAINTENANCE_MODE_PATH', $this->plugin_path());
            $this->plugin_includes();
        }
        function plugin_includes()
        {
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('template_redirect', array($this, 'amm_template_redirect'));

            // clear transient on logout and create upon login
            add_action('wp_login', array($this, 'amm_create_transient'));
            add_action('init', array($this, 'amm_create_transient'));
            add_action('wp_logout', array($this, 'amm_clear_transient'));
        }
        function amm_create_transient() {
            if( is_user_logged_in() ) {
                if ( false === ( $tmp_value = get_transient( 'amm_is_any_user_logged_in' ) ) ) {
                    $value = true;
                    set_transient( 'amm_is_any_user_logged_in', $value, 60*60 );
                }
            }
        }
        function amm_clear_transient() {
            delete_transient('amm_is_any_user_logged_in');
        }
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('auto-maintenance-mode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }
        function plugin_url()
        {
            if($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
        }
        function plugin_path(){     
            if ( $this->plugin_path ) return $this->plugin_path;        
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }
        function is_valid_page() {
            return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
        }
        function amm_template_redirect()
        {
            if(is_user_logged_in()){
                //do not display maintenance page
                // $this->amm_create_transient_on_login();
            }
            else
            {
                if( !is_admin() && !$this->is_valid_page()){  //show maintenance page
                    if ( false === ( $tmp_value = get_transient( 'amm_is_any_user_logged_in' ) ) ) {
                        $this->load_amm_page();
                    }
                }
            }
        }
        function load_amm_page()
        {
            header('HTTP/1.0 503 Service Unavailable');
?>
<!DOCTYPE html>
<html lang="<?php echo get_bloginfo('language'); ?>">
<head>
    <meta charset="<?php echo get_bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-width=1">
    <title><?php echo $name; ?> &#8250; Auto Maintenance Mode</title>
    <style>
        * { margin: 0;  padding: 0; }
        body { font-family: Georgia, Arial, Helvetica, Sans Serif; font-size: 65.5%; }
        a { color: #08658F; }
        a:hover { color: #0092BF; }
        #header { color: #333; padding: 1.5em; text-align: center; font-size: 1.2em; border-bottom: 1px solid #08658F; }
        #content { font-size: 150%; width:80%; margin:0 auto; padding: 5% 0; text-align: center; }
        #content p { font-size: 1em; padding: .8em 0; }
        h1, h2 { color: #08658F; }
        h1 { font-size: 300%; padding: .5em 0; }
    </style>
</head>
<body>
    <div id="header">
        <h2><?php echo get_bloginfo('name'); ?></h2>
    </div>  
    <div id="content">
        <h1><?php _e('Auto Maintenance Mode', 'auto-maintenance-mode')?></h1>
        <!-- <p><?php echo $link_text;?></p> -->
        <p><?php _e('Maintenance mode is enabled automatically, due to lack of activity from logged-in users!', 'auto-maintenance-mode')?></p>
        <p><?php _e('To disable the maintenance mode, please ', 'auto-maintenance-mode')?><strong><a href="<?php echo wp_login_url()?>"><?php _e('login now', 'auto-maintenance-mode'); ?></a></strong><?php _e(' or visit this page or any page of this site using a browser where you have already logged-in.', 'auto-maintenance-mode')?></p>
        <p><?php _e('Sorry for the inconvenience.', 'auto-maintenance-mode')?></p>
    </div>
</body>
</html>
<?php
            exit();
        }
    }
    $GLOBALS['auto_maintenance_mode'] = new AUTO_MAINTENANCE_MODE();
}
