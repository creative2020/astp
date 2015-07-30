<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$ajax_url = html_entity_decode(wp_nonce_url('admin-ajax.php?action=seed_csp3_aweber_auth','seed_csp3_aweber_auth'));

echo "<input id='$id' class='" . ( empty( $class ) ? 'regular-text' : $class ) . "' name='{$setting_id}[$id]' type='text' value='" . esc_attr( $options[ $id ] ) . "' /><br>";

echo "<button id='seed_csp3_aweber_save_auth_code' type='button' class='button-secondary'>".__('Save','seedprod')."</button>"
?>
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('#seed_csp3_aweber_save_auth_code').click(function() {
        auth_code = $('#aweber_authorization_code').val();
        $.get('<?php echo $ajax_url; ?>&auth_code='+ encodeURIComponent(auth_code), function(data) {
            if(data == '200'){
            	$('#seed_csp3_aweber_refresh').trigger('click');
            }
        });
    });
});
</script>