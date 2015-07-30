<?php


function seed_csp3_pro_email_list_providers($providers) {
	$pro_providers = array(
		'aweber' => 'Aweber',
		'campaignmonitor' => 'Campaign Monitor',
		'constantcontact' => 'Constant Contact',
		'getresponse' => 'Get Response',
		'gravityforms' => 'Gravity Forms',
		'icontact' => 'iContact',
		'infusionsoft' => 'Infusionsoft',
		'madmini' => 'Mad Mimi',
		'mailchimp' => 'MailChimp',
		'sendy' => 'Sendy',
		'wysija' => 'Wysija',
		'html' => 'HTML Web Form',
	);
 
	$providers = array_merge($providers,$pro_providers);
 
	return $providers;
}
add_filter('seed_csp3_email_list_providers', 'seed_csp3_pro_email_list_providers');

function seed_csp3_pro_fonts($fonts) {
	$pro_fonts = maybe_unserialize(get_transient('seed_csp3_pro_fonts'));
	if(empty($pro_fonts)){
		$response = file_get_contents('fonts.php',true);

		foreach(unserialize($response) as $v){
		 $google_fonts[$v['css-name']] = $v['font-name'];
		 $google_fonts_families[$v['css-name']] = str_replace('font-family: ', '', $v['font-family']);
		}
		update_option('seed_csp3_google_font_families',$google_fonts_families);
		asort($google_fonts);

		$pro_fonts= array('Google Fonts' => $google_fonts);
		set_transient('seed_csp3_pro_fonts',serialize( $pro_fonts ),604800);
	}
	
	if(is_array($pro_fonts)){
		$fonts = array_merge($fonts,$pro_fonts);
	}
 	
 
	return $fonts;
}
add_filter('seed_csp3_fonts', 'seed_csp3_pro_fonts');

function seed_csp3_get_gravityforms_forms(){
	if(class_exists('RGFormsModel')){
	  $forms = array();
	  $gforms = RGFormsModel::get_forms(null, "title");
	  foreach($gforms as $k=>$v){
	  	$forms[$v->id] = $v->title;
	  }
	}else{
	  $forms = array('-1'=> 'No Forms Found');
	}
	return $forms;
}

function seed_csp3_get_wysija_lists(){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(is_plugin_active('wysija-newsletters/index.php')){
	  	//get the lists and ids
		global $wpdb;
		$wlists = array();
        $tablename = $wpdb->prefix . 'wysija_list';
        if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") == $tablename ){
        	$sql = "SELECT list_id,name FROM $tablename WHERE is_enabled = 1";
	    	$wlists = $wpdb->get_results($sql);
        }
		  
		$lists = array();
		
		foreach($wlists as $k=>$v){
		  	$lists[$v->list_id] = $v->name;
		}
	}else{
	  $lists = array('-1'=> 'No Lists Found');
	}
	return $lists;
}

add_action('gform_after_submission', 'after_gravity_subscribed_record_record_into_csp3', 11, 2);

function after_gravity_subscribed_record_record_into_csp3($entry, $form) {
    global $seed_csp3;
    $o = $seed_csp3->get_settings();
    $status = $o['status'];
    if(((!empty($status) && $status === '1') || (!empty($status) && $status === '2')) || (isset($_GET['cs_preview']) && $_GET['cs_preview'] == 'true')){
	if($form['id'] == $o['gravityforms_form_id']){
	    if($o['gravityforms_enable_thankyou_page']){
	    	$data = array();
	    	foreach($form['fields'] as $k => $v){
	    		if($v['type'] == 'name'){
	    			if(!empty($entry[$v['id'].'.3']))
	    				$data['fname'] = $entry[$v['id'].'.3'];
	    			if(!empty($entry[$v['id'].'.6']))
	    			$data['lname'] = $entry[$v['id'].'.6'];
	    		}
	    		if($v['type'] == 'email'){
	    			if(!empty($entry[$v['id']]))
	    				$data['email'] = $entry[$v['id']];
	    		}
	    	}

	    	if(!empty($data)){
	    		$data['gf'] = '1';
	    	}


		    $url = $entry['source_url'];

			$query = http_build_query($data);

		    $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
		    $url .= $separator . $query;

		    wp_redirect($url);
		    exit();
		}
	}
	}
}
