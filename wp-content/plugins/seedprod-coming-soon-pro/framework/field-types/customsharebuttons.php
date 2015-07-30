<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db


//var_dump($options[$id]);
$c = 0;
foreach ( $option_values as $k => $v ) {
	$alt = '';
	
	if($k === 0){
		$alt = "&nbsp;&nbsp;<input class='regular-text' name='{$setting_id}[$id][tweet_text]' type='text' value='" . esc_attr( $options[ $id ]['tweet_text'] ) . "' placeholder='".__('Optional Tweet Text','seedprod')."' />";
	}

	if($k === 1){
		$alt = "&nbsp;&nbsp;<input id='{$id}_facebook_img' placeholder='".__('Image for Facebook Like. Optimal Size: 200px x 200px','seedprod')."' class='regular-text' name='{$setting_id}[$id][facebook_img]' type='text' value='" . esc_attr( $options[ $id ]['facebook_img'] ) . "' />";
		$alt .= "<input id='{$id}_facebook_img_upload_image_button' class='button-secondary upload-button' type='button' value='" . __( 'Media Image Library', 'seedprod' ) . "' />";

		wp_enqueue_script( 'seed_csp3-upload-js', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/upload.js', array(
		    'jquery',
		    'thickbox',
		    'media-upload' 
		) );
	}

	if($k === 4){
		$alt = "&nbsp;&nbsp;<input id='{$id}_pinterest_img' placeholder='".__('Image for Pinterest','seedprod')."' class='regular-text' name='{$setting_id}[$id][pinterest_img]' type='text' value='" . esc_attr( $options[ $id ]['pinterest_img'] ) . "' />";
		$alt .= "<input id='{$id}_pinterest_img_upload_image_button' class='button-secondary upload-button' type='button' value='" . __( 'Media Image Library', 'seedprod' ) . "' />";

		wp_enqueue_script( 'seed_csp3-upload-js', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/upload.js', array(
		    'jquery',
		    'thickbox',
		    'media-upload' 
		) );
	}

	if(is_int($k)){
    echo "<input type='checkbox' name='{$setting_id}[$id][buttons][]' value='$k' " . ( in_array( $k, ( empty( $options[ $id ]['buttons'] ) ? array( ) : $options[ $id ]['buttons'] ) ) ? 'checked' : '' ) . "  /> $v $alt<br/>";
    $c++;
	}
	

}
