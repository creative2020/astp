<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db


$range1 = array(0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1);
$range110 = array(1,2,3,4,5,6,7,8,9,10);
$range1100 = array(1,2,3,4,5,6,7,8,9,10,29,30,40,50,60,70,80,90,100);

$c = 0;
if(!is_array($options[ $id ])){
	$options[ $id ] = array();
}
foreach ( $option_values as $k => $v ) {
	$alt = '';
	
	if($k === 2){
		ob_start();
		echo ": ".__('Width','seedprod')." <select class='" . ( empty( $class ) ? '' : $class ) . "' name='{$setting_id}[$id][thickness]'>";
		foreach ( $range110 as $k0 => $v0 ) {
			if(is_array($v)){
				echo '<optgroup label="'.ucwords($k0).'">';
				foreach ( $v as $k1=>$v1 ) {
					echo "<option value='$k1' " . selected( $options[ $id ]['thickness'], $k1, false ) . ">$v1</option>";
				}
				echo '</optgroup>';
			}else{
		    	echo "<option value='$k0' " . selected( $options[ $id ]['thickness'], $k0, false ) . ">$v0</option>";
			}
		}
		echo "</select>px&nbsp;&nbsp;".__('Color','seedprod').": ";
		echo "<input type='text' class='pickcolor-field' name='{$setting_id}[$id][border_color]' value='" . esc_attr( $options[ $id ]['border_color'] ) . "' style='background-color:" . ( empty( $options[ $id ]['border_color'] ) ? $default_value : $options[ $id ]['border_color'] ) . ";' />";

wp_enqueue_script( 'seed_csp3-color-js', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/color.js', array(
     'wp-color-picker' 
),false, true );
		$alt = ob_get_contents();
		ob_end_clean();
	}
	if($k === 3){
		ob_start();
		echo ": ".__('Radius','seedprod')." <select class='" . ( empty( $class ) ? '' : $class ) . "' name='{$setting_id}[$id][radius]'>";
		foreach ( $range1100 as $k0 => $v0 ) {
			if(is_array($v)){
				echo '<optgroup label="'.ucwords($k0).'">';
				foreach ( $v as $k1=>$v1 ) {
					echo "<option value='$k1' " . selected( $options[ $id ]['radius'], $k1, false ) . ">$v1</option>";
				}
				echo '</optgroup>';
			}else{
		    	echo "<option value='$k0' " . selected( $options[ $id ]['radius'], $k0, false ) . ">$v0</option>";
			}
		}
		echo "</select>px";
		$alt = ob_get_contents();
		ob_end_clean();
	}
	if($k === 4){
		ob_start();
		echo "<select class='" . ( empty( $class ) ? '' : $class ) . "' name='{$setting_id}[$id][opacity_level]'>";
		foreach ( $range1 as $k0 => $v0 ) {
			if(is_array($v)){
				echo '<optgroup label="'.ucwords($k0).'">';
				foreach ( $v as $k1=>$v1 ) {
					echo "<option value='$k1' " . selected( $options[ $id ]['opacity_level'], $k1, false ) . ">$v1</option>";
				}
				echo '</optgroup>';
			}else{
		    	echo "<option value='$k0' " . selected( $options[ $id ]['opacity_level'], $k0, false ) . ">$v0</option>";
			}
		}
		echo "</select>";
		$alt = ob_get_contents();
		ob_end_clean();
	}

    echo "<input type='checkbox' name='{$setting_id}[$id][effects][]' value='$k' " . ( in_array( $k, ( empty( $options[ $id ]['effects'] ) ? array( ) : $options[ $id ]['effects'] ) ) ? 'checked' : '' ) . "  /> $v $alt<br/>";
    $c++;
}