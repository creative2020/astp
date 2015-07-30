// This is place for js you need to execute on the settings page. This file will only load on the menu pages you've define in the config file.
//  #seed_csp3_section_1_11 - FeedBurner 
//  #seed_csp3_section_1_13 - Aweber 
//  #seed_csp3_section_1_14 - Campagin Monitor 
//  #seed_csp3_section_1_15 - Constant Contact 
//  #seed_csp3_section_1_16 - Gravity Forms 
//  #seed_csp3_section_1_17 - MailChimp 

jQuery(document).ready(function($){

	//Hide Name Field elements
    $("#seed_csp3_section_1_30 table tr:eq(1)").hide();

	//Show Hide Integrations
	$('#emaillist').change(function() {
		seed_csp3_showhide_integrations($(this).attr('value'));
	});

	seed_csp3_showhide_integrations($('#emaillist').attr('value'));

	//Show Progress Settings
	seed_csp3_showhide_progressbar_fields();
	$('.enable_progressbar').click(function () {
		seed_csp3_showhide_progressbar_fields();
	});

	//Show Countdown Settings
	seed_csp3_showhide_countdown_fields();
	$('.enable_countdown').click(function () {
		seed_csp3_showhide_countdown_fields();
	});

	//Show Background Slideshow Settings
	seed_csp3_showhide_slideshow_fields();
	$('.bg_slideshow').click(function () {
		seed_csp3_showhide_slideshow_fields();
	});

	//Show Background Video Settings
	seed_csp3_showhide_bg_video_fields();
	$('.bg_video').click(function () {
		seed_csp3_showhide_bg_video_fields();
	});

	$('#seed-csp3-social-profiles img').css('width',$(".social_media_icon_size:checked").val()+'px');

	//Set icons sizes
	$('.social_media_icon_size').change(function() {
  		$('#seed-csp3-social-profiles img').css('width',$(this).val()+'px');
	});

	// Background Settings
	seed_csp3_showhide_bg_fields();
	$('.bg_cover').click(function () {
		seed_csp3_showhide_bg_fields();
	});

	// Container Settings
	seed_csp3_showhide_container_fields();
	$('.enable_container').click(function () {
		seed_csp3_showhide_container_fields();
	});

	// Enable Placeholder for non cool browsers
	$('input, textarea').placeholder();

	


});

function seed_csp3_showhide_container_fields(){
	    if(jQuery('.enable_container').prop("checked")){
    		jQuery("#seed_csp3_section_2_3 table tr:eq(1)").show();
    		jQuery("#seed_csp3_section_2_3 table tr:eq(2)").show();
    		jQuery("#seed_csp3_section_2_3 table tr:eq(3)").show();
    	}else{
    		jQuery("#seed_csp3_section_2_3 table tr:eq(1)").hide();
    		jQuery("#seed_csp3_section_2_3 table tr:eq(2)").hide();
    		jQuery("#seed_csp3_section_2_3 table tr:eq(3)").hide();
    	}
}

function seed_csp3_showhide_bg_fields(){
	    if(jQuery('.bg_cover').prop("checked")){
    		jQuery("#seed_csp3_section_2_1 table tr:eq(3)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(4)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(5)").hide();
    	}else{
    		jQuery("#seed_csp3_section_2_1 table tr:eq(3)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(4)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(5)").show();
    	}
}

function seed_csp3_showhide_progressbar_fields(){
	    if(jQuery('.enable_progressbar').prop("checked")){
    		jQuery("#seed_csp3_section_1_20 table tr:eq(1)").show();
    		jQuery("#seed_csp3_section_1_20 table tr:eq(2)").show();
    		jQuery("#seed_csp3_section_1_20 table tr:eq(3)").show();
    	}else{
    		jQuery("#seed_csp3_section_1_20 table tr:eq(1)").hide();
    		jQuery("#seed_csp3_section_1_20 table tr:eq(2)").hide();
    		jQuery("#seed_csp3_section_1_20 table tr:eq(3)").hide();
    	}
}

function seed_csp3_showhide_slideshow_fields(){
	    if(jQuery('.bg_slideshow').prop("checked")){
    		jQuery("#seed_csp3_section_2_1 table tr:eq(8)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(9)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(10)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(11)").show();
    	}else{
    		jQuery("#seed_csp3_section_2_1 table tr:eq(8)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(9)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(10)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(11)").hide();
    	}
}

function seed_csp3_showhide_bg_video_fields(){
	    if(jQuery('.bg_video').prop("checked")){
    		jQuery("#seed_csp3_section_2_1 table tr:eq(16)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(14)").show();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(15)").show();
    	}else{
    		jQuery("#seed_csp3_section_2_1 table tr:eq(16)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(14)").hide();
    		jQuery("#seed_csp3_section_2_1 table tr:eq(15)").hide();
    	}
}

function seed_csp3_showhide_countdown_fields(){
	    if(jQuery('.enable_countdown').prop("checked")){
    		jQuery("#seed_csp3_section_1_21 table tr:eq(1)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(2)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(3)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(4)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(5)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(6)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(7)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(8)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(9)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(10)").show();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(11)").show()
    	}else{
    		jQuery("#seed_csp3_section_1_21 table tr:eq(1)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(2)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(3)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(4)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(5)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(6)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(7)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(8)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(9)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(10)").hide();
    		jQuery("#seed_csp3_section_1_21 table tr:eq(11)").hide()
    	}
}




function seed_csp3_showhide_integrations(val){
		jQuery('#seed_csp3_section_1_87,#seed_csp3_section_1_86,#seed_csp3_section_1_85,#seed_csp3_section_1_76,#seed_csp3_section_1_80,#seed_csp3_section_1_50,#seed_csp3_section_1_10,#seed_csp3_section_1_11, #seed_csp3_section_1_13, #seed_csp3_section_1_14, #seed_csp3_section_1_15,#seed_csp3_section_1_26, #seed_csp3_section_1_16, #seed_csp3_section_1_17').hide();
		switch(val){
			case 'none':
			  jQuery('#seed_csp3_section_1_87,#seed_csp3_section_1_86,#seed_csp3_section_1_85,#seed_csp3_section_1_76,#seed_csp3_section_1_80,#seed_csp3_section_1_50,#seed_csp3_section_1_10,#seed_csp3_section_1_11, #seed_csp3_section_1_13, #seed_csp3_section_1_14, #seed_csp3_section_1_15,#seed_csp3_section_1_26, #seed_csp3_section_1_16, #seed_csp3_section_1_17').hide();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").hide();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'database':
				jQuery('#seed_csp3_section_1_10').fadeIn();
				jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
				jQuery(".emaillist_desc").hide();
			  break;
			case 'feedburner':
			  jQuery('#seed_csp3_section_1_11').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").hide();
			  jQuery("#feedburner_desc").show();
			  break;
			case 'aweber':
			  jQuery('#seed_csp3_section_1_13').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'constantcontact':
			  jQuery('#seed_csp3_section_1_15').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'campaignmonitor':
			  jQuery('#seed_csp3_section_1_14').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'gravityforms':
			  jQuery('#seed_csp3_section_1_16').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").hide();
			  break;
			case 'wysija':
			  jQuery('#seed_csp3_section_1_26').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  break;
			case 'mailchimp':
			  jQuery('#seed_csp3_section_1_17').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'getresponse':
			  jQuery('#seed_csp3_section_1_50').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'madmini':
			  jQuery('#seed_csp3_section_1_80').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'icontact':
			  jQuery('#seed_csp3_section_1_85').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'infusionsoft':
			  jQuery('#seed_csp3_section_1_86').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'html':
			  jQuery('#seed_csp3_section_1_87').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			case 'sendy':
			  jQuery('#seed_csp3_section_1_76').fadeIn();
			  jQuery("#seed_csp3_section_1_30 table tr:eq(1)").show();
			  jQuery(".emaillist_desc").hide();
			  break;
			default:
		}
}

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(f,h,$){var a='placeholder' in h.createElement('input'),d='placeholder' in h.createElement('textarea'),i=$.fn,c=$.valHooks,k,j;if(a&&d){j=i.placeholder=function(){return this};j.input=j.textarea=true}else{j=i.placeholder=function(){var l=this;l.filter((a?'textarea':':input')+'[placeholder]').not('.placeholder').bind({'focus.placeholder':b,'blur.placeholder':e}).data('placeholder-enabled',true).trigger('blur.placeholder');return l};j.input=a;j.textarea=d;k={get:function(m){var l=$(m);return l.data('placeholder-enabled')&&l.hasClass('placeholder')?'':m.value},set:function(m,n){var l=$(m);if(!l.data('placeholder-enabled')){return m.value=n}if(n==''){m.value=n;if(m!=h.activeElement){e.call(m)}}else{if(l.hasClass('placeholder')){b.call(m,true,n)||(m.value=n)}else{m.value=n}}return l}};a||(c.input=k);d||(c.textarea=k);$(function(){$(h).delegate('form','submit.placeholder',function(){var l=$('.placeholder',this).each(b);setTimeout(function(){l.each(e)},10)})});$(f).bind('beforeunload.placeholder',function(){$('.placeholder').each(function(){this.value=''})})}function g(m){var l={},n=/^jQuery\d+$/;$.each(m.attributes,function(p,o){if(o.specified&&!n.test(o.name)){l[o.name]=o.value}});return l}function b(m,n){var l=this,o=$(l);if(l.value==o.attr('placeholder')&&o.hasClass('placeholder')){if(o.data('placeholder-password')){o=o.hide().next().show().attr('id',o.removeAttr('id').data('placeholder-id'));if(m===true){return o[0].value=n}o.focus()}else{l.value='';o.removeClass('placeholder');l==h.activeElement&&l.select()}}}function e(){var q,l=this,p=$(l),m=p,o=this.id;if(l.value==''){if(l.type=='password'){if(!p.data('placeholder-textinput')){try{q=p.clone().attr({type:'text'})}catch(n){q=$('<input>').attr($.extend(g(this),{type:'text'}))}q.removeAttr('name').data({'placeholder-password':true,'placeholder-id':o}).bind('focus.placeholder',b);p.data({'placeholder-textinput':q,'placeholder-id':o}).before(q)}p=p.removeAttr('id').hide().prev().attr('id',o).show()}p.addClass('placeholder');p[0].value=p.attr('placeholder')}else{p.removeClass('placeholder')}}}(this,document,jQuery));
