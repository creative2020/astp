<?php
/*
Author: 2020 Creative
URL: htp://2020creative.com
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////// 2020 Shortcodes


//////////////////////////////////////////////////////// TT Post Content

add_shortcode( 'post_info', 'post_info' );
function post_info ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => '',
            'id' => '',
		), $atts )
	);
    
    $tt_post_content = get_post_field( 'post_content', $id );
    
// code
return $tt_post_content;    
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT Button

// [hsr_btn size="lg" link="#" color="#003764" fcolor="#ffffff" float="none" target="" class=""]Button Name[/hsr_btn], homes_for_sale_btn

add_shortcode( 'tt_btn', 'tt_btn' );
function tt_btn($atts, $content = null) {
    extract(shortcode_atts(array(
        'size'   => 'lg',
        'color'  => '#ee1d24', //#003764
        'fcolor'  => '#ffffff', //#ffffff
        'link'    => '#',
        'float'    => 'none',
        'target'    => '',
        'class' => '',
        'block' => 'n',
        'margin' => '1.0em',
    ), $atts ) );
    
    $classes = 'btn btn-default ' . $class . ' btn-' . $size;
    
    if ($block == 'y') {
    	$classes .= ' btn-block';
    }

    return '<a type="button" class="' . $classes . '" href="' . $link . '" style="' . $margin . ';padding:0.75em 2.0em;background:' . $color . ';color:'. $fcolor . ';float:' . $float . ';" target="' . $target . '">' . $content . '</a>';
}

//////////////////////////////////////////////////////// TT rule

add_shortcode( 'tt_rule', 'tt_rule' ); //line
function tt_rule($atts, $content = null) {
    extract(shortcode_atts(array(
        'size'   => '1px',
        'color'  => '#ccc',
        'classes'  => 'col-sm-12 rule',
        'id' => '',
        'top' => 'n',
    ), $atts ) );

    if ($top == 'n') {
    
    return '<div id="' . $id . '" class="' . $classes . '" style="margin:1.0em 0;border-top:' . $size . ' solid ' . $color .';padding:1.0em 0;"></div>';
    
    } else {
        
        // nothing
    }
     
    if ($top == 'y') {
    
    return '<div id="' . $id . '" class="' . $classes . '" style="border-top:' . $size . ' solid ' . $color .';padding:1.0em 0;"> <a href="#top" class="top"><i class="fa fa-arrow-circle-up pull-right"></i></a></div>';
        
    } else {
        
        // nothing
    }
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT spacer

add_shortcode( 'tt_spacer', 'tt_spacer' ); //line
function tt_spacer($atts, $content = null) {
    extract(shortcode_atts(array(
        'size'   => '1.0em',
        'classes'  => 'col-sm-12',
    ), $atts ) );

    return '<div class="' . $classes . '" style="height:'.$size.';"></div>';
        
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT Post Feed

add_shortcode( 'tt_posts', 'tt_posts' ); // echo do_shortcode('[tt_posts limit="-1" cat_name="home"]');
function tt_posts ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => 'post',
            'cat' => '-1',
            'cat_name' => '',
            'limit' => '10',
            'type' => 'post',
		), $atts )
	);
    
/////////////////////////////////////// Variables
$user_ID = get_current_user_id();
$user_data = get_user_meta( $user_ID );
$user_photo_id = $user_data[photo][0];
$user_photo_url = wp_get_attachment_url( $user_photo_id );
$user_photo_img = '<img src="' . $user_photo_url . '">';

/////////////////////////////////////// All Query    
if ($name == 'post') {
	// The Query
$args = array(
	'post_type' => $type,
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => $limit,
    'cat' => $cat,
    //'category_name' => $cat_name,
);
$the_query = new WP_Query( $args );
} else { 
	//nothing
	}
    
global $post;

// pre loop
//$output = '<ul>';    

// The Loop
if ( $the_query->have_posts() ) {
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		// pull meta for each post
		$post_id = get_the_ID();
		$permalink = get_permalink( $id );
        $post = get_post();
        $size = '250,125';
        $image = get_the_post_thumbnail( $post_id, $size, $attr );
        if (empty( $image )) {
            $image = '<img src="/wp-content/themes/math/images/img-fpo.png">';
        }
         
		
//HTML
            
    if ($cat_name == 'company' ) {
        
    //get section html
    ob_start();
        get_template_part('content', 'company');
        $output .= ob_get_contents();
    ob_end_clean();

} else {
    //nothing
}
}    // after loop
    //$output .= '</ul>';
    
/* Restore original Post Data */
wp_reset_postdata();
return $output;
}}


////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT People

add_shortcode( 'tt_people', 'tt_people' ); 
// echo do_shortcode('[tt_people name="all"]');
function tt_people ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => 'all',
            'cat' => '-1',
            'cat_name' => '',
            'limit' => '-1',
            'type' => 'people',
		), $atts )
	);
    
    $args = array(
	'post_type' => $type,
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => $limit,
    'cat' => $cat,
    //'category_name' => $cat_name,
);
//pre-loop    
$output = '';
$output .= '<div class="row people-main-wrapper">'; //main-wrap    
    
$people_query = new WP_Query($args);
    
//loop
    if ($people_query->have_posts()) :


    while ($people_query->have_posts()) : $people_query->the_post();
    
    // pull meta for each post
        $post_id = get_the_ID();
        $post_thumbnail_id = get_post_thumbnail_id( $post_id );
        $post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'medium' );
        $post_thumbnail_url_tn = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
        $permalink = get_permalink( $id );
    
    //Variables inside loop
        $sig_id = get_post_meta( $post_id, 'people_sig' );
        $people_quote = get_post_meta( $post_id, 'people_quote' );
        $sig_img = wp_get_attachment_image_src( $sig_id[0], 'medium' );
        $people_title = get_post_meta( $post_id, 'people_title' );
    
        $image = get_the_post_thumbnail( $post_id, 'medium' );
            if (empty( $image )) {
                $image = '<img src="/wp-content/themes/ue/images/img-fpo.png">';
            }
    
    //get section html
    ob_start();
        include(get_template_directory().'/content-people.php');
        $output .= ob_get_contents();
    ob_end_clean();
    

endwhile;
    //post-loop
$output .= '</div>'; //main-wrap
endif;
wp_reset_postdata(); 

return $output;
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT Map

add_shortcode( 'tt_map', 'tt_map' ); 
function tt_map ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'address' => '11184 Antioch, Overland Park, KS 66210',
            
		), $atts )
	);
    $output = '<iframe width="400" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA7FtA_cGN7ahKzvUYjAULTF_YMYRDxfrA &q='.$address.'></iframe>';
    
    return $output;
}
//////////////////////////////////////////////////////// TT Forms table

add_shortcode( 'tt_form', 'tt_form' ); 
function tt_form ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => 'Form name',
            'link' => '#',
            'target' => '',
            'row' => '',
            'desc' => 'description',
            'file' => 'file-o',
            
		), $atts )
	);
    // classes
    if ( $row == 'header' ) {
        $style = 'margin-top:0;border-top:1px solid #cccccc;padding:0.5em 0;background:#cccccc;';
        $col2 = $desc;
        } 
    else if ( $row == 'last' ) {
        $style = 'margin-top:0;border-top:1px solid #cccccc;padding:0.5em 0;border-bottom:1px solid #cccccc;margin-top:-1.3em;';
        $col2 = '<a class="btn btn-primary" href="'.$link.'"><i class="fa fa-download"></i> Download</a>';
        }
    else if ( $row == 'first' ) {
        $style = 'margin-top:0;border-top:0px solid #cccccc;padding:0.5em 0;border-bottom:0px solid #cccccc;margin-top:-1.3em;';
        $col2 = '<a class="btn btn-primary" href="'.$link.'"><i class="fa fa-download"></i> Download</a>';
        }
    else {
        $style = 'margin-top:0;border-top:1px solid #cccccc;padding:0.5em 0;margin-top:-1.3em;';
        $col2 = '<a class="btn btn-primary" href="'.$link.'"><i class="fa fa-download"></i> Download</a>';
    }
    
    
        
    //get section html
    ob_start(); ?>
        <div class="row hover" style="<?php echo $style ?>">
            <div class="col-sm-8" style="font-size:1.1em;margin-top:0.5em;">
                 <i class="fa fa-<?php echo $file ?>" style="color:#802528;width:2.0em;"></i><?php echo $name ?>
            </div>
            <div class="col-sm-4 pull-right">
                 <?php echo $col2 ?>
            </div>
        </div>
        <?php
        $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}
//////////////////////////////////////////////////////// TT Estimate list

add_shortcode( 'tt_estimates', 'tt_estimates' ); 
// echo do_shortcode('[tt_people name="all"]');
function tt_estimates ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => 'all',
            'cat' => '-1',
            'cat_name' => '',
            'limit' => '-1',
            'type' => 'estimate',
            
		), $atts )
	);
    
    $args = array(
	'post_type' => $type,
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => $limit,
    'cat' => $cat,
    //'category_name' => $cat_name,
);
//pre-loop    
$output = '';
$output .= '<div class="row estimate-main-wrapper">'; //main-wrap    
//get section html
    ob_start(); ?>
        <div class="row estimate-header hidden-xs" style="padding:1.0em 0;background:#cccccc;">
            <div class="col-sm-2">Date</div>
            <div class="col-sm-4">Project</div>
            <div class="col-sm-2">Location</div>
            <div class="col-sm-2">Budget</div>
            <div class="col-sm-2">Contact</div>
        </div>
    <?php
        $output .= ob_get_contents();
    ob_end_clean();
    
$estimate_query = new WP_Query($args);
    
//loop
    if ($estimate_query->have_posts()) :


    while ($estimate_query->have_posts()) : $estimate_query->the_post();
    
    
    
    //get section html
    ob_start();
        include(get_template_directory().'/content-estimate.php');
        $output .= ob_get_contents();
    ob_end_clean();
    

endwhile;
    //post-loop
$output .= '</div>'; //main-wrap
endif;
wp_reset_postdata(); 

return $output;
}

////////////////////////////////////////////////////////
  
//////////////////////////////////////////////////////// TT Section

add_shortcode( 'tt_section', 'tt_section' );
function tt_section ( $atts, $content = null ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'class' => '',
            'id' => '',
		), $atts )
	);
    
    return '<div id="'.$id.'" class="'.$class.'">'.$content.'</div>';
       
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// TT Section

add_shortcode( 'tt_section_row', 'tt_section_row' );
function tt_section_row ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => '',
            'id' => '',
		), $atts )
	);
    
    if (name == 'start') {
    	return '<div id="'.$id.'" class="row">';
    	}
    if (name == 'end') {
    	return '</div>';
    	}	
       
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Estimating

add_shortcode( 'tt_estimating_admin', 'tt_estimating_admin' );
function tt_estimating_admin ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => '',
            'id' => '',
		), $atts )
	);
    
    $current_user = wp_get_current_user();
    $current_user_data = get_userdata( $current_user->ID );
    $sub = get_user_meta($current_user->ID, 'ue_sub', true);
    
    if ($current_user->user_login == 'estimating' || $current_user->user_login == '2020' ) {
	    
	    return '<div><a class="btn btn-primary" href="/estimating">New Estimate</a></div></br>';
	    
    } else { 
	    //nothing 
	    }
    
    if ( $sub == 'sub' ) {
	    
	    return '<div><a class="btn btn-primary" href="/standard-forms">Download Forms</a></div></br>';
	    
	} else {
	
	//nothing	
	}
       
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Login check

add_shortcode( 'tt_login_check', 'tt_login_check' );
function tt_login_check ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'allow_user' => '',
            'allow_role' => '',
            'allow_meta' => '',
            'allow_meta_key' => '',
		), $atts )
	);
    
    $current_user = wp_get_current_user();
    $current_user_data = get_userdata( $current_user->ID );
    $sub = get_user_meta($current_user->ID, 'ue_sub');
    $user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);

    
	if ( is_user_logged_in() ) { 
		
		return '<p>you are logged in as '.$current_user_data->user_login.'</p>';	
		
		} else {
		
		return '<p>You must be logged in to view this content, please <a class="btn btn-small" href="/login">login</a> here.</p>';
		
	}
   
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Login content

add_shortcode( 'tt_login_content', 'tt_login_content' );
function tt_login_content ( $atts, $content = null ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'allow_user' => '',
            'allow_role' => '',
            'allow_meta' => '',
            'allow_meta_key' => '',
            'logged_in' => '',
            'logged_out' => '',
		), $atts )
	);
    
    $current_user = wp_get_current_user();
    $current_user_data = get_userdata( $current_user->ID );
    $sub = get_user_meta($current_user->ID, 'ue_sub', true);
    $user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	$user_meta_custom = get_user_meta($current_user->ID, $allow_meta_key, true);
	
	if ( is_user_logged_in() ) { 
		
		$tt_logged_in = 'y';	
		
		} else {
		
		$tt_logged_in = 'n';
		
	}    
    
	if ( $allow_role == $user_role || $allow_meta == $user_meta_custom || $allow_user == $current_user_data->user_login ) { 
		
		return '<p>'.$content.'</p>';	
		
		

	} elseif ( $logged_out == 'show' && $tt_logged_in == 'n' )  {
		
		return '<p>'.$content.'</p>';	
		
		
		
	} elseif ( $logged_in == 'show' && $tt_logged_in == 'y' )  {
		
		return '<p>'.$content.'</p>';	
		
		
	} else {
		//nothing
	}
		
		

       
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Login content

add_shortcode( 'tt_sub_reg', 'tt_sub_reg' );
function tt_sub_reg ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => '',
            
		), $atts )
	);
    
    $current_user = wp_get_current_user();
    $current_user_data = get_userdata( $current_user->ID );
    $sub = get_user_meta($current_user->ID, 'ue_sub', true);
    $user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	$user_meta_custom = get_user_meta($current_user->ID, $allow_meta_key, true);
	
	if ( $sub == 'sub' || $current_user_data->user_login == '2020' || $current_user_data->user_login == 'estimating' || $user_role == 'administrator' ) { 
		
		// opportunities go here
		return do_shortcode('[tt_estimates]');
		
		
	} else {
		
// 		return 'Please <a class="btn btn-sm" href="/user-login">login</a> as a subcontractor to view the open opportunities. If you do not have a login please <a class="btn btn-sm" href="/registration/subcontractor/">register</a> here.';
		
		
		
	}
		
		

       
}

////////////////////////////////////////////////////////

//////////////////////////////////////////////////////// Login content

add_shortcode( 'tt_login_form', 'tt_login_form' );
function tt_login_form ( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'name' => '',
            
		), $atts )
	);
	
	if ( is_user_logged_in() ) { 
		
			
		
		} else {
			
		$pass_link = wp_lostpassword_url( get_permalink() );	
		
		return wp_login_form( array( 'echo' => false ) ).
		
		'<a class="btn btn-sm" href="'.$pass_link.'" title="Lost Password">Forgot Password?</a>';
		
	} 
	
	
	
}
    
    
////////////////////////////////////////////////////////


  
  
  