<?php
/*
Plugin Name: SeedProd Coming Soon Pro
Plugin URI: http://www.seedprod.com
Description: The Ultimate Coming Soon & Maintenance Mode Plugin
Version:  3.15.3
Author: SeedProd
Author URI: http://www.seedprod.com
TextDomain: seedprod 
License: GPLv2
Copyright 2012  John Turner (email : john@seedprod.com, twitter : @johnturner)
*/

/**
 * Init
 *
 * @package WordPress
 * @subpackage seedprod-coming-soon-pro
 * @since 0.1.0
 */

/**
 * Plugin Update Checker
 * Courtesy of Yahnis Elsts
 */
require 'plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = PucFactory::buildUpdateChecker(
'http://updates.wppronto.com/wp-update-server/?action=get_metadata&slug=seedprod-coming-soon-pro', //Metadata URL.
__FILE__, //Full path to the main plugin file.
'seedprod-coming-soon-pro' //Plugin slug. Usually it's the same as the name of the directory.
);

/**
 * Default Constants
 */
define( 'SEED_CSP3_SHORTNAME', 'seed_csp3' ); // Used to reference namespace functions.
define( 'SEED_CSP3_FILE', 'seedprod-coming-soon-pro/seedprod-coming-soon-pro.php' ); // Used for settings link.
define( 'SEED_CSP3_TEXTDOMAIN', 'seedprod' ); // Your textdomain
define( 'SEED_CSP3_PLUGIN_NAME', __( 'Coming Soon Pro', 'seedprod' ) ); // Plugin Name shows up on the admin settings screen.
define( 'SEED_CSP3_VERSION', '3.15.3' ); // Plugin Version Number. Recommend you use Semantic Versioning http://semver.org/
define( 'SEED_CSP3_REQUIRED_WP_VERSION', '3.3' ); // Required Version of WordPress
define( 'SEED_CSP3_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); // Example output: /Applications/MAMP/htdocs/wordpress/wp-content/plugins/seed_csp3/
define( 'SEED_CSP3_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // Example output: http://localhost:8888/wordpress/wp-content/plugins/seed_csp3/
define( 'SEED_CSP3_TABLENAME', 'csp3_subscribers' );
define( 'SEED_CSP3_API_URL', 'http://api.sellwp.co/v1/update' );


/**
 * Load Translation
 */
function seed_csp3_load_textdomain() {
    load_plugin_textdomain( 'seedprod', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'seed_csp3_load_textdomain');

/**
 * Upon activation of the plugin, see if we are running the required version and deploy theme in defined.
 *
 * @since 0.1.0
 */
function seed_csp3_activation(){
    if ( version_compare( get_bloginfo( 'version' ), SEED_CSP3_REQUIRED_WP_VERSION, '<' ) ) {
        deactivate_plugins( __FILE__ );
        wp_die( sprintf( __( "WordPress %s and higher required. The plugin has now disabled itself. On a side note why are you running an old version :( Upgrade!", 'seedprod' ), SEED_CSP3_REQUIRED_WP_VERSION ) );
    }

    // Redirect to Settings
    wp_redirect(admin_url('options-general.php?page=seed_csp3'));

}
register_activation_hook( __FILE__, 'seed_csp3_activation' );


/**
 * Load Required Files
 */
require_once( 'framework/framework.php' );
require_once( 'inc/class-plugin.php' );
@include( 'extensions/pro.php' );
require_once( 'inc/config.php' );


/**
* API Updates
*/
$seed_csp3_settings_1 = get_option('seed_csp3_settings_1');
$seed_csp3_api_key = '';
if(isset($seed_csp3_settings_1['api_key'])){
    $seed_csp3_api_key = $seed_csp3_settings_1['api_key'];
}
if(defined('SEED_CSP_API_KEY')){
    $seed_csp3_api_key = SEED_CSP_API_KEY;
}
if(!empty($seed_csp3_api_key)){
    //add_action('init', 'seed_csp3_auto_update');
}
function seed_csp3_auto_update()
{
    global $seed_csp3_api_key;
    require_once 'framework/seedprod-auto-update.php';
    $seed_csp3_plugin_domain = home_url();
    $seed_csp3_plugin_api_key = trim($seed_csp3_api_key);
    $seed_csp3_plugin_current_version = SEED_CSP3_VERSION;
    $seed_csp3_plugin_remote_path = SEED_CSP3_API_URL;
    #TODO Add new slug format in
    $seed_csp3_plugin_slug = plugin_basename(__FILE__);
    new seedprod_auto_update($seed_csp3_plugin_current_version, $seed_csp3_plugin_remote_path, $seed_csp3_plugin_slug, $seed_csp3_plugin_api_key,$seed_csp3_plugin_domain);
}


add_action('init', 'seed_csp3_sellwp_updater');

function seed_csp3_sellwp_updater() {
    global $seed_csp3_api_key;
    include( 'sellwp-updater.php' );
    new SellWP_Updater(
        $seed_csp3_api_key,  
        SEED_CSP3_VERSION,
        plugin_basename(__FILE__)
    );
}

#TODO Implement new real time check

// if(!empty($_GET['seed_check_key']) && $_GET['seed_check_key'] == '1'){
//     $response = wp_remote_post( 'http://api.sellwp.co/v1/update', array(
//         'method' => 'POST',
//         'timeout' => 45,
//         'body' => array(
//             'action' => 'info', 
//             'slug' => plugin_basename(__FILE__),
//             'domain' => home_url(),
//             'api_key' => '63b43e90-68c8-44d5-842a-70aaa97629d6',
//             'installed_version' => SEED_CSP3_VERSION,
//             ),
//         )
//     );

//     if( is_wp_error( $response ) ) {
//        $error_message = $response->get_error_message();
//        echo "Something went wrong: $error_message";
//     } else {
//         $response = unserialize($response['body']);
//         update_option('seedprod-coming-soon-pro',$response->message);
//         var_dump($response);
//     }
// }
