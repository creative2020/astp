<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
// if(empty($options[ $id ])){
// 	$options[ $id ] = '';
// }
// $seed_update_msg = get_option('seedprod-coming-soon-pro_update_msg');
// if(!empty($seed_update_msg)){
// 	echo $seed_update_msg.'<br>';
// }
$ajax_url = html_entity_decode(wp_nonce_url('admin-ajax.php?action=seed_csp3_check_license','seed_csp3_check_license'));

echo "<input autocomplete='off' id='$id' class='" . ( empty( $class ) ? 'regular-text' : $class ) . "' name='{$setting_id}[$id]' type='password' value='" . esc_attr( $options[ $id ] ) . "' />";
echo "<button id='seed_csp3_check_license' type='button' class='button-secondary'>".__('Check License','seedprod')."</button><br>";
$msg = get_option('seedprod-coming-soon-pro_update_msg');
echo "<div id='seed_csp3_check_license_msg'>".$msg."</div>";
?>
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('#seed_csp3_check_license').click(function() {
    	$('#seed_csp3_check_license').prop("disabled", true);
      $('#seed_csp3_check_license_msg').hide();
    	apikey = $('#api_key').val();
    	if(apikey != ''){
        $.get('<?php echo $ajax_url; ?>&apikey='+apikey, function(data) {
          var response = $.parseJSON(data);
          $('#seed_csp3_check_license_msg').text(response.message);
          //console.log(response.message);
          $('#seed_csp3_check_license_msg').fadeIn();
          $('#seed_csp3_check_license').prop("disabled", false);
        });
		}else{
      $('#seed_csp3_check_license_msg').show();
			$('#seed_csp3_check_license').prop("disabled", false);
		}

    }); 
});
</script>