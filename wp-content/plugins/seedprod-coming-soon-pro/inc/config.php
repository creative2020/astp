<?php
/**
 * Config
 *
 * @package WordPress
 * @subpackage seed_csp3
 * @since 0.1.0
 */

/**
 * Config
 */

// Instantiate class

if(is_admin()){
    add_action('init', 'seed_csp3_init');
}
function seed_csp3_init(){
    
    global $seed_csp3;
    /**
     * Create new menus
     */

    $seed_csp3->options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_options_page",
        "page_name" => __( "Coming Soon Pro", 'seedprod' ),
        "menu_slug" => "seed_csp3",
        "layout" => "2-col" 
    );

    /**
     * Settings Tab
     */
    $seed_csp3->options[ ] = array(
        "type" => "tab",
        "id" => "seed_csp3_tab_1",
        "label" => __( "Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "setting",
        "id" => "seed_csp3_settings_1" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_1",
        "label" => __( "General", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "radio",
        "id" => "status",
        "label" => __( "Status", 'seedprod' ),
        "option_values" => array(
            '0' => __( 'Disabled', 'seedprod' ),
            '1' => __( 'Enable Coming Soon Mode', 'seedprod' ),
            '2' => __( 'Enable Maintenance Mode', 'seedprod' ) 
        ),
        "desc" => __( "When you are logged in you'll see your normal website. Logged out visitors will see the Coming Soon or Maintenance page. Coming Soon Mode will be available to search engines if your site is not private. Maintenance Mode will notify search engines that the site is unavailable.", 'seedprod' ),
        "default_value" => "0" 
    );


    $csp3_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
    if (file_exists($csp3_maintenance_file)) {
    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_maintenance_php",
        "label" => __( "Use maintenance.php", 'seedprod' ),
        "desc" => __('maintenance.php detected, would you like to use this for your maintenance page?', 'seedprod'),
        "option_values" => array(
             'name' => __( 'Yes', 'seedprod' ),
             //'required' => __( 'Make Name Required', 'seedprod' ),
        ) 
    );
    }


    if(defined('SEED_CSP_API_KEY') === false){
    $seed_csp3->options[ ] = array(
        "type" => "customlicensekey",
        "id" => "api_key",
        "label" => __( "License Key", 'seedprod' ),
        "desc" => __( "Enter your <a href='http://www.seedprod.com/members' target='_blank'>License Key</a> to receive automatic plugin updates.", 'seedprod' ), 
    );
    }


    // Page Setttings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_2",
        "label" => __( "Page Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "upload",
        "id" => "logo",
        "label" => __( "Logo", 'seedprod' ),
        "desc" => __('Upload a logo or teaser image (or) enter the url to your image.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "headline",
        "class" => "large-text",
        "label" => __( "Headline", 'seedprod' ),
        "desc" => __( "Enter a headline for your page. Replace the default headline if it exists.", 'seedprod' ), 
    );

    $seed_csp3->options[ ] = array(
        "type" => "wpeditor",
        "id" => "description",
        "label" => __( "Description", 'seedprod' ),
        "desc" => __( "Tell the visitor what to expect from your site. Also supports WordPress shortcodes and <a href='http://codex.wordpress.org/Embeds' target='_target'>video embeds</a>.", 'seedprod' ),
        "class" => "large-text" 
    );

    // $seed_csp3->options[ ] = array(
    //     "type" => "textarea",
    //     "id" => "privacy_policy",
    //     "class" => "large-text",
    //     "label" => __( "Privacy Policy", 'seedprod' ),
    //     "desc" => __( "Add a privacy policy for your site or paste in your <a href='http://www.iubenda.com/' target='_blank'>iubenda</a> embedding code.", 'seedprod' ), 
    // );

    // Form Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_30",
        "label" => __( "Form Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "customemaillist",
        "id" => "emaillist",
        "label" => __( "Save subscribers to:", 'seedprod' ),
        "default_value" => "bottom-center",
        "option_values" => $seed_csp3->email_list_providers()
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "fields",
        "label" => __( "Name Field", 'seedprod' ),
        "desc" => __('Ask for the visitors name.', 'seedprod'),
        "option_values" => array(
             'name' => __( 'Display Name Field', 'seedprod' ),
             //'required' => __( 'Make Name Required', 'seedprod' ),
        ) 
    );

    // $seed_csp3->options[ ] = array(
    //     "type" => "checkbox",
    //     "id" => "create_users",
    //     "label" => __( "Create WordPress User", 'seedprod' ),
    //     "desc" => __('Create a WordPress user when the user submit their email.', 'seedprod'),
    //     "option_values" => array(
    //          '1' => __( 'Yes', 'seedprod' ),
    //     ) 
    // );

    // $seed_csp3->options[ ] = array(
    //     "type" => "select", 
    //     "id" => "create_users_role",
    //     "label" => __( "Create WordPress User assigned Role", 'seedprod' ),
    //     "desc" => __('Assign new WordPress user to this role.', 'seedprod'),
    //     "option_values" => $seed_csp3->get_roles(),
    //     "default_value" => "1",
    // );

    // Database Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_10",
        "label" => __( "Database Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "database_notifications",
        "label" => __( "Enable New Subscriber Notifications", 'seedprod' ),
        "desc" => __('Get an email notification when some subscribes.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "database_notifications_emails",
        "label" => __( "Send Notifications to this Email", 'seedprod' ),
        "desc" => __('Separate multiple emails with a comma. If no email is defined, notifications while be sent to the admin email.', 'seedprod'),
    );

    // FeedBurner Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_11",
        "label" => __( "FeedBurner Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "customfeedburner",
        "id" => "feedburner_addr",
        "label" => __( "Address", 'seedprod' ),
        "desc" => __('Enter your FeedBurner address.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "feedburner_local",
        "label" => __( "Local", 'seedprod' ),
        "default_value" => 'EN',
        "class" => 'small-text',
        "desc" => __('The language the FeedBurner form is displayed in. The default is English. <a href="http://support.google.com/feedburner/bin/answer.py?hl=en&answer=81423">Learn more</a>.','seedprod'),
    );

    // Aweber Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_13",
        "label" => __( "Aweber Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "text",
        "id" => "aweber_step1",
        "label" => '',
        "default_value" => __('<a href="https://auth.aweber.com/1.0/oauth/authorize_app/a662998e" target="_blank">Authorize App</a> &larr; Click the link to get you Authorization Code.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customaweberauth",
        "id" => "aweber_authorization_code",
        "label" => __( "Authorization Code", 'seedprod' ),
        "desc" => __('Paste in the Authorization Code you received when authorizing the app and click <strong>Save</strong>.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customaweberlists",
        "id" => "aweber_listid",
        "label" => __( "List", 'seedprod' ),
        "option_values" => $seed_csp3->get_aweber_lists(),
    );

    // Campaign Monitor Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_14",
        "label" => __( "Campaign Monitor Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "campaignmonitor_api_key",
        "label" => __( "API Key", 'seedprod' ),
        "desc" => __('Get your <a target="_blank" href="http://help.campaignmonitor.com/topic.aspx?t=206">API key</a>','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customcampaignmonitorclients",
        "id" => "campaignmonitor_clientid",
        "label" => __( "Client", 'seedprod' ),
        "option_values" => $seed_csp3->get_campaignmonitor_clients(),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customcampaignmonitorlists",
        "id" => "campaignmonitor_listid",
        "label" => __( "List", 'seedprod' ),
        "option_values" => $seed_csp3->get_campaignmonitor_lists(),
    );


    // Constant Contact Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_15",
        "label" => __( "Constant Contact Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "constantcontact_username",
        "label" => __( "Username", 'seedprod' ),
        "desc" => __('Enter your Constant Contact username.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "password",
        "id" => "constantcontact_password",
        "label" => __( "Password", 'seedprod' ),
        "desc" => __('Enter your Constant Contact password.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customconstantcontactlist",
        "id" => "constantcontact_listid",
        "label" => __( "List", 'seedprod' ),
        "option_values" => $seed_csp3->get_constantcontact_lists(),
    );

    // GetResponse Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_50",
        "label" => __( "GetResponse Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "getresponse_api_key",
        "label" => __( "API Key", 'seedprod' ),
        "desc" => __('Enter your API Key. <a target="_blank" href="https://app.getresponse.com/my_account.html" target="_blank">Get your API key</a>','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customgetresponse",
        "id" => "getresponse_listid",
        "label" => __( "Campaign", 'seedprod' ),
        "option_values" => $seed_csp3->get_getresponse_lists(),
    );


    // MadMini Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_80",
        "label" => __( "Mad Mimi Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "madmini_username",
        "label" => __( "Username or Email", 'seedprod' ),
        "desc" => __('Enter your username or email.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "madmini_api_key",
        "label" => __( "API Key", 'seedprod' ),
        "desc" => __('Enter your API Key. <a target="_blank" href="https://madmimi.com/user/edit?set_api&account_info_tabs=account_info_personal" target="_blank">Get your API key</a>','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "custommadmini",
        "id" => "madmini_listid",
        "label" => __( "Campaign", 'seedprod' ),
        "option_values" => $seed_csp3->get_madmini_lists(),
    );

    // infusionsoft Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_86",
        "label" => __( "Infusionsoft Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "custominfusionsoftapp",
        "id" => "infusionsoft_app",
        "label" => __( "App Name", 'seedprod' ),
        "desc" => __('Enter your app name.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "infusionsoft_api_key",
        "label" => __( "API Key", 'seedprod' ),
        "desc" => __('Enter your api key. Learn how to <a href="http://ug.infusionsoft.com/article/AA-00442/0/How-do-I-enable-the-Infusionsoft-API-and-generate-an-API-Key.html" target="_blank">generate your Infusionsoft api key</a>.','seedprod'),
    );

    // htmlSettings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_87",
        "label" => __( "HTML Web Form Settings", 'seedprod' ) 
    );


    $seed_csp3->options[ ] = array(
        "type" => "customhtmlintegrationtextarea",
        "id" => "html_integration",
        "label" => __( "HTML", 'seedprod' ),
        "desc" => __('Enter the html provided from the 3rd party service.','seedprod'),
    );


    // iContact Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_85",
        "label" => __( "iContact Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "text",
        "id" => "icontact_instructions",
        "label" => __( "Authorize App", 'seedprod' ),
        "desc" => __('<a href="https://app.icontact.com/icp/core/externallogin?sAppId=puD4TZWs2kKlKZLZZgD7IAUiqPSYPIvd" target="_blank">Authorize the App</a> and define the app password, then enter that information below.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "icontact_username",
        "label" => __( "Username", 'seedprod' ),
        "desc" => __('Enter your username.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "icontact_password",
        "label" => __( "App Password", 'seedprod' ),
        "desc" => __('Enter the password you defined for this app.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customicontact",
        "id" => "icontact_listid",
        "label" => __( "List", 'seedprod' ),
        "option_values" => $seed_csp3->get_icontact_lists(),
    );

    // Gravity Forms Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_16",
        "label" => __( "Gravity Forms Settings", 'seedprod' ) ,
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "gravityforms_form_id",
        "label" => __( "Form", 'seedprod' ),
        "option_values" => seed_csp3_get_gravityforms_forms(),
        "desc" => __('Select a form to display on your page.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "gravityforms_enable_thankyou_page",
        "label" => __( "Override Gravity Form's confirmation page" , 'seedprod' ),
        "desc" => __("Redirect to this plugin's Thank You page instead of the default Gravity Forms Confirmation page.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    // WYSIJA Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_26",
        "label" => __( "WYSIJA Settings", 'seedprod' ) ,
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "wysija_list_id",
        "label" => __( "List", 'seedprod' ),
        "option_values" => seed_csp3_get_wysija_lists(),
        "desc" => __('Select a list to save your contacts to.','seedprod'),
    );

    // Sendy Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_76",
        "label" => __( "Sendy Settings", 'seedprod' ) ,
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "sendy_url",
        "label" => __( "Sendy Url", 'seedprod' ),
        "validate" => 'escurlraw',
        "desc" => __('The  url to where your Sendy is installed. Example: http://your_sendy_installation','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "sendy_list_id",
        "label" => __( "List ID", 'seedprod' ),
        "desc" => __('The list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID in Sendy','seedprod'),
    );

    // MailChimp Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_17",
        "label" => __( "MailChimp Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "mailchimp_api_key",
        "label" => __( "API Key", 'seedprod' ),
        "desc" => __('Enter your API Key. <a target="_blank" href="http://admin.mailchimp.com/account/api-key-popup" target="_blank">Get your API key</a>','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "custommailchimplist",
        "id" => "mailchimp_listid",
        "label" => __( "List", 'seedprod' ),
        "option_values" => $seed_csp3->get_mailchimp_lists(),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "mailchimp_enable_double_optin",
        "label" => __( "Enable Double Opt-In", 'seedprod' ),
        "desc" => __('Learn more about <a href="http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work">Double Opt-In</a>', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "mailchimp_welcome_email",
        "label" => __( "Send Welcome Email", 'seedprod' ),
        "desc" => __("If your Double Opt-in is false and this is true, MailChimp will send your lists Welcome Email if this subscribe succeeds - this will not fire if MailChimp ends up updating an existing subscriber. If Double Opt-in is true, this has no effect. Learn more about <a href='http://blog.mailchimp.com/sending-welcome-emails-with-mailchimp/' target='_blank'>Welcome Emails</a>.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "mailchimp_group_name",
        "label" => __( "Group Name", 'seedprod' ),
        "desc" => __('Optional: Enter the name of the group. Learn more about <a href="http://mailchimp.com/features/groups/" target="_blank">Groups</a>', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "mailchimp_groups",
        "label" => __( "Groups", 'seedprod' ),
        "desc" => __('Optional: Comma delimited list of interest groups to add the email to.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "mailchimp_replace_interests",
        "label" => __( "Replace Interests", 'seedprod' ),
        "desc" => __("Whether MailChimp will replace the interest groups with the groups provided or add the provided groups to the member's interest groups", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    //After Subscribe
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_5",
        "label" => __( "After Subscribe Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "wpeditor",
        "id" => "thankyou_msg",
        "label" => __( "Thank You or Incentive Message", 'seedprod' ),
        "desc" => __( "Leave a thank you or incentive information after the user has subscribed. This will override the default success message.", 'seedprod' ),
        "class" => "large-text" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "customsharebuttons",
        "id" => "share_buttons",
        "label" => __( "Share Buttons", 'seedprod' ),
        "desc" => __('Select the checkboxes above to display Social Share Buttons after you capture an email.', 'seedprod'),
        "option_values" => apply_filters('seed_csp3_share_buttons',array(
             '0' => __( 'Twitter', 'seedprod' ),
             '1' => __( 'Facebook', 'seedprod' ),
             '2' => __( 'Google Plus', 'seedprod' ),
             '3' => __( 'LinkedIn', 'seedprod' ),
             '4' => __( 'PinIt', 'seedprod' ),
             //'5' => __( 'StumbledUpon', 'seedprod' ),
             '5' => __( 'Tumblr', 'seedprod' ),
        ) )
    );


    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "enable_reflink",
        "label" => __( "Display Referrer Link", 'seedprod' ),
        "desc" => __('The referrer link is a special link that you can encourage your subscribers to share so you track who referred who.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    // Progress Bar Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_20",
        "label" => __( "Progress Bar Settings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "enable_progressbar",
        "label" => __( "Enable Progress Bar", 'seedprod' ),
        "desc" => __('Displays a progress bar on your site.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "daterange", 
        "id" => "progressbar_date_range",
        "label" => __( "Start and End Date", 'seedprod' ),
        "desc" => __('The percent complete will be automatically calculated if you enter a start and end date.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "progressbar_percentage",
        "class" => "small-text",
        "label" => __( "Percent Complete Override", 'seedprod' ),
        "desc" => __( "Enter the percentage complete without the percentage sign. This will override the date calculation above.", 'seedprod' ),
        "validate" => 'number', 
    );

    $seed_csp3->options[ ] = array(
        "type" => "radio",
        "id" => "progressbar_effect",
        "label" => __( "Progress Bar Effect", 'seedprod' ),
        "option_values" => array(
             'basic' => __( 'Basic', 'seedprod' ),
             'striped' => __( 'Striped', 'seedprod' ),
             'animated' => __( 'Animated', 'seedprod' ),
        ),
        "default_value" => 'basic',
        "desc"=> __('Striped and Animated are not supported in Internet Explorer','seedprod'),
    );

    //Countdown Settings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_21",
        "label" => __( "Countdown Settings", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "enable_countdown",
        "label" => __( "Enable Countdown", 'seedprod' ),
        "desc" => __('Displays a countdown on your site.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "customcountdown", 
        "id" => "countdown_date",
        "label" => __( "End DateTime", 'seedprod' ),
        "desc" => __('Enter the datetime to countdown to.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "countdown_format",
        "class" => "text-small",
        "label" => __( "Format", 'seedprod' ),
        "desc" => __("Optional Format for display - upper case for always, lower case only if non-zero, 'D' days, 'H' hours, 'M' minutes, 'S' seconds. Default: dHMS", 'seedprod'),
    );

    // $seed_csp3->options[ ] = array(
    //     "type" => "textbox", 
    //     "class" => "small-text",
    //     "id" => "countdown_timezone",
    //     "label" => __( "Timezone", 'seedprod' ),
    //     "desc" => __('Enter the hours or minutes from GMT. If left blank the browsers local time will be used. Example: -5', 'seedprod'),
    // );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "countdown_launch",
        "label" => __( "Auto Launch", 'seedprod' ),
        "desc" => __('This will automatically launch your site when countdown reaches the end.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "text", 
        "id" => "countdown_ls_text",
        "label" => __( "Language Strings", 'seedprod' ),
        "desc" => __('Translate the text strings below into your language if not English.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_days",
        "label" => __( "Days", 'seedprod' ),
        "desc" => __('Translate into your language. (Plural)', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_day",
        "label" => __( "Day", 'seedprod' ),
        "desc" => __('Translate into your language. (Singular)', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_hours",
        "label" => __( "Hours", 'seedprod' ),
        "desc" => __('Translate into your language. (Plural)', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_hour",
        "label" => __( "Hour", 'seedprod' ),
        "desc" => __('Translate into your language.(Singular)', 'seedprod'),
    );


    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_minutes",
        "label" => __( "Minutes", 'seedprod' ),
        "desc" => __('Translate into your language. (Plural)', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_minute",
        "label" => __( "Minute", 'seedprod' ),
        "desc" => __('Translate into your language. (Singular)', 'seedprod'),
    );


    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_seconds",
        "label" => __( "Seconds", 'seedprod' ),
        "desc" => __('Translate into your language. (Plural)', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "class" => "text-small",
        "id" => "countdown_second",
        "label" => __( "Second", 'seedprod' ),
        "desc" => __('Translate into your language. (Singular)', 'seedprod'),
    );






    // Header
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_8",
        "label" => __( "Header", 'seedprod' ) 
    );
    $seed_csp3->options[ ] = array(
        "type" => "upload",
        "id" => "favicon",
        "label" => __( "Favicon", 'seedprod' ),
        "desc" => __('Favicons are displayed in a browser tab. Need Help <a href="http://tools.dynamicdrive.com/favicon/" target="_blank">creating a favicon</a>?', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "seo_title",
        "label" => __( "SEO Title", 'seedprod' ),
        "desc" => __('If left empty the <a href="http://www.seomoz.org/learn-seo/title-tag" target="_blank">title tag</a> of your page will be: ', 'seedprod').get_bloginfo( 'description', 'display' ).' - '.get_bloginfo('name','Display'), 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "seo_description",
        "label" => __( "SEO Meta Description", 'seedprod' ),
        "desc" => __('If left empty the <a href="http://www.seomoz.org/learn-seo/meta-description" target="_blank">meta description</a> of your page will be: ', 'seedprod').get_bloginfo( 'description', 'display' ),
        "class" => "large-text" 
    );


    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "ga_analytics",
        "label" => __( "Analytics Code", 'seedprod' ),
        "desc" => __('Paste in your <a href="http://www.google.com/analytics/" target="_blank">Google Analytics</a> code. Example: UA-xxxxxxxxx', 'seedprod'),
    );

    // Footer
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_7",
        "label" => __( "Footer Credit", 'seedprod' ) 
    );

    // $seed_csp3->options[ ] = array(
    //     "type" => "textbox",
    //     "id" => "footer_credit_txt",
    //     "label" => __( "Credit Text", 'seedprod' ),
    // );

    $seed_csp3->options[ ] = array(
        "type" => "upload",
        "id" => "footer_credit_img",
        "label" => __( "Credit Image", 'seedprod' ),
        "desc" => __('Use an image to add a footer credit.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "footer_credit_link",
        "desc" => __('Link to be used for your footer credit.', 'seedprod'),
        "label" => __( "Credit Link", 'seedprod' ),
        "validate" => 'escurlraw',
    );








    // Social Media
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_4",
        "label" => __( "Social Media Profiles", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "radio",
        "id" => "social_media_icon_size",
        "label" => __( "Social Media Icons Size", 'seedprod' ),
        "option_values" => array(
            '16' => __( 'Small 16px', 'seedprod' ),
            '24' => __( 'Medium 24px', 'seedprod' ),
            '32' => __( 'Large 32px', 'seedprod' ),
        ),
        "default_value" => "24" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "customsocialfollow",
        "id" => "social_profiles",
        "label" => __( "Social Profiles", 'seedprod' ),
    );

    // Language Strings
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_1_3",
        "label" => __( "Language Strings", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_1",
        "label" => __( "Subscribe Button", 'seedprod' ),
        "default_value" => __('Notify Me','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_2",
        "label" => __( "Subscribe Field", 'seedprod' ),
        "default_value" => __('Enter Your Email','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_fname",
        "label" => __( "First Name Field", 'seedprod' ),
        "default_value" => __('First Name','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_lname",
        "label" => __( "Last Name Field", 'seedprod' ),
        "default_value" => __('Last Name','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_5",
        "label" => __( "Success", 'seedprod' ),
        "default_value" => __("You'll be notified soon!",'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_3",
        "label" => __( "Already Subscribed", 'seedprod' ),
        "default_value" => __("You're already subscribed.",'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_4",
        "label" => __( "Invalid Email", 'seedprod' ),
        "default_value" => __('Please enter a valid email.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "txt_6",
        "label" => __( "API Error", 'seedprod' ),
        "default_value" => __('Error, please try again.','seedprod'),
    );

    if (is_plugin_active('wpml-string-translation/plugin.php')) {
        $seed_csp3->options[ ] = array(
            "type" => "radio",
            "id" => "display_lang_switcher",
            "label" => __( "Display WPML Language Switcher", 'seedprod' ),
            "option_values" => array(
                '0' => __( 'No', 'seedprod' ),
                '1' => __( 'Yes', 'seedprod' ),
            ),
            "desc" => __( "This will display the WPML Language Switcher on the Coming Soon page.", 'seedprod' ),
            "default_value" => "0" 
        );
    }


    /**
     * Design Tab
     */
    $seed_csp3->options[ ] = array(
        "type" => "tab",
        "id" => "seed_csp3_tab_2",
        "label" => __( "Design", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "setting",
        "id" => "seed_csp3_settings_2" 
    );


    // Themes
    // $seed_csp3->options[ ] = array(
    //     "type" => "section",
    //     "id" => "seed_csp3_section_2_8",
    //     "label" => __( "Themes", 'seedprod' ) 
    // );

    // $seed_csp3->options[ ] = array(
    //     "type" => "radio",
    //     "id" => "theme",
    //     "label" => __( "Themes", 'seedprod' ),
    //     "option_values" => array(
    //         'no-repeat' => __( 'Sunday Morning', 'seedprod' ),
    //     ),
    //     "desc" => __("Themes are a quick way to styles and get up and going. After you load a theme feel free to customizer further.",'seedprod')
    // );


    // Background
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_2_1",
        "label" => __( "Background", 'seedprod' ) 
    );


    $seed_csp3->options[ ] = array(
        "type" => "color",
        "id" => "bg_color",
        "label" => __( "Background Color", 'seedprod' ),
        "default_value" => "#ffffff",
        "validate" => 'color',
        "desc" => __( "Choose between having a solid color background or uploading an image. By default images will cover the entire background.", 'seedprod' ) 
    );


    $seed_csp3->options[ ] = array(
        "type" => "upload",
        "id" => "bg_image",
        "label" => __( "Background Image", 'seedprod' ),  
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_cover",
        "label" => __( "Cover or Stretch", 'seedprod' ),
        "desc" => __("Cover will scale the image to the smallest size such that both its width and its height can fit inside the content area. The image will be stretched for older browsers.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 
        //"default_value" => array('1'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "bg_repeat",
        "label" => __( "Background Repeat", 'seedprod' ),
        "option_values" => array(
            'no-repeat' => __( 'No-Repeat', 'seedprod' ),
            'repeat' => __( 'Tile', 'seedprod' ),
            'repeat-x' => __( 'Tile Horizontally', 'seedprod' ),
            'repeat-y' => __( 'Tile Vertically', 'seedprod' ),
        )
    );


    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "bg_position",
        "label" => __( "Background Position", 'seedprod' ),
        "option_values" => array(
            'left top' => __( 'Left Top', 'seedprod' ),
            'left center' => __( 'Left Center', 'seedprod' ),
            'left bottom' => __( 'Left Bottom', 'seedprod' ),
            'right top' => __( 'Right Top', 'seedprod' ),
            'right center' => __( 'Right Center', 'seedprod' ),
            'right bottom' => __( 'Right Bottom', 'seedprod' ),
            'center top' => __( 'Center Top', 'seedprod' ),
            'center center' => __( 'Center Center', 'seedprod' ),
            'center bottom' => __( 'Center Bottom', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "bg_attahcment",
        "label" => __( "Background Attachment", 'seedprod' ),
        "option_values" => array(
            'fixed' => __( 'Fixed', 'seedprod' ),
            'scroll' => __( 'Scroll', 'seedprod' ),
        )
    );

    $seed_csp3->options[ ] = array(
        "type" => "radio",
        "id" => "bg_effect",
        "label" => __( "Background Effects", 'seedprod' ),
        "option_values" => array(
            '0' => __( 'None', 'seedprod' ),
            'noise' => __( 'Noise', 'seedprod' ),
        ),
        "desc" => __( "Background effects can be applied to Background Colors or Background Images.", 'seedprod' ),
        "default_value" => '0', 
    );


    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_slideshow",
        "label" => __( "Background Slideshow", 'seedprod' ),
        "desc" => __("This will override the setting above and create a background slideshow.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 
        //"default_value" => array('1'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_slideshow_randomize",
        "label" => __( "Slideshow Randomize", 'seedprod' ),
        "desc" => __("This will display a random slideshow each time as opposed to the order listed below.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 
        //"default_value" => array('1'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "bg_slideshow_slide_speed",
        "label" => __( "Slide Speed", 'seedprod' ),
        "desc" => __("This will determine how fast slides change in milliseconds.", 'seedprod'),
        "default_value" => '3000',
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "bg_slideshow_slide_transition",
        "label" => __( "Slide Transition", 'seedprod' ),
        "desc" => __("This will determine the transition between slides.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Fade', 'seedprod' ),
             '2' => __( 'Slide Top', 'seedprod' ),
             '3' => __( 'Slide Right', 'seedprod' ),
             '4' => __( 'Slide Bottom', 'seedprod' ),
             '5' => __( 'Slide Left', 'seedprod' ),
             '6' => __( 'Carousel Right', 'seedprod' ),
             '7' => __( 'Carousel Left', 'seedprod' ),
        ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "bg_slideshow_images",
        "label" => __( "Slideshow Images", 'seedprod' ),
        "desc" => __('Paste in the urls to the images you like to use for your slideshow one per line. <a href="http://www.seedprod.com/knowledge-base/create-background-slideshow/" target="_blank">Learn How</a>', 'seedprod'),
        "class" => "large-text" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "text", 
        "id" => "background_video_ls_text",
        "label" => __( "Background Video (Beta)", 'seedprod' ),
        "desc" => __('This is very much a beta feature. There may be bugs.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_video",
        "label" => __( "Enable Background Video", 'seedprod' ),
        "desc" => __("This will override the setting above and create a background video. Mobile devices will not auto play video so the background image above will be used instead.", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 
        //"default_value" => array('1'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "bg_video_url",
        "label" => __( "Background Video URL", 'seedprod' ),
        "desc" => __("Enter the YouTube, Vimeo or MP4 url.", 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_video_hd",
        "label" => __( "Background Video HD", 'seedprod' ),
        "desc" => __("Control hd playback (YouTube videos only).", 'seedprod'),
                "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 

    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "bg_video_adproof",
        "label" => __( "Background AdProof", 'seedprod' ),
        "desc" => __("Scale the youtube player larger than the browser to obscure ads (YouTube videos only).", 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ), 

    );

    // Text
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_2_2",
        "label" => __( "Text", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "color",
        "id" => "text_color",
        "label" => __( "Text Color", 'seedprod' ),
        "default_value" => "#ffffff",
        "validate" => 'required,color',
    );



    $seed_csp3->options[ ] = array(
        "type" => "color",
        "id" => "link_color",
        "label" => __( "Link &amp; Button Color", 'seedprod' ),
        "default_value" => "#ffffff",
        "validate" => 'required,color',
    );

    $seed_csp3->options[ ] = array(
        "type" => "color",
        "id" => "headline_color",
        "label" => __( "Headline Color", 'seedprod' ),
        "validate" => 'color',
        "desc" => __('If no Headline Color is choosen then the Link &amp; Button Color will be used. ','seedprod'),
    );



    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "text_font",
        "label" => __( "Text Font", 'seedprod' ),
        "option_values" => apply_filters('seed_csp3_fonts',array(
            'Default Fonts' =>array(
            '_arial'     => 'Arial',
            '_arial_black' =>'Arial Black',
            '_georgia'   => 'Georgia',
            '_helvetica_neue' => 'Helvetica Neue',
            '_impact' => 'Impact',
            '_lucida' => 'Lucida Grande',
            '_palatino'  => 'Palatino',
            '_tahoma'    => 'Tahoma',
            '_times'     => 'Times New Roman',
            '_trebuchet' => 'Trebuchet',
            '_verdana'   => 'Verdana',),
            ) ),
        //"desc" => __( "If you do not choose a text font then fefault font will be used.", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "headline_font",
        "label" => __( "Headline Font", 'seedprod' ),
        "option_values" => apply_filters('seed_csp3_fonts',array(
            'inherit' => 'Inherit',
            'Default Fonts' =>array(
            '_arial'     => 'Arial',
            '_arial_black' =>'Arial Black',
            '_georgia'   => 'Georgia',
            '_helvetica_neue' => 'Helvetica Neue',
            '_impact' => 'Impact',
            '_lucida' => 'Lucida Grande',
            '_palatino'  => 'Palatino',
            '_tahoma'    => 'Tahoma',
            '_times'     => 'Times New Roman',
            '_trebuchet' => 'Trebuchet',
            '_verdana'   => 'Verdana',),
            ) ),
        "desc" => __( "If you choose 'Inherit' as a headline font then the text font will be used.", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "select",
        "id" => "button_font",
        "label" => __( "Button Font", 'seedprod' ),
        "option_values" => apply_filters('seed_csp3_fonts',array(
            'inherit' => 'Inherit',
            'Default Fonts' =>array(
            '_arial'     => 'Arial',
            '_arial_black' =>'Arial Black',
            '_georgia'   => 'Georgia',
            '_helvetica_neue' => 'Helvetica Neue',
            '_impact' => 'Impact',
            '_lucida' => 'Lucida Grande',
            '_palatino'  => 'Palatino',
            '_tahoma'    => 'Tahoma',
            '_times'     => 'Times New Roman',
            '_trebuchet' => 'Trebuchet',
            '_verdana'   => 'Verdana',),
            ) ),
        "desc" => __( "If you choose 'Inherit' as a button font then the headline font will be used.", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox",
        "id" => "typekit_id",
        "class" => "all-options",
        "label" => __( "Typekit Kit ID", 'seedprod' ),
        "desc" => __('Enter your <a href="https://typekit.com" target="_blank">Typekit</a> Kit ID. This will override the fonts above.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "text_effect",
        "label" => __( "Text Effects", 'seedprod' ),
        "option_values" => array(
             'inset' => __( 'Inset <small class="description">: Adds a subtle inset shadow</small>', 'seedprod' ),
             //'glow' => __( 'Glow', 'seedprod' ),
        ),
        "default_value" => '0',
    );


    // Container
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_2_3",
        "label" => __( "Container", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_container",
        "label" => __( "Enable Container", 'seedprod' ),
        "desc" => __('A container is a wrapper that will encapsulate your coming soon content.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "color",
        "id" => "container_color",
        "label" => __( "Container Color", 'seedprod' ),
        "default_value" => "#ffffff",
        "validate" => 'required,color',
    );

    $seed_csp3->options[ ] = array(
        "type" => "radio",
        "id" => "container_position",
        "label" => __( "Container Position", 'seedprod' ),
        "option_values" => array(
             'left' => __( 'Left', 'seedprod' ),
             'none' => __( 'Center', 'seedprod' ),
             'right' => __( 'Right', 'seedprod' ),
        ),
        "default_value" => 'center'
    );

    $seed_csp3->options[ ] = array(
        "type" => "customcontainereffects",
        "id" => "container_effect",
        "label" => __( "Container Effects", 'seedprod' ),
        "option_values" => array(
             '5' => __( 'Fade In', 'seedprod' ),
             '0' => __( 'Drop Shadow', 'seedprod' ),
             '1' => __( 'Glow', 'seedprod' ),
             '2' => __( 'Border', 'seedprod' ),
             '3' => __( 'Rounded Corners', 'seedprod' ),
             '4' => __( 'Opacity', 'seedprod' ),
        ),
            "desc"=> __('These effects only work in modern browsers. Not sure what a <a target="_blank" href="http://browsehappy.com/">modern browser</a> is?','seedprod'),
    );





    // // Other Settings
    // $seed_csp3->options[ ] = array(
    //     "type" => "section",
    //     "id" => "seed_csp3_section_2_5",
    //     "label" => __( "Social Media Icon Settings", 'seedprod' ) 
    // );




    // Template
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_2_9",
        "label" => __( "Template", 'seedprod' ) 
    );


    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "custom_css",
        "class" => "all-options",
        "label" => __( "Custom CSS", 'seedprod' ),
        "desc" => __('Need to tweaks the styles? Add your custom CSS here.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "customcodeeditor",
        "id" => "template",
        "class" => "large-text",
        "label" => __( "Code", 'seedprod' ),
        "desc" => __('If you need to make some advanced changes to the template you can edit the code above directly.','seedprod'),
        "default_value" => $seed_csp3->get_default_template()
    );

    $seed_csp3->options[ ] = array(
        "type" => "text",
        "id" => "template_tags",
        "label" => __( "Template Tags", 'seedprod' ),
        "desc" => __('
            <code>{Title}</code> - Display your title. Settings > Coming Soon Pro Settings > Header > SEO Title <br>
            <code>{MetaDescription}</code> - Displays your description along with the proper meta tags. Settings > Coming Soon Pro > Settings > Header > SEO Meta Description <br>
            <code>{Privacy}</code> - Displays noindex tag if set to private. Settings > Privacy <br>
            <code>{Favicon}</code> - Displays favicon tag. Settings > Coming Soon Pro > Settings > Header > Favicon <br>
            <code>{Head}</code> - Required in the <head> Displays custom scripts for the page. Settings > Coming Soon Pro > Advanced > Scripts<br>
            <code>{Custom CSS}</code> - Displays custom css page. Settings > Coming Soon Pro > Design > Template > Custom CSS<br>
            <code>{Logo}</code> - Displays your logo if set. Settings > Coming Soon Pro > Settings > Page Settings > Logo<br>
            <code>{Headline}</code> - Displays your headline if set. Settings > Coming Soon Pro > Settings > Page Settings > Headline<br>
            <code>{Description}</code> - Displays your description if set. Settings > Coming Soon Pro > Settings > Page Settings > Description<br>
            <code>{ProgressBar}</code> - Displays a progress bar if enabled. Settings > Coming Soon Pro > Settings > Progress Bar Settings<br>
            <code>{Countdown}</code> - Displays a countdown if enabled. Settings > Coming Soon Pro > Settings > Countdown Settings<br>
            <code>{Form}</code> - Displays signup form and thank you page. Settings > Coming Soon Pro > Settings<br>
            <code>{SocialProfiles}</code> - Displays your social profiles if set. Settings > Coming Soon Pro > Settings > Social Media Profiles<br>
            <code>{Credit}</code> - Displays your footer credit if set. Settings > Coming Soon Pro > Settings > Footer Credit<br>
            <code>{Footer}</code> - Displays required scripts for the page.<br>
            ','seedprod'),
    );


    /**
     * Advanced Tab
     */
    $seed_csp3->options[ ] = array(
        "type" => "tab",
        "id" => "seed_csp3_tab_4",
        "label" => __( "Advanced", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "setting",
        "id" => "seed_csp3_settings_4" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_4_2",
        "label" => __( "Access", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "customclientview",
        "id" => "client_view_url",
        "class" => "all-options",
        "label" => __( "Client View URL", 'seedprod' ),
        "desc" => __( "Enter a phrase above and give your client a secret url that will allow them to bypass the Coming Soon page. The cookie life is 6 hours. After the cookie expires they will need to revisit the client view url to regain access.", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "ip_access",
        "label" => __( "Access by IP", 'seedprod' ),
        "desc" => __( "All visitors from certain IP's to bypass the Coming Soon page. Put each IP on it's own line.", 'seedprod' ),
    );

    $seed_csp3->options[ ] = array(
        "type" => "multiselect", 
        "id" => "include_roles",
        "label" => __( "Access by Role", 'seedprod' ),
        "desc" => __('By default anyone logged in will see the regular site and not the coming soon page. To override this select Roles that will be given access to see the regular site.', 'seedprod'),
        "option_values" => array(0 => "Anyone Logged In") + $seed_csp3->get_roles(),
        "default_value" => "0",
    );


    $seed_csp3->options[ ] = array(
        "type" => "select", 
        "id" => "include_page",
        "label" => __( "Landing Page Mode", 'seedprod' ),
        "desc" => __('Only display the coming soon page on one page of your site and use it as a landing page. Leave unselected if you want the coming soon page to be display on your entire site.', 'seedprod'),
        "option_values" => array('-1' => "-- Select --") + $seed_csp3->get_pages(),
        "default_value" => "0",
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "include_url_pattern",
        "label" => __( "Include URL Pattern", 'seedprod' ),
        "desc" => __('Include certain urls to only diplay the coming soon page on a section of your site using <a href="http://en.wikipedia.org/wiki/Regex" target="_blank">regular expressions</a>. For example enter "blog" will include pages with the url: http://example.org/blog/*. <a href="http://www.seedprod.com/knowledge-base/exclude-include-urls-pattern-examples/" target="_blank">See more examples</a> and learn how to include multiple pages. This is useful if you are working on a new section of your site.', 'seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "textbox", 
        "id" => "exclude_url_pattern",
        "label" => __( "Exclude URL Pattern", 'seedprod' ),
        "desc" => __('Exclude certain urls from displaying the Coming Soon page using <a href="http://en.wikipedia.org/wiki/Regex" target="_blank">regular expressions</a>. For example enter "blog" will exclude the url: http://example.org/blog/. <a href="http://www.seedprod.com/knowledge-base/exclude-include-urls-pattern-examples/" target="_blank">See more examples</a> and learn how to exclue multiple pages. This is useful if a section of you site is ready to be viewed or you have a login page on the frontend.', 'seedprod'),
    );


    // Scripts
    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_4_3",
        "label" => __( "Scripts", 'seedprod' ) 
    );

    // $seed_csp3->options[ ] = array(
    //     "type" => "checkbox", 
    //     "id" => "enable_ajax",
    //     "label" => __( "Enable Ajax", 'seedprod' ),
    //     "desc" => __('Submit the form without refreshing the page. Certain WordPress Multisite setups require ajax to be disabled.', 'seedprod'),
    //     "option_values" => array(
    //          '1' => __( 'Yes', 'seedprod' ),
    //     ),
    //     //"default_value" => "1",
    // );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "enable_responsiveness",
        "label" => __( "Enable Responsiveness", 'seedprod' ),
        "desc" => __('Makes the page responsive.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ),
        //"default_value" => "1",
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox", 
        "id" => "enable_fitvidjs",
        "label" => __( "Enable FitVid", 'seedprod' ),
        "desc" => __('Makes your videos responsive.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ),
        "default_value" => "1",
    );


    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "enable_wp_head_footer",
        "label" => __( "Enable wp_head &amp; wp_footer", 'seedprod' ),
        "desc" => __('The <a href="http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head" target="_blank">wp_head</a> and <a href="http://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer" target="_blank">wp_footer</a> functions are required by some plugins. If you are unsure do not enable it. These functions can cause styling issues with the page.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "header_scripts",
        "label" => __( "Header Scripts", 'seedprod' ),
        "desc" => __('Enter any custom scripts. You can enter Javascript or CSS. This will be rendered before the closing head tag.', 'seedprod'),
        "class" => "large-text" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "footer_scripts",
        "label" => __( "Footer Scripts", 'seedprod' ),
        "desc" => __('Enter any custom scripts. This will be rendered before the closing body tag.', 'seedprod'),
        "class" => "large-text" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "textarea",
        "id" => "conversion_scripts",
        "label" => __( "Conversion Scripts", 'seedprod' ),
        "desc" => __('This will render only after the form has been submitted. This will be rendered before the closing body tag.', 'seedprod'),
        "class" => "large-text" 
    );

    $seed_csp3->options[ ] = array(
        "type" => "checkbox",
        "id" => "disable_code_template",
        "label" => __( "Disable Custom Code Template", 'seedprod' ),
        "desc" => __('Mod_security will cause false positive 400 error on some host. Disable the custom template to avoid this error and notify your host of the false positive.', 'seedprod'),
        "option_values" => array(
             '1' => __( 'Yes', 'seedprod' ),
        ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "section",
        "id" => "seed_csp3_section_4_4",
        "label" => __( "Import / Export", 'seedprod' ) 
    );

    $seed_csp3->options[ ] = array(
        "type" => "import",
        "id" => "import",
        "label" => __( "Import", 'seedprod' ),
        "desc" => __('Paste settings from another Coming Soon Pro instance to import.','seedprod'),
    );

    $seed_csp3->options[ ] = array(
        "type" => "export",
        "id" => "export",
        "label" => __( "Export", 'seedprod' ),
        "desc" => __('Copy these settings and use the import them to another Coming Soon Pro instance.','seedprod'),
    );

    /**
     * Subscribers Tab
     */
    $seed_csp3->options[ ] = array(
        "type" => "tab",
        "id" => "seed_csp3_tab_3",
        "label" => __( "Subscribers", 'seedprod' ),
    );

}


// $csp3_options = csp3_insert_array_index($csp3_options, $csp3_options_new, 9);

// function csp3_insert_array_index($array, $new_element, $index) {
//         $start = array_slice($array, 0, $index); 
//         $end = array_slice($array, $index);
//         $start = array_merge($start, $new_element);
//         return array_merge($start, $end);
// }


/**
 * Getting Started Desc
 */
function seed_csp3_section_example_callback( )
{
    echo '<p>This is a sample section description callback.</p>';
}