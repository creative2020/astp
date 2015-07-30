<?php
/*
Author: 2020 Creative
URL: htp://2020creative.com
*/
//////////////////////////////////////////////////////// 2020 Functions
define( 'TEMPPATH', get_stylesheet_directory_uri());
define( 'IMAGES', TEMPPATH. "/imgages");

// Plugins
//require_once ('plugins/advanced-custom-fields/acf.php');
//require_once ('plugins/acf-options-page/acf-options-page.php');
//require_once ('plugins/github-updater-2.8.1/github-updater.php'); //version 2.8.1 added 2014-10-15
require_once ('plugins/wp_bootstrap_navwalker.php'); // used for bootstrap nav menus

// Shortcodes
require_once ('tt-shortcodes.php');

// CPT's
//require_once ('tt-cpt.php');

// Custom fields
// require_once ('tt-acf-fields.php');

// Add theme support for featured images
add_theme_support( 'post-thumbnails' );

// Add feature image sizes
add_image_size( square, 400, 400, true );

// Add theme support for shortcodes inside widgets
add_filter( 'widget_text', 'do_shortcode');

// Add built in post formats
if (function_exists('add_theme_support')) {
    add_theme_support( 'post-formats', array( 'aside', 'link', 'quote', 'gallery' ) );
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////// Add boostrap from CDN

if( !function_exists("tt_bootstrap_cdn") ) {  
    function tt_bootstrap_cdn() { 
        // parent theme
        wp_register_style( 'tt-boot', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css', array(), '1.0', 'all' );
        wp_enqueue_style( 'tt-boot' );
        
        wp_register_script( 'tt-boot-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array('tt-jq2') );
        wp_enqueue_script( 'tt-boot-js' );
        
        wp_register_style( 'tt-boot-fontawesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array(), '1.0', 'all' );
        wp_enqueue_style( 'tt-boot-fontawesome' );
        
        wp_register_style( 'theme-style', get_stylesheet_directory_uri() . '/style.css', array(), '1.0', 'all' );
        wp_enqueue_style( 'theme-style' );

        wp_register_script( 'tt-jq2', 'http://code.jquery.com/jquery-1.9.1.min.js', array() );
        wp_enqueue_script( 'tt-jq2' );
        
        // child themes
        // wp_register_style( 'tt-child', get_stylesheet_directory_uri() . '/tt-child.css', array(), '1.0', 'all' );
        // wp_enqueue_style( 'tt-child' );
    }
}
add_action( 'wp_enqueue_scripts', 'tt_bootstrap_cdn' );

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////// CSS Enqueue Styles

if( !function_exists("tt_theme_styles") ) {  
    function tt_theme_styles() { 
        // parent theme
        wp_register_style( 'tt-main', get_template_directory_uri() . '/tt-lib/css/tt-main.css', array('tt-boot'), '1.0', 'all' );
        wp_enqueue_style( 'tt-main' );
        wp_register_style( 'tt-forms', get_template_directory_uri() . '/tt-lib/css/gf-formsmain-2020.css', array('tt-main'), '1.0', 'all' );
        wp_enqueue_style( 'tt-forms' );

        // child themes
        // wp_register_style( 'tt-child', get_stylesheet_directory_uri() . '/tt-child.css', array(), '1.0', 'all' );
        // wp_enqueue_style( 'tt-child' );
    }
}
add_action( 'wp_enqueue_scripts', 'tt_theme_styles' );
////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////// TT Admin

// Custom Backend Footer
//add_filter('admin_footer_text', 'tt_custom_admin_footer');
function tt_custom_admin_footer() {
	echo '<span id="footer-thankyou">Developed by <a href="http://2020creative.com" target="_blank">2020creative.com</a></span>.';
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////// Menus

register_nav_menus( array(
	'tt_main' => 'TT Main',
	
) );

/*
 * Sidebars
 */

// Main sidebar

$args = array(
	'name'          => 'TT Sidebar',
	'id'            => 'tt_sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>');
register_sidebar( $args );

// Secondary sidebar

$args = array(
	'name'          => 'TT Lower Sidebar',
	'id'            => 'tt_lower_sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>');
register_sidebar( $args );

// That gray area

register_sidebar([
	'name'          => 'TT Graybar 1',
	'id'            => 'tt_graybar_1',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
]);

register_sidebar([
	'name'          => 'TT Graybar 2',
	'id'            => 'tt_graybar_2',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
]);

register_sidebar([
	'name'          => 'TT Graybar 3',
	'id'            => 'tt_graybar_3',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
]);

register_sidebar([
	'name'          => 'TT Graybar 4',
	'id'            => 'tt_graybar_4',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
]);

// Headline

$args = array(
	'name'          => 'TT Headlines',
	'id'            => 'tt_headlines',
	'before_widget' => '<div id="%1$s" class="col-sm-3 widget %2$s">',
	'after_widget'  => '</div>');
register_sidebar( $args );


//////////////////////////////////////////////////////////////////////////////////////////////////////////////// disable admin area

function tt_disable_admin_bar() { 
	if( ! current_user_can('edit_dashboard') )
		add_filter('show_admin_bar', '__return_false');	
}
//add_action( 'after_setup_theme', 'tt_disable_admin_bar' );
 

function tt_redirect_admin(){
	if ( ! current_user_can( 'edit_dashboard' ) ){
		wp_redirect( site_url() . '/' );
		exit;		
	}
}
//add_action( 'admin_init', 'tt_redirect_admin' );

////////////////////////////////////////////////////////

function tt_print_acf() {
    
    //$user_meta = get_user_meta(1);
    //print_r($user_meta);
}
//add_action('admin_print_footer_scripts', 'tt_print_acf');


//////////////////////////////////////////////////////////////////////////////////////////////////////////////// 


// add categories for attachments
function add_categories_for_attachments() {
    register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init' , 'add_categories_for_attachments' );

/*
 * like get_the_category_list, but exlude specified categories
 */

function tt_the_category_list($cats) {
    echo '<ul class="post-categories">';
    foreach((get_the_category()) as $cat) {
        if (in_array($cat->slug, $cats)) continue;
        $cat_link = get_category_link(get_cat_ID($cat->name));
        echo '<li><a href="'.$cat_link.'">'.$cat->name.'</a></li>';
    }
    echo '</ul>';
}
