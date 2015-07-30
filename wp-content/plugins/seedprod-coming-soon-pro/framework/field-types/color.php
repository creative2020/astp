<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

echo "<input id='$id' class='pickcolor-field' type='text' name='{$setting_id}[$id]' value='" . esc_attr( $options[ $id ] ) . "' style='background-color:" . ( empty( $options[ $id ] ) ? $default_value : $options[ $id ] ) . ";' />";

wp_enqueue_script( 'seed_csp3-color-js', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/color.js', array(
     'wp-color-picker' 
),false, true );