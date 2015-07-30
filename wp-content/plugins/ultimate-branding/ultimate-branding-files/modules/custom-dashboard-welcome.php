<?php
/*
Plugin Name: Hide Dashboard Welcome
Plugin URI:
Description: Hides the dashboard welcome message
Author: Barry (Incsub), Sam Najian (Incsub)
Version: 1.1
Author URI:
Network: true

Copyright 2012 Incsub (email: admin@incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/**
 * Remove from page options
 */
add_filter('user_has_cap', "ub_remove_dashboard_welcome_from_page_options", 10, 4);
function ub_remove_dashboard_welcome_from_page_options($allcaps, $caps, $args, $wp_user){
    global $current_screen;
    if( isset( $current_screen ) && "dashboard" === $current_screen->id ){
        $allcaps['edit_theme_options'] = false;
    }
    return $allcaps;
}

/**
 * Remove from dashboard
 */
function ub_remove_dashboard_welcome( $value , $object_id, $meta_key , $single ) {
    global $wp_version;
    if( version_compare($wp_version, "3.5", ">=") ){
        remove_action( 'welcome_panel', 'wp_welcome_panel' );
        return $value;
    }else{
        if($meta_key == 'show_welcome_panel') {
            return false;
        }
    }
    return $value;
}

add_filter( "get_user_metadata", 'ub_remove_dashboard_welcome' , 10, 4 );

function ub_dashboard_welcome_manage_output() {

	?>

	<div class="postbox">
		<h3 class="hndle" style='cursor:auto;'><span><?php _e('Hide Dashboard Welcome','ub'); ?></span></h3>
		<div class="inside">
				<p class='description'><?php _e( 'The Dashboard Welcome message is now hidden.', 'ub' ); ?>

		</div>
	</div>

<?php
}

add_action('ultimatebranding_settings_menu_widgets','ub_dashboard_welcome_manage_output');