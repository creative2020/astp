<?php

/*
 * Plugin Name: Support
 * Description: Adds support menu to admin bar.
 * Author: WPPronto
 * Author URI: http://www.wppronto.com/
 * Version: 1.0
 * License: GPL2+
 */

// Add Zendesk feedback tab JavaScript and CSS

function wpp_custom_code() {
  if ( is_admin_bar_showing() ) {
    ?>

?>

<script type="text/javascript" src="//assets.zendesk.com/external/zenbox/v2.6/zenbox.js"></script>
<style type="text/css" media="screen, projection">
  @import url(//assets.zendesk.com/external/zenbox/v2.6/zenbox.css);
</style>
<script type="text/javascript">
  if (typeof(Zenbox) !== "undefined") {
    Zenbox.init({
      dropboxID:   "20136945",
      url:         "https://wppronto.zendesk.com",
      tabTooltip:  "Support",
      tabImageURL: "https://p4.zdassets.com/external/zenbox/images/tab_support.png",
      tabColor:    "black",
      tabPosition: "Left",
      hide_tab:    "true"
    });
  }
</script>

<?php;
}
}
add_action( 'wp_footer', 'wpp_custom_code' );
add_action( 'admin_footer', 'wpp_custom_code' );

// Add admin bar menu

add_action('admin_bar_menu', 'add_toolbar_items', 100);
function add_toolbar_items($admin_bar){
	$admin_bar->add_menu( array(
		'id'    => 'support-menu',
		'title' => 'Support',
		'href'  => '#',	
		'meta'  => array(
		'title' => __('Support'),
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'support-sub-item-1',
		'parent' => 'support-menu',
		'title' => 'Knowledge Base',
		'href'  => 'http://clients.wppronto.com/knowledgebase.php',
		'meta'  => array(
		'title' => __('Visit the WPPronto knowledge base'),
		'target' => '_blank',
		'class' => 'my_menu_item_class'
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'support-sub-item-2',
		'parent' => 'support-menu',
		'title' => 'Request Help',
		'href'  => 'http://wpprontohelp.zendesk.com/account/dropboxes/20136945',
		'meta'  => array(
		'title' => __('Open a support ticket'),
		'target' => '_blank',
		'onclick' => 'script: Zenbox.show(); return false;',
		'class' => 'my_menu_item_class'
		),
	));
	$admin_bar->add_menu( array(
		'id'    => 'support-sub-item-3',
		'parent' => 'support-menu',
		'title' => 'Client Area Login',
		'href'  => 'https://clients.wppronto.com/clientarea.php',
		'meta'  => array(
		'title' => __('Log in to your client area'),
		'target' => '_blank',
		'class' => 'my_menu_item_class'
		),
	));
}

?>