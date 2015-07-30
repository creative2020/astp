<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
$ajax_url = html_entity_decode(wp_nonce_url('admin-ajax.php?action=seed_csp3_campaingmonitor_client','seed_csp3_campaingmonitor_client'));
if(empty($option_values)){
  $option_values = array();
}

echo "<select id='$id' class='" . ( empty( $class ) ? '' : $class ) . "' name='{$setting_id}[$id]'>";
foreach ( $option_values as $k => $v ) {
	if(is_array($v)){
		echo '<optgroup label="'.ucwords($k).'">';
		foreach ( $v as $k1=>$v1 ) {
			echo "<option value='$k1' " . selected( $options[ $id ], $k1, false ) . ">$v1</option>";
		}
		echo '</optgroup>';
	}else{
    	echo "<option value='$k' " . selected( $options[ $id ], $k, false ) . ">$v</option>";
	}
}
echo "</select>";

echo "<button id='seed_csp3_campaingmonitor_refresh_clients' type='button' class='button-secondary'>".__('Refresh List','seedprod')."</button>"
?>
<script type='text/javascript'>
jQuery(document).ready(function($) {
    $('#seed_csp3_campaingmonitor_refresh_clients').click(function() {
    	$('#seed_csp3_campaingmonitor_refresh_clients').attr("disabled", "disabled");
        apikey = $('#campaignmonitor_api_key').val();
        $.get('<?php echo $ajax_url; ?>&apikey='+apikey, function(data) {
          lists = $.parseJSON(data);
          if(lists){
              $('#campaignmonitor_clientid').html('');
          }
          $.each(lists,function(k,v){
              $('#campaignmonitor_clientid').prepend('<option value=\"'+k+'\">'+v+'</option>');
          });
          $('#seed_csp3_campaingmonitor_refresh_clients').html('Lists Refreshed');
        });
    }); 
});
</script>