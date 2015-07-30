<?php
// Template Tags
function seed_cs3_title(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);

	$title = '';
	if(!empty($seo_title)){
		$title = esc_html($seo_title);
	} else {
		$blogname = get_bloginfo( 'name', 'display' );
	  	$site_description = get_bloginfo( 'description', 'display' );
	  	$title = $site_description .' - '. $blogname;
	}
	return $title;
}


function seed_cs3_metadescription(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	if(empty($seo_description)){
		$seo_description = get_bloginfo( 'description', 'display' );
	}

	$meta_description = '<meta name="description" content="'.esc_attr($seo_description).'">';

	return $meta_description;
}

function seed_cs3_privacy(){
	$output = '';
	if(get_option('blog_public') == 0){
		$output = "<meta name='robots' content='noindex,nofollow' />";
	}
	return $output;
}

function seed_cs3_favicon(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = '';
	if(!empty($favicon)){
		$output .= "<!-- Favicon -->\n";
		$output .= '<link href="'.esc_attr($favicon).'" rel="shortcut icon" type="image/x-icon" />';
	}
	return $output;
}

function seed_cs3_customcss(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = '';
	if(!empty($custom_css)){
		$output = '<style type="text/css">'.$custom_css.'</style>';
	}
	return $output;
}

function seed_cs3_head(){
	require_once(SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_lessc.inc.php');
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	if($emaillist == 'gravityforms'){
		$enable_wp_head_footer = '1';
	}
	$output = '';

	if(!empty($enable_wp_head_footer)){
		$output .= "<!-- wp_head() -->\n";
		ob_start();
		if($emaillist == 'gravityforms'){
			if(class_exists('RGFormsModel')){
				gravity_form_enqueue_scripts($gravityforms_form_id, false);
			}
		}
		wp_enqueue_script('jquery');  
		wp_head();
		$dump = ob_get_contents();
		ob_end_clean();
		$output .= $dump;
	}

	if(!empty($share_buttons['facebook_img'])){
		$output .= '<meta property="og:image" content="'.esc_url($share_buttons['facebook_img']).'" />'."\n";
	}

	$output .= "<!-- Google Fonts CSS -->\n";
	$output .= $seed_csp3->get_google_font_css($text_font,$headline_font,$button_font);


	$output .= "<!-- Bootstrap and default Style -->\n";
	$output .= '<link rel="stylesheet" href="'.SEED_CSP3_PLUGIN_URL.'themes/default/bootstrap/css/bootstrap.min.css">'."\n";
	if(!empty($enable_responsiveness)){
	$output .= '<link rel="stylesheet" href="'.SEED_CSP3_PLUGIN_URL.'themes/default/bootstrap/css/bootstrap-responsive.min.css">'."\n";
	}
	$output .= '<link rel="stylesheet" href="'.SEED_CSP3_PLUGIN_URL.'themes/default/style.css">'."\n";
	if(is_rtl()){
		$output .= '<link rel="stylesheet" href="'.SEED_CSP3_PLUGIN_URL.'themes/default/rtl.css">'."\n";
	}
	$output .= '<style type="text/css">'."\n";

	$output .= '/* calculated styles */'."\n";
	ob_start();

	$css = "
	@primaryColor: $link_color;
	@secondaryColor: darken(@primaryColor, 15%);
	#gradient {
		.vertical(@startColor: #555, @endColor: #333) {
		    background-color: mix(@startColor, @endColor, 60%);
		    background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
		    background-image: -ms-linear-gradient(top, @startColor, @endColor); // IE10
		    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
		    background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
		    background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
		    background-image: linear-gradient(top, @startColor, @endColor); // The standard
		    background-repeat: repeat-x;
		    filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)\",@startColor,@endColor)); // IE9 and down
		}
	}

	.progress .bar{
		#gradient > .vertical(@primaryColor, @secondaryColor);
	}

	.countdown_section{
		#gradient > .vertical(@primaryColor, @secondaryColor);
	}

	";
	if($progressbar_effect == 'basic'){
		$less = new seed_csp3_lessc();
		$style = $less->parse($css);
		echo $style;
	}

	$css = "
	@primaryColor: $link_color;
	@secondaryColor: darken(@primaryColor, 15%);
	#gradient {
		.vertical(@startColor: #555, @endColor: #333) {
		    background-color: mix(@startColor, @endColor, 60%);
		    background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
		    background-image: -ms-linear-gradient(top, @startColor, @endColor); // IE10
		    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
		    background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
		    background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
		    background-image: linear-gradient(top, @startColor, @endColor); // The standard
		    background-repeat: repeat-x;
		    filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)\",@startColor,@endColor)); // IE9 and down
		}
	}

	.countdown_section{
		#gradient > .vertical(@primaryColor, @secondaryColor);
	}

	";
	if(isset($enable_countdown) && $enable_countdown){
		$less = new seed_csp3_lessc();
		$style = $less->parse($css);
		echo $style;
	}

	$output .= ".progress-striped .bar, .progress.active .bar{background-color:$link_color}";
	?>

	/* Background Style */
    html{
    	height:100%;
		<?php if(!empty($bg_image)): ;?>
			<?php if(isset($bg_cover) && in_array('1', $bg_cover)) : ?>
				background: <?php echo $bg_color;?> url('<?php echo $bg_image; ?>') no-repeat top center fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			<?php else: ?>
				background: <?php echo $bg_color;?> url('<?php echo $bg_image; ?>') <?php echo $bg_repeat;?> <?php echo $bg_position;?> <?php echo $bg_attahcment;?>;
			<?php endif ?>
        <?php else: ?>
        	background: <?php echo $bg_color;?>;
		<?php endif; ?>
    }
    body{	
    	height:100%;
			<?php if(!empty($bg_effect) ) : ?>
				background: transparent url('<?php echo plugins_url('images/bg-'.$bg_effect.'.png',__FILE__) ; ?>') repeat;
			<?php else: ?>
				background: transparent;
			<?php endif; ?>
	}

    /* Text Styles */
    <?php if(!empty($text_font)):?>
	    body{
	        font-family: <?php $seed_csp3->get_font_family($text_font); ?> 
	    }
    <?php endif;?>

    <?php if($headline_font == 'inherit'){$headline_font = $text_font;}?>

    <?php if(!empty($headline_font)):?>
	    h1, h2, h3, h4, h5, h6{
	        font-family: <?php $seed_csp3->get_font_family($headline_font); ?>;
	        <?php if($headline_font[0] != "_") { if($headline_font != 'inherit'){ ?>
	        font-weight:normal;
	    	<?php }} ?>
	    }
    <?php endif;?>

    <?php if($button_font == 'inherit'){$button_font = $headline_font;}?>
    <?php if(!empty($button_font)):?>
	    button{
	        font-family: <?php $seed_csp3->get_font_family($button_font); ?>
	    }
    <?php endif;?>

    <?php if(!empty($text_color)){ ?>
		body{
			color:<?php echo $text_color;?>;
		}
    <?php } ?>

    <?php if(empty($headline_color)){ $headline_color = $link_color; }?>


    <?php if(!empty($headline_color)){ ?>
		h1, h2, h3, h4, h5, h6{
			color:<?php echo $headline_color;?>;
		}
    <?php }?>


    <?php if(!empty($link_color)){ ?>
		a, a:visited, a:hover, a:active{
			color:<?php echo $link_color;?>;
		}
		
		<?php

		$css = "
				.buttonBackground(@startColor, @endColor) {
		  // gradientBar will set the background to a pleasing blend of these, to support IE<=9
		  .gradientBar(@startColor, @endColor);
		  *background-color: @endColor; /* Darken IE7 buttons by default so they stand out more given they won't have borders */
		  .reset-filter();

		  // in these cases the gradient won't cover the background, so we override
		  &:hover, &:active, &.active, &.disabled, &[disabled] {
		    background-color: @endColor;
		    *background-color: darken(@endColor, 5%);
		  }

		  // IE 7 + 8 can't handle box-shadow to show active, so we darken a bit ourselves
		  &:active,
		  &.active {
		    background-color: darken(@endColor, 10%) e(\"\9\");
		  }
		}

		.reset-filter() {
		  filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(enabled = false)\"));
		}

		.gradientBar(@primaryColor, @secondaryColor) {
		  #gradient > .vertical(@primaryColor, @secondaryColor);
		  border-color: @secondaryColor @secondaryColor darken(@secondaryColor, 15%);
		  border-color: rgba(0,0,0,.1) rgba(0,0,0,.1) fadein(rgba(0,0,0,.1), 15%);
		}

		#gradient {
			.vertical(@startColor: #555, @endColor: #333) {
		    background-color: mix(@startColor, @endColor, 60%);
		    background-image: -moz-linear-gradient(top, @startColor, @endColor); // FF 3.6+
		    background-image: -ms-linear-gradient(top, @startColor, @endColor); // IE10
		    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(@startColor), to(@endColor)); // Safari 4+, Chrome 2+
		    background-image: -webkit-linear-gradient(top, @startColor, @endColor); // Safari 5.1+, Chrome 10+
		    background-image: -o-linear-gradient(top, @startColor, @endColor); // Opera 11.10
		    background-image: linear-gradient(top, @startColor, @endColor); // The standard
		    background-repeat: repeat-x;
		    filter: e(%(\"progid:DXImageTransform.Microsoft.gradient(startColorstr='%d', endColorstr='%d', GradientType=0)\",@startColor,@endColor)); // IE9 and down
		  }
		}
		.lightordark (@c) when (lightness(@c) >= 65%) {
			color: black;
			text-shadow: 0 -1px 0 rgba(256, 256, 256, 0.3);
		}
		.lightordark (@c) when (lightness(@c) < 65%) {
			color: white;
			text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
		}
		@btnColor: {$link_color};
		@btnDarkColor: darken(@btnColor, 15%);
		.btn, .gform_button {
		  .lightordark (@btnColor);
		  .buttonBackground(@btnColor, @btnDarkColor);
		}

		#csp3-progressbar span,.countdown_section{
			.lightordark (@btnColor);
		}

		.btn:hover{
		  .lightordark (@btnColor);
		}

		input[type='text']{
			border-color: @btnDarkColor @btnDarkColor darken(@btnDarkColor, 15%);
		}

		@hue: hue(@btnDarkColor);
		@saturation: saturation(@btnDarkColor);
		@lightness: lightness(@btnDarkColor);
		input[type='text']:focus {
			border-color: hsla(@hue, @saturation, @lightness, 0.8);
			webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);
			-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px hsla(@hue, @saturation, @lightness, 0.6);
		    
		}

		";

		$less = new seed_csp3_lessc();
		$style = $less->parse($css);
		echo $style;

		?>
    <?php } 

    if(!empty($text_effect)){
    	foreach($text_effect as $k=>$v){
    		switch($v){
    			case 'inset':
    				$css = "
		    		.lightordarkshadow (@c) when (lightness(@c) >= 65%) {
						text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.8);
					}
					.lightordarkshadow (@c) when (lightness(@c) < 65%) {
						text-shadow: 0 -1px 0 rgba(256, 256, 256, 0.8);
					}
    				@text_color: {$text_color};
    				body{
    					.lightordarkshadow (@text_color);
    				}
    				@headline_color: {$headline_color};
    				h1, h2, h3, h4, h5, h6{
    					.lightordarkshadow (@headline_color);
    				}
    				@link_color: {$link_color};
    				a, a:visited, a:hover, a:active{
    					.lightordarkshadow (@link_color);
    				}
    				";	
		        	$less = new seed_csp3_lessc();
					$style = $less->parse($css);
					echo $style;
    				break;
    		}
    	}

    }


    //Container
    if(!empty($enable_container)){
    	$dropshadow = 0;
    	if(is_array($container_effect['effects']) && in_array('0',$container_effect['effects'])){
    		$dropshadow = 1;
    	}

    	$glow = 0;
    	if(is_array($container_effect['effects']) && in_array('1',$container_effect['effects'])){
    		$glow = 1;
    	}

    	$border = 0;
    	$thickness = 0;
    	$border_color = 0;
    	if(is_array($container_effect['effects']) && in_array('2',$container_effect['effects'])){
    		$border = 1;
    		$thickness = ($container_effect['thickness'] + 1) .'px';
    		if(empty($container_effect['border_color'])){
    			$border_color = ($link_color);
    		}else{
    			$border_color = ($container_effect['border_color']);
    		}
    		
    	}


    	$roundedcorners = 0;
    	$radius = 0;
    	if(is_array($container_effect['effects']) && in_array('3',$container_effect['effects'])){
    		$roundedcorners = 1;
    		$radius = ($container_effect['radius'] + 1) .'px';
    	}

    	$opacity = 0;
    	$level = 0;
    	if(is_array($container_effect['effects']) && in_array('4',$container_effect['effects'])){
    		$opacity = 1;
    		$level = ($container_effect['opacity_level'] * 10) .'%';
    	}


    	$css = "
    	@dropshadow: $dropshadow;
		.dropshadow() when (@dropshadow = 1){
			-moz-box-shadow:    0px 11px 15px -5px rgba(69, 69, 69, 0.8);
			-webkit-box-shadow: 0px 11px 15px -5px rgba(69, 69, 69, 0.8);
			box-shadow: 0px 11px 15px -5px rgba(69, 69, 69, 0.8);
  		}
  		@glow: $glow;
		.glow() when (@glow = 1){
			-moz-box-shadow:    0px 0px 50px 5px $container_color;
			-webkit-box-shadow: 0px 0px 50px 5px $container_color;
			box-shadow: 0px 0px 50px 15px $container_color;
  		}
  		@border: $border;
  		@thickness: $thickness;
		.border() when (@border = 1){
			border: @thickness solid $border_color;
  		}
  		@roundedcorners: $roundedcorners;
  		@radius: $radius;
		.roundedcorners() when (@roundedcorners = 1){
			-webkit-border-radius: $radius;
			border-radius: $radius;
			-moz-background-clip: padding; -webkit-background-clip: padding-box; background-clip: padding-box;
  		}
  		@opacity: $opacity;
  		@level: $level;
		.opacity() when (@opacity = 1){
			background-color: fade($container_color,$level);
  		}
    	#csp3-content{
    		background-color: $container_color;
    		float: $container_position;
    		text-align: $container_position;
    		.dropshadow(); /* dropshadow */
    		.glow(); /* glow */
    		.border(); /* border */
    		.roundedcorners(); /* rounded corners */
    		.opacity(); /* opacity */
		}"; 
    	$less = new seed_csp3_lessc();
		$style = $less->parse($css);
		echo $style;
    }

    if(!empty($enable_responsiveness)){
    	?>

    	 /* Landscape phones and down */
  @media (max-width: 480px) { 
  		#csp3-email{
  			margin-bottom: 10px;
			 -webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
  		}
  		#csp3-email,#csp3-fname,#csp3-lname{
  			width:97% !important;
  		}
  		#csp3-fname{
  			margin-right:0;
  		}
  		#csp3-subscribe-btn{
  			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
  		}
  		.csp3-form-wrapper {
  			text-align:left;
  		}
  		
		#csp3-content {
			padding: 15px;
			margin-top: 20px;
		}
		.input-append{
			display:block !important;
		}
		html{
			height:auto;
	    }

		#csp3-email{
			margin-bottom: 10px;
		}
		#csp3-headline {
			line-height: 46px;
		}
		.countdown_section{
			margin-bottom: 10px;
		}

   }
 
  /* Landscape phone to portrait tablet */
  @media (max-width: 767px) {
		#csp3-fname,
		#csp3-lname{
			box-sizing:inherit;
			width:90%;
		}
		#csp3-email{
			box-sizing:inherit;
			width:90%;
		}

		#csp3-subscribe-btn{
			display:block;
		}

		#csp3-subscribe-btn{
  			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
		}

  		#csp3-email{
  			margin-bottom: 10px;
			 -webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
  		}

		#csp3-fname,#csp3-fname{
  			margin-bottom: 10px
  		}

  		.input-append{
			display:block !important;
		}

		#csp3-email,#csp3-subscribe-btn{
			margin:auto;
		}

		#csp3-email{
			margin-bottom:10px;
		}

  }
 
  /* Portrait tablet to landscape and desktop */
  @media (min-width: 768px) and (max-width: 979px) {  }
 
  /* Large desktop */
  @media (min-width: 1200px) {  }

  <?php
    }

	$dump = ob_get_contents();
	ob_end_clean();
	$output .= $dump;

	if(!empty($bg_video)){
	$output .= 'html{background-color:#000 ;}';
	}

	$output .= '</style>'."\n";

	if(!empty($typekit_id)){
		$output .= "<!-- Typekit -->\n";
		$output .= '<script type="text/javascript" src="//use.typekit.com/'.$typekit_id.'.js"></script>';
		$output .= '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';

	}


	$output .= "<!-- JS -->\n";
	$include_url = includes_url();
	$last = $include_url[strlen($include_url)-1]; 
	if($last != '/'){
		$include_url = $include_url . '/';
	}
	if(empty($enable_wp_head_footer)){
	$output .= '<script src="'.$include_url.'js/jquery/jquery.js"></script>'."\n";
	}
	$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/bootstrap/js/bootstrap.js"></script>'."\n";

	if(!empty($enable_fitvidjs)){		
		$output .= "<!-- FitVid -->\n";
		$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/jquery.fitvids.js"></script>'."\n";
	}

	if(!empty($bg_video)){		
		$output .= "<!-- Background Video -->\n";
		$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/okvideo.js"></script>'."\n";
		$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/video.js"></script>'."\n";
		$output .= '<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>'."\n";
		$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/bigvideo.js"></script>'."\n";
	}
	$output .= "<!-- Scripts -->\n";
	$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/script.js"></script>'."\n";



	if(!empty($header_scripts)){
		$output .= "<!-- Header Scripts -->\n";
		$output .= $header_scripts;
	}

	if(!empty($ga_analytics)){
		$output .= "<!-- Google Analytics -->\n";

		$ga_code = "<script type='text/javascript'>

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '{$ga_analytics}']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>";
		$output .= $ga_code;
	}



  	$output .= "<!-- Modernizr -->\n";
	$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/modernizr.min.js"></script>'."\n";


	return $output;
}

function seed_cs3_footer(){
	global $seed_csp3,$seed_csp3_post_result;
	$is_post = false;
	if(!empty($seed_csp3_post_result['post']) && $seed_csp3_post_result['post'] == 'true' && $seed_csp3_post_result['status'] == '200'){
		$is_post = true;
	}
	$o = $seed_csp3->get_settings();
	extract($o);
	if($emaillist == 'gravityforms'){
		$enable_wp_head_footer = '1';
	}
	$output = '';
	// if(!empty($enable_wp_head_footer)){
	// 	$output .= "<!-- wp_footer() -->\n";
	// 	ob_start();
	// 	wp_footer();
	// 	$dump = ob_get_contents();
	// 	ob_end_clean();
	// 	$output .= $dump;
	// }
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if (is_plugin_active('wpml-string-translation/plugin.php')) {
	if(!empty($display_lang_switcher)){
		ob_start();
		do_action('icl_language_selector');
		$output .= ob_get_clean();
	}
	}

	$output .= "<!-- Belatedpng -->\n";
	$output .= "<!--[if lt IE 7 ]>\n";
	$output .= '<script src="'.SEED_CSP3_PLUGIN_URL.'themes/default/js/dd_belatedpng.js"></script>'."\n";
	$output .= "<script>DD_belatedPNG.fix('img, .png_bg');</script>\n";
	$output .= "<![endif]-->\n";

	$rf_url = "";
	$rf_stats = "";
	if(isset($enable_reflink) && !empty($seed_csp3_post_result['referrer_id'])){
		$rf_url = "<br><br>".__('Your Referral URL is:','seedprod').'<br>'.home_url().'?ref='.$seed_csp3_post_result['referrer_id'].'';
		$rf_stats = '<br><br>'.__('Your Referral Stats','seedprod').'<br>Clicks: '.$seed_csp3_post_result['clicks'].'<br>Subscribers: '.$seed_csp3_post_result['subscribers'];
	}

	$output .= "<!-- Email Form Strings -->\n";
	$output .= "<script>
	var seed_csp3_err_msg={
		'msgdefault':'".esc_attr($txt_2)."',
		'msg600':'".esc_attr($txt_3).$rf_url.$rf_stats."',
		'msg500':'".esc_attr($txt_6)."',
		'msg400':'".esc_attr($txt_4)."',
		'msg200':'".esc_attr($txt_5)."'
	};
	</script>";

	if(!empty($seed_csp3_post_result['post'])){
		$output .= "<script>after_form('".json_encode($seed_csp3_post_result)."');</script>\n";
	}

	// Make sure desc is hidden even on js error
	if($is_post){
		$output .= "<script>jQuery('#csp3-description').hide();</script>\n";
	}


	$output .= "<script>\n";
	if(is_array($container_effect['effects']) && in_array('5',$container_effect['effects'])){
	$output .= 'jQuery(document).ready(function($){$("#csp3-content").fadeIn("slow").css("display","inline-block");';
	}else{
	$output .= 'jQuery(document).ready(function($){$("#csp3-content").show().css("display","inline-block");';	
	}
	if(!empty($enable_fitvidjs)){
		$output .= '$("#csp3-description").fitVids();';
	}


	$fronend = parse_url($_SERVER['HTTP_HOST']);
	$backend = parse_url(admin_url());
	//if(empty($enable_ajax) || $backend['host'] != $fronend['host']){
		$output .= '$("#csp3-form").off();';
	//}
	$output .= "});</script>\n";

	if(empty($bg_slideshow)){
		if(!empty($bg_cover)){
			$output .= '<!--[if lt IE 9]>
			<script>
			jQuery(document).ready(function($){';

		
			$output .= '$.supersized({';
			$output .= "slides:[ {image : '$bg_image'} ]";
			$output .= '});';
		

			$output .= '});
			</script>
			<![endif]-->';
		}
	}

	if(!empty($bg_slideshow)){

		$output .= '<!-- Slideshow -->
		<style>
		 	#supersized{
				display:block;
			}
		</style>
		<script>
		jQuery(document).ready(function($){';

		$output .= '$.supersized({';

		if(!empty($bg_slideshow_slide_speed) && absint($bg_slideshow_slide_speed)){
			$output .= 'slide_interval:'.$bg_slideshow_slide_speed.',';
		}else{
			$output .= 'slide_interval:3000,';
		}
		if(!empty($bg_slideshow_slide_transition) && absint($bg_slideshow_slide_transition)){
			$output .= 'transition:'.$bg_slideshow_slide_transition.',';
		}else{
			$output .= 'transition:1,';
		}
		$output .= 'transition_speed:700,';
		$output .= 'fit_landscape:0,';


		if(!empty($bg_slideshow_randomize)){
			$output .= 'random:1,';
		}

		$output .= "slides:[\n";
		if(!empty($bg_slideshow_images)){
			$imgs = trim($bg_slideshow_images);
			$imgs = explode("\n", $imgs);
			$imgs = array_filter($imgs, 'trim');
			foreach($imgs as $k=>$v){
				if($k !== 0){
				$output .= ",";
				}
				$output .= " {image : '".trim($v)."'}"."\n";
			}
		}

		$output .= "]";
		$output .= '});});';
		$output .= '</script>';

	}

	if(!empty($bg_video)){
		if(!empty($bg_video_url)){
			$adproof = 'false';
			if(!empty($bg_video_adproof)){
				$adproof = 'true';
			}
			$hd = 'false';
			if(!empty($bg_video_hd)){
				$hd = 'true';
			}
			if(pathinfo($bg_video_url, PATHINFO_EXTENSION) == 'mp4'){
				$output .= '
				<script>
				jQuery(document).ready(function($){
				if (Modernizr.touch == false) {
				';

			
				$output .= "var BV = new $.BigVideo();";
				$output .= "BV.init();";
				$output .= "BV.show('".$bg_video_url."',{ambient:true});";
				$output .= "$('#big-video-wrap').show()";

			

				$output .= '
				}
				});
				</script>
				';
			}else{
				$output .= '
				<script>
				jQuery(document).ready(function($){
				if (Modernizr.touch == false) {
				';

			
				$output .= "$.okvideo({ ";
				$output .= "video: '".$bg_video_url."',";
				$output .= "hd: ".$hd.",";
				$output .= "adproof: ".$adproof."";
				$output .= " })";
			

				$output .= '
				}
				});
				</script>
				';
			}
		}
	}


	if(!empty($footer_scripts)){
		$output .= "<!-- Footer Scripts -->\n";
		$output .= $footer_scripts;
	}
	if(!empty($conversion_scripts) && $is_post){
		$output .= "<!-- Conversion Scripts -->\n";
		$output .= $conversion_scripts;
	}

	return $output;

}

function seed_cs3_logo(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = "";
	if(!empty($logo)){
		$output .= "<img id='csp3-image' src='$logo' />";
	}
	return  $output;
}

function seed_cs3_headline(){
	global $seed_csp3,$post;

	// get settings
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = "";
	$dump = "";
	if(!empty($headline)){
		if(!empty($post->post_title)){
			$post->post_title = $headline;
			ob_start();
				the_title();
			$dump = ob_get_contents();
			ob_end_clean();
		}else{
			$dump = $headline;
		}
		$output .= '<h1 id="csp3-headline">'.$dump.'</h1>';
	}
	return  $output;
}

function seed_cs3_description(){
	global $seed_csp3,$post;
	$o = $seed_csp3->get_settings();

	$csp3_description = $o['description'];

	$output = '';
	$dump = '';
	if(!empty($csp3_description)){

		ob_start();
			$content = $csp3_description;
			if(!empty($enable_wp_head_footer)){
				$content = apply_filters('the_content', $content);
				echo $content;
			}else{
				if(isset($GLOBALS['wp_embed']))
					$content = $GLOBALS['wp_embed']->autoembed($content);
				echo do_shortcode(shortcode_unautop(wpautop(convert_chars(wptexturize($content)))));
			}
		$dump = ob_get_contents();
		ob_end_clean();
		$output .= '<div id="csp3-description">'.$dump.'</div>';
	}
	return  $output;
}

function seed_cs3_form(){
	global $seed_csp3,$seed_csp3_post_result;
	$is_post = false;
	if(!empty($seed_csp3_post_result['post']) && $seed_csp3_post_result['post'] == 'true' && $seed_csp3_post_result['status'] == '200'){
		$is_post = true;
	}
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = "";

	$ref = '';
	if(isset($_GET['ref'])){
		$ref = $_GET['ref'];
	}

	// Form
	if($is_post === false){
		if($emaillist == 'feedburner'){
			$output .= '<form class="form-inline" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri='.esc_attr($feedburner_address).'\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\');return true">';
			$output .= '<input type="hidden" value="'.esc_attr($feedburner_addr).'" name="uri"/>';
			$output .= '<input type="hidden" name="loc" value="'.esc_attr($feedburner_loc).'"/>';
			$output .= '<div class="csp3-form-wrapper">';
			$output .= '<div class="input-append"><input id="csp3-email" type="text" name="email" class="input-xlarge" placeholder="'.esc_attr($txt_2).'"/>';
			$output .= '<button id="csp3-subscribe-btn" type="submit" class="btn btn-large action">'.esc_html($txt_1).'</button></div></div>';
			$output .= '</form>';
		}elseif($emaillist == 'gravityforms'){
			if(class_exists('RGFormsModel')){
				ob_start();
				gravity_form($gravityforms_form_id, false, false, false, '', apply_filters('seed_csp3_gf_ajax', false));
				$dump = ob_get_contents();
				ob_end_clean();
				if($gravityforms_enable_thankyou_page){
				$output .= '<div id="csp3-alert" class="alert"></div>';
				}
				$output .= $dump;
			}
		}elseif($emaillist == 'html'){
			$output .=  $html_integration;
		}elseif($emaillist != 'none'){
			$output .= '<form id="csp3-form" class="form-inline" method="post">';
			$output .= '<input id="csp3-ref" name="ref" type="hidden" value="'.$ref.'" />';
			$output .= '<input id="csp3-ajax-url" type="hidden" value="'.admin_url().'admin-ajax.php" />';
			$output .= '<div id="csp3-alert" class="alert"></div>';
			$output .= '<div class="csp3-form-wrapper">';
			if(!empty($fields) && in_array('name',$fields)){
			$output .= '<input id="csp3-fname" name="fname" class="input-medium" type="text" placeholder="'.esc_attr($txt_fname).'"/>';
			$output .= '<input id="csp3-lname" name="lname" class="input-large" type="text" placeholder="'.esc_attr($txt_lname).'"/><span class="sep"></span>';
			}
			$output .= '<div class="input-append"><input id="csp3-email" name="email" class="input-xlarge" type="text" placeholder="'.esc_attr($txt_2).'"/>';
			$output .= '<button id="csp3-subscribe-btn" type="submit" class="btn btn-large action">'.esc_html($txt_1).'</button></div></div>';
			$output .= '</form>';
		}
	}

	// After Form
	if($is_post === true){
	$output .= '<div id="csp3-afterform">';

		
		$output .= '<div id="csp3-thankyoumsg">';
		if(!empty($thankyou_msg)){
			$content = $thankyou_msg;
			if(isset($GLOBALS['wp_embed']))
				$content = $GLOBALS['wp_embed']->autoembed($content);
			$output .= do_shortcode(shortcode_unautop(wpautop(convert_chars(wptexturize($content)))));
		}else{
			$output .= '<p>'.esc_html($txt_5).'</p>';
		}
		$output .= '</div>';

		$ref_link = home_url().'?ref='.$seed_csp3_post_result['ref'];
		if(!empty($enable_reflink)){
			$output .= '<div id="csp3-ref-out" class="well">';
			$output .= $ref_link;
			$output .= '</div>';
		}

		

	$output .= '</div>';

	if(!empty($share_buttons['buttons'])){
	$output .= '<ul id="csp3-sharebuttons" class="unstyled">';
	if(in_array('0',$share_buttons['buttons'])){
		$output .= '<li id="share_twitter"><a id="twitter-tweet-btn" class="twitter-share-button" data-url="'.$ref_link.'" data-text="'.esc_attr($share_buttons['tweet_text']).'" data-count="none">Tweet</a>';
		$output .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>';
	}
	if(in_array('1',$share_buttons['buttons'])){
		$output .= '<li id="share_facebook"><iframe src="//www.facebook.com/plugins/like.php?href='.urlencode($ref_link).'&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35&amp;appId=383341908396413" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe></li>';
	}
	if(in_array('2',$share_buttons['buttons'])){
		$output .= '<li id="share_googleplus"><g:plusone annotation="inline" href="'.$ref_link.'"></g:plusone>';
		$output .=  '<script type="text/javascript">
  (function() {
    var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
    po.src = \'https://apis.google.com/js/plusone.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>';
	}
	if(in_array('3',$share_buttons['buttons'])){
		$output .= '<li id="share_linkedin"><script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>';
		$output .= '<script type="IN/Share" data-url="'.$ref_link.'"></script></li>';
	}
	if(in_array('4',$share_buttons['buttons'])){
		$output .= '<li id="share_pinterest"><a href="http://pinterest.com/pin/create/button/?url='.urlencode($ref_link).'&media='.esc_url($share_buttons['pinterest_img']).'&description='.esc_attr($seo_description).'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>';
		$output .= '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script></li>';
	}
	if(in_array('6',$share_buttons['buttons'])){
		$output .= '<li id="share_stumbledupon"><su:badge layout="3"></su:badge>';
		$output .= '<script type="text/javascript">
  (function() {
    var li = document.createElement(\'script\'); li.type = \'text/javascript\'; li.async = true;
    li.src = \'https://platform.stumbleupon.com/1/widgets.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(li, s);
  })();
</script></li>';
	}
	if(in_array('5',$share_buttons['buttons'])){
		$output .= '<li id="share_tumblr">';
		$output .= '<a href="http://www.tumblr.com/share/link?url='.urlencode($ref_link).'" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url(\'http://platform.tumblr.com/v1/share_1.png\') top left no-repeat transparent;">Share on Tumblr</a><script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script></li>';
	}
	$output .= '</ul>';
	};
	}


	return  $output;
}
			
function seed_cs3_socialprofiles(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);

	$output = "";
	if(!empty($social_profiles)){
		$output .= '<div id="csp3-social-profiles">';
		foreach($social_profiles as $k=>$v){
			$upload_dir = wp_upload_dir();
			if(file_exists($upload_dir['basedir'].'/seedprod-icons/'.strtolower($k).'.png')){
				$path = $upload_dir['baseurl'].'/seedprod-icons/'; 
				$icon_size = '';
			}else{
				$path = SEED_CSP3_PLUGIN_URL.'themes/default/images/icons1/';
				$icon_size = 'width:'.$social_media_icon_size.'px';
			}
			
			if(!empty($v)){
				if($k == 'Email'){
					$output .= '<a href="'.'mailto:'.$v.'" target="_blank"><img style="'.$icon_size.'" src="'.$path.strtolower($k).'.png" /></a>';
				}else{
					$output .= '<a href="'.esc_url($v).'" target="_blank"><img style="'.$icon_size.'" src="'.$path.strtolower($k).'.png" /></a>';
				}
			}
		}
		$output .= '</div>';
	}
	return  $output;
}

function seed_cs3_credit(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);
	$output = "";
	
	if(!empty($footer_credit_img)){
	$output = '<div id="csp3-credit">';
	$output .= '<a target="_blank" href="'.esc_url($footer_credit_link).'"><img src="'.esc_url($footer_credit_img).'" /></a>';
	$output .= '</div>';
	}elseif(!empty($footer_credit_txt)){
	$output = '<div id="csp3-credit">';
	$output .= '<a target="_blank" href="'.esc_url($footer_credit_link).'">'.esc_html($footer_credit_txt).'</a>';
	$output .= '</div>';
	}

	// if(!empty($privacy_policy)){
	// 	if(preg_match('/iubenda/',$privacy_policy)){
	// 		$output .= $privacy_policy;
	// 	}else{
	// 		$output .= '<a href="">'.__('Privacy Policy','seedprod').'</a>'.$privacy_policy;
	// 	}
	// }

	return  $output;
}

function seed_cs3_countdown(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	if(!empty($o['enable_countdown'])){
	$dt = $o['countdown_date'];
	$dt['month'] = $dt['month'] -1;

    $tz = get_option('timezone_string');
    if(empty($tz)){
        $tz = 'GMT';
    }

    date_default_timezone_set($tz);
	$now = new DateTime();
	$seconds = $now->getOffset();
	$offset = floor($seconds/ 3600);


	if(empty($o['countdown_launch'])){
		$o['countdown_launch'] = 'null';
	}else{
		$o['countdown_launch'] = home_url();
	}

	if(empty($o['countdown_days'])){
		$o['countdown_days'] = 'Days';
	}

	if(empty($o['countdown_hours'])){
		$o['countdown_hours'] = 'Hours';
	}

	if(empty($o['countdown_minutes'])){
		$o['countdown_minutes'] = 'Minutes';
	}

	if(empty($o['countdown_seconds'])){
		$o['countdown_seconds'] = 'Seconds';
	}

	if(empty($o['countdown_day'])){
		$o['countdown_day'] = 'Day';
	}

	if(empty($o['countdown_hour'])){
		$o['countdown_hour'] = 'Hour';
	}

	if(empty($o['countdown_minute'])){
		$o['countdown_minute'] = 'Minute';
	}

	if(empty($o['countdown_second'])){
		$o['countdown_second'] = 'Second';
	}

	if(empty($o['countdown_format'])){
		$o['countdown_format'] = 'dHMS';
	}

	$output ="<script>
	jQuery(document).ready(function($){
		var endDate = new Date();
		endDate= new Date('".$dt['year']."', '".$dt['month']."', '".$dt['day']."', '".$dt['hour']."', '".$dt['minute']."', '00');
		$('#csp3-countdown').countdown({
			labels: ['Years', 'Months', 'Weeks', '".$o['countdown_days']."', '".$o['countdown_hours']."', '".$o['countdown_minutes']."', '".$o['countdown_seconds']."'],
			labels1: ['Years', 'Months', 'Weeks', '".$o['countdown_day']."', '".$o['countdown_hour']."', '".$o['countdown_minute']."', '".$o['countdown_second']."'],
			until: endDate,
			timezone:".$offset.",
			expiryUrl: '".$o['countdown_launch']."',
			format: '".$o['countdown_format']."'
		});

	});
	</script>";
	$output .= '<div id="csp3-countdown"></div>';
	return $output;
	}
}

function seed_cs3_progressbar(){
	global $seed_csp3;
	$o = $seed_csp3->get_settings();
	extract($o);

	$class = '';
	if($progressbar_effect == 'striped'){
		$class = 'progress-striped';
	}elseif($progressbar_effect == 'animated'){
		$class = 'progress-striped active';
	}


	if(empty($progressbar_percentage)){
		if(empty($progressbar_date_range['start_year']) || empty($progressbar_date_range['start_month']) || empty($progressbar_date_range['start_day']) || empty($progressbar_date_range['end_year']) || empty($progressbar_date_range['end_month']) || empty($progressbar_date_range['end_day'])){
		}else{
			$start_date = strtotime($progressbar_date_range['start_year'].'-'.$progressbar_date_range['start_month'].'-'.$progressbar_date_range['start_day']);
			$end_date = strtotime($progressbar_date_range['end_year'].'-'.$progressbar_date_range['end_month'].'-'.$progressbar_date_range['end_day']);
			$today = time();
			$diff = abs($end_date - $start_date); // 8
			$complete = abs($start_date - $today); //4
			$progressbar_percentage = ($complete/$diff) * 100;

			if($progressbar_percentage > 100){
			 	$progressbar_percentage = '100';
			}elseif($progressbar_percentage < 0){
				$progressbar_percentage = '0';
			}

			$progressbar_percentage = round($progressbar_percentage);

		}
	}

	$output = "";
	if(!empty($enable_progressbar)){
		$output .= '<div id="csp3-progressbar">';
		$output .= '<div class="progress '.$class.'">';
		$output .= '<div class="bar" style="width: '.$progressbar_percentage.'%;"><span>'.$progressbar_percentage.'%</span></div>';
		$output .= '</div>';
		$output .= '</div>';
	}

	return  $output;
}
		