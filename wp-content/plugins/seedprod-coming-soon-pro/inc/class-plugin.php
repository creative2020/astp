<?php
/**
 * Plugin class logic goes here
 */
$seed_csp3_post_result = array();

class SEED_CSP3_PLUGIN extends SEED_CSP3 {

	private $comingsoon_rendered = false;

	function __construct(){
            parent::__construct();

			extract($this->get_settings());

            // Actions & Filters
            if(((!empty($status) && $status === '1') || (!empty($status) && $status === '2')) || (isset($_GET['cs_preview']) && $_GET['cs_preview'] == 'true')){
            	if(function_exists('bp_is_active')){
                    add_action( 'template_redirect', array(&$this,'render_comingsoon_page'),9);
                }else{
                    add_action( 'template_redirect', array(&$this,'render_comingsoon_page'));
                }
                add_action( 'admin_bar_menu',array( &$this, 'admin_bar_menu' ), 1000 );
            }

            //Display Subscribers
            add_action('seed_csp3_render_page', array( &$this, 'display_subscribers' ));

            // Add backend scripts
            add_action( 'admin_enqueue_scripts', array(&$this,'add_backend_scripts') );

            // Import Scripts
            add_action( 'wp_ajax_seed_csp3_import_settings', array(&$this,'import_settings') );

            // Refresh Lists
            add_action( 'wp_ajax_seed_csp3_refresh_list', array(&$this,'refresh_list'));

            // Check License
            add_action( 'wp_ajax_seed_csp3_check_license', array(&$this,'check_license'));

            // Refresh Campaign Monitor Clients
            add_action( 'wp_ajax_seed_csp3_campaingmonitor_client', array(&$this,'campaingmonitor_client_refresh') );

            // Aweber Auth
            add_action( 'wp_ajax_seed_csp3_aweber_auth', array(&$this,'aweber_auth') );

            //Subscribe Callback
            add_action( 'wp_ajax_seed_csp3_subscribe_callback', array(&$this,'subscribe_callback') );
            add_action( 'wp_ajax_nopriv_seed_csp3_subscribe_callback', array(&$this,'subscribe_callback') );

            // Upgrade
            add_action( 'admin_init', array( &$this, 'upgrade' ), 0 );

            // Handle action post
            add_action( 'admin_init', array( &$this, 'subscriber_actions' ), 0 );

            
    }


    function subscriber_actions(){
        if(isset($_GET['tab']) && $_GET['tab'] == 'seed_csp3_tab_3'){
    
            if(!empty($_POST['action'])){
                if($_POST['action'] == 'export'){
                    $this->export_all_subscribers();
                }
            }
        }
    }

    /**
     * Upgrade setting pages. This allows you to run an upgrade script when the version changes.
     *
     */
    function upgrade( )
    {
        // get current version
        $seed_csp3_current_version = get_option( 'seed_csp3_version' );
        $upgrade_complete = false;
        if ( empty( $seed_csp3_current_version ) ) {
            $seed_csp3_current_version = 0;
        }

        if($seed_csp3_current_version === 0){
            // Load Defaults if new install
            require_once(SEED_CSP3_PLUGIN_PATH.'inc/defaults.php');
            
            //Try to upgrade settings from 2 to 3
            // $mapping = array(
            //     'api_key' => 'seedprod_api_key',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     // '' => '',
            //     );

            // $old_fields = array();
            // $old_fields = get_option('csp2_option_tree');
            // var_dump($old_fields);
            // if(!empty($old_fields)){
            //     foreach($seed_csp3_settings_deafults as $k=>$v){
            //         foreach($v as $k2=>$v2){
            //             if(array_key_exists($k2,$mapping)){
            //                 if(!empty($old_fields[$mapping[$k2]]))
            //                     $seed_csp3_settings_deafults[$k][$k2] = $old_fields[$mapping[$k2]];
            //             }
            //         }
            //     }
            // }
            // var_dump($seed_csp3_settings_deafults);

            foreach($seed_csp3_settings_deafults as $k=>$v){
                update_option( $k, $v );
            }
        }

        if ( version_compare( $seed_csp3_current_version,SEED_CSP3_VERSION) === -1) {
            // Upgrade db if new version
            $this->subscriber_database_setup();
            $upgrade_complete = true;

        }

        if($upgrade_complete){
            update_option( 'seed_csp3_version', SEED_CSP3_VERSION );
        }
        //var_dump($upgrade_complete);
        
        // Sample script to update field if it's changed to a different tab.
        // if ( version_compare( SEED_CSP3_VERSION,$seed_csp3_current_version ) === 1) {
        //     $old_fields = array();
        //     $old_fields = get_option('seed_csp3_options_1');
        //     $old_fields = $old_fields + get_option('seed_csp3_options_1');
        
        //     $new_fields = array();
        //     foreach ($this->options as $k) {
        //         switch ($k['type']) {
        //             case 'setting':
        //             case 'section':
        //             case 'tab':
        //                 break;
        //             default:
        //                 if(isset($old_fields[$k['id']])){
        //                     $new_fields[$k['setting_id']][$k['id']] = $old_fields[$k['id']];
        //                 }
        
        
        //         }
        //     }
        //     var_dump($old_fields);
        //     var_dump($new_fields);
        
        // }
    }

    /**
     * Get settings
     */
    function get_settings(){
	    $s1 = get_option('seed_csp3_settings_1');
        $s2 = get_option('seed_csp3_settings_2');
        $s3 = get_option('seed_csp3_settings_4');
        if(empty($s1) || is_array($s1) === false)
            $s1 = array();

        if(empty($s2) || is_array($s2) === false)
            $s2 = array();

        if(empty($s3) || is_array($s3) === false)
            $s3 = array();

        $options = $s1 + $s2 + $s3;
        return $options;
    }

    /**
     * Get pages and put in assoc array
     */
    function get_pages(){
        $pages = get_pages();
        $page_arr = array();
        if(is_array($pages)){
            foreach($pages as $k=>$v){
                $page_arr[$v->ID] = $v->post_title;
            }
        }
        return $page_arr;
    }

    /**
     * Get roles
     */
    function get_roles() {
        global $wp_roles;
        if ( ! isset( $wp_roles ) )
            $wp_roles = new WP_Roles();
        $roles = $wp_roles->get_names();
        
        if ( is_multisite() )
            $roles['superadmin'] = __('Super Admin','seedprod');

        return $roles;
    }

    /**
     * Display admin bar when active
     */
    function admin_bar_menu($str){
        global $wp_admin_bar;
        extract($this->get_settings());
        $msg = '';
        if($status == '1'){
        	$msg = __('Coming Soon Mode Active','seedprod');
        }elseif($status == '2'){
        	$msg = __('Maintenance Mode Active','seedprod');
        }
    	//Add the main siteadmin menu item 
        $wp_admin_bar->add_menu( array(
            'id'     => 'seed-csp3-notice',
            'href' => admin_url().'options-general.php?page=seed_csp3',
            'parent' => 'top-secondary',
            'title'  => $msg,
            'meta'   => array( 'class' => 'csp3-mode-active' ),
        ) );
    }

    /**
     * Display the default template
     */
    function get_default_template(){
        $file = file_get_contents(SEED_CSP3_PLUGIN_PATH.'/themes/default/index.php');
        return $file;
    }

	/**
     * Load backend scripts
     */
    function add_backend_scripts($hook) {
        if($hook == 'settings_page_seed_csp3'){
        	wp_enqueue_script( 'seed-csp3-backend-script', plugins_url('inc/backend-scripts.js',dirname(__FILE__)), array( 'jquery' ),SEED_CSP3_VERSION, true );  
			$data = array( 
                'delete_confirm' => __( 'Are you sure you want to DELETE all subscribers?' , 'seedprod'),
                'import_confirm' => __( 'Are you sure you want to IMPORT these settings? Current settings will be erased!' , 'seedprod'),
            );
			wp_localize_script( 'seed-csp3-backend-script', 'seed_csp3_msgs', $data );
        }
        wp_enqueue_style( 'seed-csp3-adminbar-notification', SEED_CSP3_PLUGIN_URL.'inc/adminbar-style.css', false, SEED_CSP3_VERSION, 'screen');
    }

    /**
     * Import Settings
     */
    function import_settings(){
        if(check_ajax_referer('seed_csp3_import_settings')){
            $settings = $_POST['settings'];
            if(!empty($settings)) {
                $new_options = json_decode(stripslashes($settings), TRUE);
                foreach($new_options as $k=>$v){
                    update_option( $k, $v );
                }
                echo '1';
            }

            // update theme Options
            exit;
        }
    }

    /**
     *  Check License
     */
    function check_license(){
        if(check_ajax_referer('seed_csp3_check_license')){
            $api_key = $_GET['apikey'];
            $updater = new SellWP_Updater();
            $response = $updater->getRemote_information(
                $api_key,
                SEED_CSP3_VERSION,
                'seedprod-coming-soon-pro/seedprod-coming-soon-pro.php'
                );

            echo json_encode($response);
            exit();
        }
    }

    /**
     *  Callback for mailing list to be displayed in the admin area.
     */
    function refresh_list(){
        if(check_ajax_referer('seed_csp3_refresh_list')){
            $provider = $_GET['provider'];
            switch($provider){
                case 'icontact':
                    $pass = $_GET['pass'];
                    $username = $_GET['username'];
                    delete_transient('seed_csp3_icontact_lists');
                    $lists = $this->get_icontact_lists($pass,$username);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'madmini':
                    $api_key = $_GET['apikey'];
                    $username = $_GET['username'];
                    delete_transient('seed_csp3_madmini_lists');
                    $lists = $this->get_madmini_lists($api_key,$username);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'getresponse':
                    $api_key = $_GET['apikey'];
                    delete_transient('seed_csp3_getresponse_lists');
                    $lists = $this->get_getresponse_lists($api_key);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'mailchimp':
                    $api_key = $_GET['apikey'];
                    delete_transient('seed_csp3_mailchimp_lists');
                    $lists = $this->get_mailchimp_lists($api_key);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'constantcontact':
                    $username = $_GET['username'];
                    $password = $_GET['password'];
                    delete_transient('seed_csp3_constantcontact_lists');
                    $lists = $this->get_constantcontact_lists($username,$password);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'campaignmonitor':
                    $apikey = $_GET['apikey'];
                    $clientid = $_GET['clientid'];
                    delete_transient('seed_csp3_campaignmonitor_lists');
                    $lists = $this->get_campaignmonitor_lists($apikey,$clientid);
                    echo json_encode($lists);
                    exit();
                    break;
                case 'aweber':
                    delete_transient('seed_csp3_aweber_lists');
                    $lists = $this->get_aweber_lists();
                    echo json_encode($lists);
                    exit();
                    break;
                
            }
        }
    }

    /**
     *  Get List from MailChimp
     */
    function get_mailchimp_lists($apikey = null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'mailchimp' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list')){
        $lists = unserialize(get_transient('seed_csp3_mailchimp_lists'));
        if($lists === false){
            //var_dump('MAILCHIMP MISS');
            require_once SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_MCAPI.class.php';
            extract($this->get_settings());
            
            if(!isset($apikey) && isset($mailchimp_api_key)){
                $apikey = $mailchimp_api_key;
            }

            if(empty($apikey)){
                return array();
            }

            $api = new seed_csp3_MCAPI($apikey);

            $response = $api->lists();

            if ($response['total'] === 0){
                $lists['false'] = __("No lists Found", 'seedprod');
                return $lists;
            }
            if ($api->errorCode){
                $lists['false'] = __("Unable to load MailChimp lists, check your API Key.", 'seedprod');
            } else {

                foreach ($response['data'] as $k => $v){
                    $lists[$v['id']] = $v['name'];
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_mailchimp_lists',serialize( $lists ),86400);
                }
            }
        }}
        return $lists;
    }

    /**
     *  Get List from iContact
     */
    function get_icontact_lists($pass = null, $username = null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'icontact' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_icontact_lists'));
        //$lists = false;
        if($lists === false){
            require_once SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_iContactApi.php';
            extract($this->get_settings());
            
            if(!isset($pass) && isset($icontact_password)){
                $pass = $icontact_password;
            }

            if(!isset($username) && isset($icontact_username)){
                $username = $icontact_username;
            }

            if(!empty($pass) && !empty($username)){
                seed_csp3_iContactApi::getInstance()->setConfig(array(
                    'appId'       => 'puD4TZWs2kKlKZLZZgD7IAUiqPSYPIvd', 
                    'apiPassword' => $pass, 
                    'apiUsername' => $username
                ));

                $oiContact = seed_csp3_iContactApi::getInstance();


                $response = $oiContact->getLists();
                

            }

            if (empty($response)){
                $lists['false'] = __("No lists Found", 'seedprod');
                return $lists;
            } else {

                foreach ($response as $k => $v){
                    $lists[$v->listId] = $v->name;
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_icontact_lists',serialize( $lists ),86400);
                }
            }
        }}
        return $lists;
    }

    /**
     *  Get List from MadMini
     */
    function get_madmini_lists($apikey = null, $username = null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'madmini' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_madmini_lists'));
        //$lists = false;
        if($lists === false){
            //var_dump('MadMimi Miss');
            require_once SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_MadMimi.class.php';
            extract($this->get_settings());
            
            if(!isset($apikey) && isset($madmini_api_key)){
                $apikey = $madmini_api_key;
            }

            if(!isset($username) && isset($madmini_username)){
                $username = $madmini_username;
            }

            if(!empty($apikey) && !empty($username)){
                $api = new seed_csp3_MadMimi($username,$apikey);

                $response = $api->Lists();

                if($response == 'Unable to authenticate'){
                    $lists['false'] = __("Unable to authenticate", 'seedprod');
                    return $lists;
                }
            }

            if (empty($response)){
                $lists['false'] = __("No lists Found", 'seedprod');
                return $lists;
            } else {
                $response = json_decode($response);
                foreach ($response as $k => $v){
                    $lists[$v->name] = $v->name;
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_madmini_lists',serialize( $lists ),86400);
                }
            }
        }}
        return $lists;
    }


    /**
     *  Get List from GetResponse
     */
    function get_getresponse_lists($apikey = null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'getresponse' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_getresponse_lists'));
        if($lists === false){
            require_once SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_GetResponseAPI.class.php';
            extract($this->get_settings());
            
            if(!isset($apikey) && isset($getresponse_api_key)){
                $apikey = $getresponse_api_key;
            }

            if(empty($apikey)){
                return array();
            }

            $api = new seed_csp3_GetResponse($apikey);

            $response = (array)$api->getCampaigns();

            if (empty($response)){
                $lists['false'] = __("No lists Found", 'seedprod');
                return $lists;
            } else {

                foreach ($response as $k => $v){
                    $lists[$k] = $v->name;
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_getresponse_lists',serialize( $lists ),86400);
                }
            }
        }}
        return $lists;
    }


    /**
     *  Get List from Constant Contact
     */
    function get_constantcontact_lists($username=null,$password=null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'constantcontact' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_constantcontact_lists'));
        if($lists === false){
            if (class_exists('cc')) {
                //trigger_error("Duplicate: Another Constant Contact client library is already in scope.", E_USER_WARNING);
            }
            else {
                require_once SEED_CSP3_PLUGIN_PATH.'lib/seed_csp3_class.cc.php';
            }
            
            extract($this->get_settings());
            
            if(!isset($username) && isset($constantcontact_username)){
                $username = $constantcontact_username;
                $password = $constantcontact_password;
            }

            if(empty($username) || empty($password)){
                return array();
            }
            
            $api = new cc($username, $password);

            $response = $api->get_all_lists();
            if($response){
                foreach($response as $k => $v){
                    $lists[$v['id']] = $v['Name'];
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_constantcontact_lists',serialize( $lists ),86400);
                } 
            }else{
                $lists['false'] = __("Unable to load Constant Contact lists", 'seedprod');          
            }

        }}
        return $lists;
    }


    /**
     *  Get List from Campaign Monitor
     */
    function get_campaignmonitor_lists($apikey = null,$clientid = null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'campaignmonitor' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_campaignmonitor_lists'));
        if($lists === false){

            if (class_exists('CS_REST_Clients') ) {
                //trigger_error("Duplicate: Another Campaign Moniter client library is already in scope.", E_USER_WARNING);
            }
            else {
                require_once SEED_CSP3_PLUGIN_PATH.'lib/campaign_monitor/csrest_clients.php';
            }

            
            
            extract($this->get_settings());

            if(!isset($apikey) && isset($campaignmonitor_api_key)){
                $apikey = $campaignmonitor_api_key;
            }
            if(!isset($clientid) && isset($campaignmonitor_clientid)){
                $clientid = $campaignmonitor_clientid;
            }

            if(empty($apikey) || empty($clientid)){
                return array();
            }

            $api = new CS_REST_Clients($clientid, $apikey);
            
            $response = $api->get_lists();

            if($response->was_successful()){
                foreach($response->response as $k => $v){
                    $lists[$v->ListID] = $v->Name;
                }
                if(!empty($lists)){
                   set_transient('seed_csp3_campaignmonitor_lists',serialize( $lists ),86400);
                } 
            }else{
                $lists['false'] = __("Unable to load Campaign Monitor lists", 'seedprod');          
            }

        }}
        return $lists;
    }


    /**
     *  Get List from Campaign Monitor
     */
    function get_campaignmonitor_clients($apikey=null){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $clients = array();
        if($o['emaillist'] == 'campaignmonitor' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_campaingmonitor_client' )){
        $clients = unserialize(get_transient('seed_csp3_campaignmonitor_clients'));
        if($clients === false){
            if (class_exists('CS_REST_General') ) {
                //trigger_error("Duplicate: Another Campaign Moniter client library is already in scope.", E_USER_WARNING);
            }
            else {
                require_once SEED_CSP3_PLUGIN_PATH.'lib/campaign_monitor/csrest_general.php';
            }
            
            
            extract($this->get_settings());
            
            if(!isset($apikey) && isset($campaignmonitor_api_key)){
                $apikey = $campaignmonitor_api_key;
            }

            if(empty($apikey)){
                return array();
            }
            
            $api = new CS_REST_General($apikey);

            $response = $api->get_clients();

            if($response->was_successful()) {
                foreach($response->response as $k => $v){
                    $clients[$v->ClientID] = $v->Name;
                }
                if(!empty($clients)){
                   set_transient('seed_csp3_campaignmonitor_clients',serialize( $clients ),86400);
                } 
            }else{
                $clients['false'] = __("Unable to load Campaign Monitor clients", 'seedprod');          
            }

        }}
        return $clients;
    }


    /**
     *  Callback for Campaign Monitor client refresh
     */
    function campaingmonitor_client_refresh(){
        if(check_ajax_referer('seed_csp3_campaingmonitor_client')){
            $apikey = $_GET['apikey'];
            delete_transient('seed_csp3_campaignmonitor_clients');
            $clients = $this->get_campaignmonitor_clients($apikey);
            echo json_encode($clients);
            exit();
        }
    }

    /**
     *  Get List from AWeber
     */
    function get_aweber_lists(){
        global $seed_csp3;
        $o = $seed_csp3->get_settings();
        $lists = array();
        if($o['emaillist'] == 'aweber' || ( defined('DOING_AJAX') && DOING_AJAX && isset($_GET['action']) && $_GET['action'] == 'seed_csp3_refresh_list' )){
        $lists = unserialize(get_transient('seed_csp3_aweber_lists'));
        if($lists === false){
            require_once SEED_CSP3_PLUGIN_PATH.'lib/aweber_api/aweber_api.php';
            
            extract($this->get_settings());

            $aweber_auth = get_option('seed_csp3_aweber_auth');
            if(!empty($aweber_auth)){
                extract($aweber_auth);
                $consumerKey = $consumer_key;
                $consumerSecret = $consumer_secret; 
            }

            if(empty($consumerKey) || empty($consumerSecret)){
                return array();
            }

            try{
                $aweber = new AWeberAPI($consumerKey, $consumerSecret); 
                $account = $aweber->getAccount($access_key, $access_secret);
            } catch (Exception $e) {}

            foreach($account->lists as $list) {
                $lists[$list->id] = $list->name;
            }
            
            if(!empty($lists)){
                set_transient('seed_csp3_aweber_lists',serialize( $lists ),86400);
            } else{
                $lists['false'] = __("Unable to load Aweber lists", 'seedprod');
            }

        }
        }
        return $lists;
    }



    /**
     *  Callback for Aweber Authorization
     */
    function aweber_auth(){
        if(check_ajax_referer('seed_csp3_aweber_auth')){
            require_once SEED_CSP3_PLUGIN_PATH.'lib/aweber_api/aweber_api.php';
            $authorization_code = urldecode($_GET['auth_code']);
            try {
                $auth = AWeberAPI::getDataFromAweberID($authorization_code);
                list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $auth;

                update_option('seed_csp3_aweber_auth', array('consumer_key'=>$consumerKey,'consumer_secret'=>$consumerSecret,'access_key'=>$accessKey,'access_secret'=>$accessSecret));
                echo '200';
            }
            catch(AWeberAPIException $exc) {
                echo '500';
            }
            exit;
        }
    }

    /**
     * Add user to db
     */
    function add_subscriber($email,$fname,$lname){
        global $seed_csp3_post_result;

        extract($this->get_settings());
        // Record reference
        $ref = '-1';
        if(!empty($_REQUEST['ref'])){
            $ref = intval($_REQUEST['ref'],36)-1000;
        }

        // Get gravatar stuff TODO: Fix this call
        // $r = 'http://www.gravatar.com/'.md5($email).'.json';
        // $grav = wp_remote_get( $r );
        // $insights = '';
        // if(!empty($grav['response']['code']) && $grav['response']['code'] == '200'){
        //     $insights = $grav['body'];
        //     $profile = json_decode( $insights );
        //     if(empty($fname)){
        //         if ( is_array( $profile ) && isset( $profile['entry'] ) && isset($profile['entry'][0]['name']['givenName'])  ){
        //            $fname =  $profile['entry'][0]['name']['givenName'];
        //         }
        //     }
        //     if(empty($lname)){
        //         if ( is_array( $profile ) && isset( $profile['entry'] ) && isset($profile['entry'][0]['name']['familyName'])  ){
        //            $lname =  $profile['entry'][0]['name']['familyName'];
        //         }
        //     }

        // }

        // Get visitors County TODO Fix time issue
        // $r = 'http://api.hostip.info/get_json.php?ip='.$this->get_ip();
        // $location = wp_remote_get( $r );
        // if(!empty($location['response']['code']) && $location['response']['code'] == '200'){
        //     $location_info = $location['body'];
        // }

        $location_info = '';
    
        //var_dump($insights);

        global $wpdb;
        $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;

        $sql = "SELECT * FROM $tablename WHERE email = %s";
        $safe_sql = $wpdb->prepare($sql,$email);
        $select_result =$wpdb->get_row($safe_sql);
        $insights = '';


        if(empty($select_result->email) || $select_result->email != $email){
            $values = array(
                'email' => $email,
                'referrer' => $ref,
                'ip' => $this->get_ip(),
                'fname' => $fname,
                'lname' => $lname,
                'insights' => $insights,
                'location' => $location_info,
            );
            $format_values = array(
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            );
            $insert_result = $wpdb->insert(
                $tablename,
                $values,
                $format_values
            );
            // Record ref
            if(!empty($ref)){

                $sql = "UPDATE $tablename SET conversions = conversions + 1 WHERE id = %d";
                $safe_sql = $wpdb->prepare($sql,$ref);
                $update_result =$wpdb->get_var($safe_sql);
            }

        }

        // if(isset($insert_result) && $insert_result != false){
        //     $message = "You have a new email subscriber: ". $email;
        //     $result = wp_mail( get_option('admin_email'), __('New Email Subscriber', 'seedprod'), $message);
        //     $ref = $wpdb->insert_id+1000;
        //     $seed_csp3_post_result['ref'] = base_convert($ref, 10, 36);
        // }

        if(isset($insert_result) && $insert_result != false){
            if($emaillist == 'database' && !empty($database_notifications)){
                $message = "You have a new email subscriber: ". $email;
    
                if(empty($database_notifications_emails)) {
                    $result = wp_mail( get_option('admin_email'), __('New Email Subscriber', 'seedprod'), $message);
                }else{
                    $result = wp_mail( $database_notifications_emails, __('New Email Subscriber', 'seedprod'), $message);
                }
            }

            if(empty($seed_csp3_post_result['status']))
                $seed_csp3_post_result['status'] = '200';
            $ref = $wpdb->insert_id+1000;
            $seed_csp3_post_result['ref'] = base_convert($ref, 10, 36);
        }else{
            $seed_csp3_post_result['status'] = '600';
            $ref = $select_result->id+1000;
            $seed_csp3_post_result['referrer_id'] = base_convert($ref, 10, 36);
            $seed_csp3_post_result['clicks'] = '0';
            if(!empty($select_result->clicks)){
                $seed_csp3_post_result['clicks'] = $select_result->clicks;
            }
            $seed_csp3_post_result['subscribers'] = '0';
            if(!empty($select_result->conversions)){
                $seed_csp3_post_result['subscribers'] = $select_result->conversions;
            }
        }
    }


    /**
     * Subscribe User to Mailing List or return an error.
     */
    function subscribe_callback(){
        global $seed_csp3_post_result;
        extract($this->get_settings());
        $email = '';
        if(!empty($_REQUEST['email'])){
            $email = $_REQUEST['email'];
        }

        $fname = '';
        if(!empty($_REQUEST['fname'])){
            $fname = $_REQUEST['fname'];
        }

        $lname = '';
        if(!empty($_REQUEST['lname'])){
            $lname = $_REQUEST['lname'];
        }
        
        // If not email exit and return 400
        if($emaillist != 'gravityforms'){
            if(is_email($email) != $email || empty($email)){
                 $seed_csp3_post_result['status'] = '400';
                 $emaillist = '';
            }
        }



        switch ($emaillist) {
            case 'database':
                $this->add_subscriber($email,$fname,$lname);
                break;
            case 'gravityforms':
                if($gravityforms_enable_thankyou_page){
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';
                }
                break;

            case 'wysija':
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if(is_plugin_active('wysija-newsletters/index.php') && class_exists('WYSIJA')){
                    $list_id = $wysija_list_id;

                    //check if the email address is recorded in wysija
                    $modelUser=&WYSIJA::get('user','model');
                    $userData=$modelUser->getOne(array('user_id'),array('email'=>$email));

                    if(!$userData){
                        //record the email in wysija
                        $userHelper=&WYSIJA::get('user','helper');
                        $data=array('user'=>array('email'=>$email,'firstname'=>$fname,'lastname'=>$lname),'user_list'=>array('list_ids'=>array($list_id)));
                        $test = $userHelper->addSubscriber($data);
                        $this->add_subscriber($email,$fname,$lname);
                        if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';
                    }else{
                        $user_id=$userData['user_id'];
                        $userHelper=&WYSIJA::get('user','helper');
                        $userHelper->addToLists(array($list_id), $user_id);
                        $this->add_subscriber($email,$fname,$lname);
                        if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';
                    }
                }
                
                break;
            case 'sendy':
                $url = $sendy_url;
                $list = $sendy_list_id;
                if(!empty($fname)){
                    $name = $fname.' '.$lname;
                }else{
                    $name = null;
                }
                $args = array('timeout' => 45,'body' => array('name'=>$name,'email'=>$email,'list'=> $list,'boolean'=>true));
                if(!empty($url) && !empty($list)){
                $r = wp_remote_post( trailingslashit($url).'subscribe', $args );
                }

                if ( is_wp_error( $response ) ) {
                    $seed_csp3_post_result['status'] = '500';
                }else{
                    $this->add_subscriber($email,$fname,$lname);
                    $body = wp_remote_retrieve_body($r);
                    if($body == '1'){
                        $seed_csp3_post_result['status'] = '200';
                    }elseif($body == 'Already subscribed.'){
                        $seed_csp3_post_result['status'] = $body;
                    }
                    
                }

                break;
            case 'mailchimp':
                require_once SEED_CSP3_PLUGIN_PATH.'/lib/seed_csp3_MCAPI.class.php';
                $apikey = $mailchimp_api_key;
                $api = new seed_csp3_MCAPI($apikey);
                $listId = $mailchimp_listid;
  
                if(!empty($mailchimp_enable_double_optin)){
                    $double_optin = true;
                }else{
                    $double_optin = false;
                }
                if(!empty($mailchimp_welcome_email)){
                    $welcome_email=true;
                }else{
                    $welcome_email=false;
                }
                if(!empty($mailchimp_replace_interests)){
                    $replace_interests=true;
                }else{
                    $replace_interests=false;
                }
                $merge_vars = array('FNAME'=>$fname, 'LNAME'=>$lname);
                if(!empty($mailchimp_groups) && !empty($mailchimp_group_name)){
                    $merge_vars['GROUPINGS'] = array(
                        array('name'=>$mailchimp_group_name, 'groups'=>$mailchimp_groups),
                        );
                }

                $retval = $api->listSubscribe( $listId, $email, $merge_vars,$email_type='html', $double_optin,false,$replace_interests,$welcome_email);

                if($retval == false){
                    if($api->errorCode == 214){
                        $this->add_subscriber($email,$fname,$lname);
                    }else{
                        $seed_csp3_post_result['status'] = addslashes($api->errorMessage);
                    }
                }else {
                    $this->add_subscriber($email,$fname,$lname);
                    if($seed_csp3_post_result['status'] == 600)
                        $seed_csp3_post_result['status'] ='200';

                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';

                }  
                break;
            case 'madmini':
                require_once SEED_CSP3_PLUGIN_PATH.'/lib/seed_csp3_MadMimi.class.php';
                $apikey = $madmini_api_key;
                $username = $madmini_username;
                $api = new seed_csp3_MadMimi($username,$apikey);
                $listId = $madmini_listid;

                $user = array(
                    'email' => $email, 
                    'firstName' => $fname, 
                    'lastName' => $lname,  
                    'add_list' => $listId);

                $response = $api->AddUser($user);

                if(empty($response)){
                    $seed_csp3_post_result['status'] = '500';
                }else {
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';
                }  
                break;
            case 'icontact':
                require_once SEED_CSP3_PLUGIN_PATH.'/lib/seed_csp3_iContactApi.php';
                $pass = $icontact_password;
                $username = $icontact_username;
                $listId = $icontact_listid;
                

                seed_csp3_iContactApi::getInstance()->setConfig(array(
                    'appId'       => 'puD4TZWs2kKlKZLZZgD7IAUiqPSYPIvd', 
                    'apiPassword' => $pass, 
                    'apiUsername' => $username
                ));

                $oiContact = seed_csp3_iContactApi::getInstance();

                

                $user = array(
                    'email' => $email, 
                    'firstName' => $fname, 
                    'lastName' => $lname,  
                    'add_list' => $listId);

                $contact = $oiContact->addContact($email, $sStatus = 'normal', $sPrefix = null, $sFirstName = $fname, $sLastName = $lname);
                $response = $oiContact->subscribeContactToList($contact->contactId, $listId, $sStatus = 'normal');

                if(empty($response)){
                    $seed_csp3_post_result['status'] = '500';
                }else {
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';
                }  
                break;

            case 'infusionsoft':

                if(!class_exists('xmlrpc_client')){
                    require_once SEED_CSP3_PLUGIN_PATH.'/lib/xmlrpc-2.0/lib/xmlrpc.inc';
                }
                $app = $infusionsoft_app;
                $api_key = $infusionsoft_api_key;

                
                ###Set our Infusionsoft application as the client###
                $client = new xmlrpc_client("https://".$app.".infusionsoft.com/api/xmlrpc");

                ###Return Raw PHP Types###
                $client->return_type = "phpvals";

                ###Dont bother with certificate verification###
                $client->setSSLVerifyPeer(FALSE);

                ###Our API Key###
                $key = $api_key;


                ###Build a Key-Value Array to store a contact###
                $contact = array(
                        "FirstName" =>  $fname,
                        "LastName" =>   $lname,
                        "Email" =>      $email,
                    );

                ###Set up the call###
                $call = new xmlrpcmsg("ContactService.add", array(
                        php_xmlrpc_encode($key),        #The encrypted API key
                        php_xmlrpc_encode($contact)     #The contact array
                    ));

                ###Send the call###
                    $result = $client->send($call);

                ###Check the returned value to see if it was successful and set it to a variable/display the results###
                if(!$result->faultCode()) {
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';
                    // $conID = $result->value();
                    // print "Contact added was " . $conID;
                    // print "<BR>";
                } else {
                    $seed_csp3_post_result['status'] = '500';
                    // print $result->faultCode() . "<BR>";
                    // print $result->faultString() . "<BR>";die();
                } 
                break;
            case 'getresponse':
                require_once SEED_CSP3_PLUGIN_PATH.'/lib/seed_csp3_GetResponseAPI.class.php';
                $apikey = $getresponse_api_key;
                $api = new seed_csp3_GetResponse($apikey);
                $listId = $getresponse_listid;

                $name = $fname.' '.$lname;

                $response = $api->addContact( $listId,$name,$email);

                if(empty($response)){
                    $seed_csp3_post_result['status'] = '500';
                }else {
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                        $seed_csp3_post_result['status'] ='200';
                }  
                break;
            case 'constantcontact':
                if (class_exists('cc')) {
                    //trigger_error("Duplicate: Another Constant Contact client library is already in scope.", E_USER_WARNING);
                }
                else {
                    require_once SEED_CSP3_PLUGIN_PATH.'/lib/seed_csp3_class.cc.php';
                }

                $username = $constantcontact_username;
                $password = $constantcontact_password;
                
                $api = new cc($username, $password);
                $listId = $constantcontact_listid;

                $contact_list = $listId;
                $extra_fields = array();
                
                // check if the contact exists
                $contact = $api->query_contacts($email);
                
                // uncomment this line if the user makes the action themselves
                $api->set_action_type('contact');  
                $extra_fields = array(
                    'FirstName' => $fname,
                    'LastName' => $lname,
                );
                if($contact){
                    $contact_ext = $api->get_contact($contact['id']);
                    if (in_array($contact_list, $contact_ext['lists'])) {
                        $seed_csp3_post_result['status'] = '500';
                        break;
                    }
                    $lists = $contact_ext['lists'] + array($contact_list);
                    $updated = $api->update_contact($contact['id'],$email, $lists, $extra_fields);
                    if($updated){
                        $this->add_subscriber($email,$fname,$lname);
                        if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';
                    }else{
                        $seed_csp3_post_result['status'] = '500';
                    };
                }else{
                    $new_id = $api->create_contact($email, $contact_list, $extra_fields);             
                    if($new_id){
                        $this->add_subscriber($email,$fname,$lname);
                        if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';
                    }else{
                        $seed_csp3_post_result['status'] = '500';
                    };
                };
                break;
            case 'campaignmonitor':
                if (class_exists('CS_REST_Subscribers') ) {
                    //trigger_error("Duplicate: Another Campaign Moniter client library is already in scope.", E_USER_WARNING);
                }
                else {
                    require_once SEED_CSP3_PLUGIN_PATH.'/lib/campaign_monitor/csrest_subscribers.php';
                }
                

                $apikey = $campaignmonitor_api_key;
                $listid = $campaignmonitor_listid;
                
                $api = new CS_REST_Subscribers($listid, $apikey);
                
                $response = $api->add(array(
                    'EmailAddress' => $email,
                    'Name' => $fname.' '.$lname,
                    // 'CustomFields' => array(
                    //     array(
                    //         'Key' => 'Field Key',
                    //         'Value' => 'Field Value'
                    //     )
                    // ),
                    'Resubscribe' => true
                )); 
                
                if($response->was_successful()){
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';
                }else{       
                    $seed_csp3_post_result['status'] = '500';
                };
                break;
            case 'aweber':
                require_once SEED_CSP3_PLUGIN_PATH.'/lib/aweber_api/aweber_api.php';

                $aweber_auth = get_option('seed_csp3_aweber_auth');
                extract($aweber_auth);

                $consumerKey = $consumer_key;
                $consumerSecret = $consumer_secret;  
                $aweber = new AWeberAPI($consumerKey, $consumerSecret); 
                
                $list_id = $aweber_listid;

                try {
                    $account = $aweber->getAccount($access_key, $access_secret);
                    $account_id     = $account->id;
                    $listURL = "/accounts/{$account_id}/lists/{$list_id}";
                    $list = $account->loadFromUrl($listURL);

                    # create a subscriber
                    $params = array(
                        'email' => $email,
                        'name' => $fname.' '.$lname,
                        'ip_address' => $this->get_ip(),
                        // 'ad_tracking' => 'coming_soon_pro',
                        // 'last_followup_message_number_sent' => 1,
                        // 'misc_notes' => 'my cool app',
                        // 'name' => 'John Doe',
                        // 'custom_fields' => array(
                        //     'Car' => 'Ferrari 599 GTB Fiorano',
                        //     'Color' => 'Red',
                        // ),
                    );
                    $subscribers = $list->subscribers;
                    $new_subscriber = $subscribers->create($params);

                    # success!
                    $this->add_subscriber($email,$fname,$lname);
                    if(empty($seed_csp3_post_result['status']))
                            $seed_csp3_post_result['status'] ='200';

                } catch(AWeberAPIException $exc) {
                    if($exc->status == '400'){
                        $this->add_subscriber($email,$fname,$lname);
                        $seed_csp3_post_result['status'] = '600';
                    }else{
                        $seed_csp3_post_result['status'] = '500';
                    }
                    // var_dump($exc);
                    // print "<h3>AWeberAPIException:</h3>";
                    // print " <li> Type: $exc->type              <br>";
                    // print " <li> Msg : $exc->message           <br>";
                    // print " <li> Docs: $exc->documentation_url <br>";
                    // print "<hr>";
                }
                break;
        } 


        // If not a POST echo ajax msg
        if($_POST || (isset($_GET['gf']) && $_GET['gf'] == '1')) {

            if(!empty($gravityforms_enable_thankyou_page) && $emaillist == 'gravityforms' && isset($_GET['email']) == false){
                $seed_csp3_post_result['post'] = false;
            }else{
                $seed_csp3_post_result['post'] = true;

            }
            //$seed_csp3_post_result['post'] = false;
            //var_dump($seed_csp3_post_result);
        }else{
            echo json_encode($seed_csp3_post_result);          
            exit;  
        }
    }

    /**
     * Get Google Font CSS
     */
    function get_google_font_css($text_font,$headline_font,$button_font){
        $output = '';
        if($text_font != 'inherit'){
            if($text_font[0] != '_' && !empty($text_font)){
                $output .= "<link href='//fonts.googleapis.com/css?family={$text_font}' rel='stylesheet' type='text/css'>\n";
                
            }
        }
        if($headline_font != 'inherit'){
            if($headline_font[0] != '_' && !empty($headline_font)){
                $output .= "<link href='//fonts.googleapis.com/css?family={$headline_font}' rel='stylesheet' type='text/css'>\n";
                
            }
        }
        if($button_font != 'inherit'){
            if($button_font[0] != '_' && !empty($button_font)){
                $output .= "<link href='//fonts.googleapis.com/css?family={$button_font}' rel='stylesheet' type='text/css'>\n";
                
            }
        }
 
        return $output;

    }

    /**
     * Get Font Family
     */
    function get_font_family($font){
        $fonts                    = array();
        $fonts['_arial']          = 'Helvetica, Arial, sans-serif';
        $fonts['_arial_black']    = 'Arial Black, Arial Black, Gadget, sans-serif';
        $fonts['_georgia']        = 'Georgia,serif';
        $fonts['_helvetica_neue'] = '"Helvetica Neue", Helvetica, Arial, sans-serif';
        $fonts['_impact']         = 'Charcoal,Impact,sans-serif';
        $fonts['_lucida']         = 'Lucida Grande,Lucida Sans Unicode, sans-serif';
        $fonts['_palatino']       = 'Palatino,Palatino Linotype, Book Antiqua, serif';
        $fonts['_tahoma']         = 'Geneva,Tahoma,sans-serif';
        $fonts['_times']          = 'Times,Times New Roman, serif';
        $fonts['_trebuchet']      = 'Trebuchet MS, sans-serif';
        $fonts['_verdana']        = 'Verdana, Geneva, sans-serif';
        $google_fonts = get_option('seed_csp3_google_font_families');

        if(is_array($google_fonts))
            $fonts = $fonts + $google_fonts;

        if(!empty($fonts[$font])){
            $font_family = $fonts[$font];
        }else{
            $font_family = 'Helvetica, Arial, sans-serif';
        }
            
        echo $font_family;
    }


    /**
     * Display the coming soon page
     */
    function render_comingsoon_page() {
    	extract($this->get_settings());

        //If POST process
        if($_POST || (!empty($_GET['gf']) && $_GET['gf'] == '1'))
            $this->subscribe_callback();


        if(!empty($countdown_date) && !empty($enable_countdown) && !empty($countdown_launch)){

            if(empty($countdown_date['hour'])){
                $countdown_date['hour'] = '00';
            }
            if(empty($countdown_date['minute'])){
                $countdown_date['minute'] = '00';
            }
            $tz = get_option('timezone_string');
            if(empty($tz)){
                $tz = 'GMT';
            }
            date_default_timezone_set($tz);

            $launch_date = new DateTime($countdown_date['year'].'-'.$countdown_date['month'].'-'.$countdown_date['day'].' '.$countdown_date['hour'].':'.$countdown_date['minute'].':00');

            // Launch this biatch
            if($launch_date <= new DateTime()){

                $o = get_option('seed_csp3_settings_1');
                $o['status'] = 0;
                update_option('seed_csp3_settings_1', $o);
                return false;
            }
        }

        //IF Referrer record it
        if(isset($_GET['ref'])){

            $id = intval($_GET['ref'],36)-1000;
            
            global $wpdb;
            $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
            $sql = "UPDATE $tablename SET clicks = clicks + 1 WHERE id = %d";
            $safe_sql = $wpdb->prepare($sql,$id);
            $update_result =$wpdb->get_var($safe_sql);

        }

    	// Exit if feed and feedburner is enabled.
        if(is_feed() && $emaillist == 'feedburner' ){
            return false;
        }

        // Exit if admin or feed
    	if(is_admin()){
    		return false;
    	}

        if(empty($_GET['cs_preview'])){
            $_GET['cs_preview'] = false;
        }
        
        if(empty($_GET['bypass'])){
            $_GET['bypass'] = false;
        }

    	//Check for Client View
        if (isset($_COOKIE['wp-client-view']) && ((strtolower(basename($_SERVER['REQUEST_URI'])) == trim(strtolower($client_view_url))) || (strtolower($_GET['bypass']) == trim(strtolower($client_view_url))) ) && !empty($client_view_url)) {
            header( 'Location: '.home_url() ) ;
        }

        // Don't show Coming Soon Page if client View is active
        $client_view_hash = md5($client_view_url . get_current_blog_id());
        if (isset($_COOKIE['wp-client-view']) && $_COOKIE['wp-client-view'] == $client_view_hash && $_GET['cs_preview'] != 'true' && !empty($client_view_url)) {
            nocache_headers();
            header( 'Cache-Control: max-age=0');
            return false;
        }else{
            setcookie("wp-client-view", "", time()-3600);
        }

        // If Client view is not empty and we are on the client view url set cookie.
        if(!empty($client_view_url)){
            if(empty($_GET['bypass'])){
                $_GET['bypass'] = '';
            }

            if((strtolower(basename($_SERVER['REQUEST_URI'])) == trim(strtolower($client_view_url))) || (strtolower($_GET['bypass']) == trim(strtolower($client_view_url)))) {  
                setcookie("wp-client-view", $client_view_hash , time()+21600, COOKIEPATH, COOKIE_DOMAIN, false);
                header( 'Location: '.home_url() ) ;
                exit();
            } 
        }

                // Check if Preview
        $is_preview = false;
        if ((isset($_GET['cs_preview']) && $_GET['cs_preview'] == 'true')) {
            $is_preview = true;
        }

        // Check for excluded IP's
        $ip = $this->get_ip();
        $exclude_ips = explode("\r\n",$ip_access);
        if(is_array($exclude_ips) && in_array($ip,$exclude_ips) && $_GET['cs_preview'] != 'true'){
            return false;
        }

        // Check for included pages
        if(!empty($include_url_pattern) && preg_match("/{$include_url_pattern}/",$_SERVER['REQUEST_URI']) == 0 && $is_preview == false){
            return false;
        }

        // Check for excluded pages
        if(!empty($exclude_url_pattern) && preg_match("/{$exclude_url_pattern}/",$_SERVER['REQUEST_URI']) > 0 && $is_preview == false){
            return false;
        }

        // Exit if a custom login page
        if(preg_match("/login/i",$_SERVER['REQUEST_URI']) > 0 && $is_preview == false){
            return false;
        }

        //Exit if wysija double opt-in
        if(preg_match("/wysija/i",$_SERVER['REQUEST_URI']) > 0 && $is_preview == false){
            return false;
        }



        // Set values if not set
        if(!isset($include_page))
            $include_page = '-1';
        if(!isset($include_roles))
            $include_roles = '0';

        //Limit to one page
        if($is_preview === false){
            if($include_page != '-1'){
                if(!is_page($include_page)){
                    return false;
                }
            }
        }

        //Limit access by role

        if(!is_page($include_page)){
            if($is_preview === false){
                if(!empty($include_roles)){
                    foreach($include_roles as $v){
                        if($v == '0' && is_user_logged_in()){
                            return false;
                        }
                        if(current_user_can($v)){
                            return false;
                        }

                    }
                }elseif(is_user_logged_in()){
                    return false;
                }
            }
        }


        

        // Finally check if we should show the coming soon page.
        //if ((isset($_GET['cs_preview']) && $_GET['cs_preview'] == 'true')) {
        
            $this->comingsoon_rendered = true;
            
            // set headers
            if($status == '2'){
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Status: 503 Service Temporarily Unavailable');
                header('Retry-After: 86400'); // retry in a day
                $csp3_maintenance_file = WP_CONTENT_DIR."/maintenance.php";
                if(!empty($enable_maintenance_php) and file_exists($csp3_maintenance_file)){
                    include_once( $csp3_maintenance_file );
                    exit();
                }
            }
            
            // render template tags
            if (empty($template) || isset($disable_code_template)) {
                $template = $this->get_default_template();
            }
            require_once( SEED_CSP3_PLUGIN_PATH.'/themes/default/functions.php' );
            $template_tags = array(
                '{Title}' => seed_cs3_title(),
                '{MetaDescription}' => seed_cs3_metadescription(),
                '{Privacy}' => seed_cs3_privacy(),
                '{Favicon}' => seed_cs3_favicon(),
                '{CustomCSS}' => seed_cs3_customcss(),
                '{Head}' => seed_cs3_head(),
                '{Footer}' => seed_cs3_footer(),
                '{Logo}' => seed_cs3_logo(),
                '{Headline}' => seed_cs3_headline(),
                '{Description}' => seed_cs3_description(),
                '{Form}' => seed_cs3_form(),
                '{SocialProfiles}' => seed_cs3_socialprofiles(),
                '{Credit}' => seed_cs3_credit(),
                '{ProgressBar}' => seed_cs3_progressbar(),
                '{Countdown}' => seed_cs3_countdown(),
                );
            $template = str_replace("</body>", "", $template);
            $template = str_replace("</html>", "", $template);
			echo strtr($template, $template_tags);


            // Enable wp_footer, nasty hack fix in next major version
            if($emaillist == 'gravityforms'){
                $enable_wp_head_footer = '1';
            }

            if(!empty($enable_wp_head_footer)){
                echo "<!-- wp_footer() -->\n";
                wp_footer();
            }

            echo "</body></html>";

            exit();
        
    }

    /**
     * Create Database to Store Emails
     */
    function subscriber_database_setup() {
        global $wpdb;
        $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
        //if( $wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename ){
            $sql = "CREATE TABLE `$tablename` (
              id int(11) unsigned NOT NULL AUTO_INCREMENT,
              email varchar(100) DEFAULT NULL,
              fname varchar(100) DEFAULT NULL,
              lname varchar(100) DEFAULT NULL,
              clicks int(11) NOT NULL DEFAULT '0',
              conversions int(11) NOT NULL DEFAULT '0',
              referrer int(11) NOT NULL DEFAULT '0',
              ip varchar(40) DEFAULT NULL,
              insights text DEFAULT NULL,
              location text DEFAULT NULL,
              created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY  (id)
            );";
        
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            dbDelta($sql);
        //}
        
    }

	/*
	 * Available Email List Providers
	 */
    function email_list_providers() {
		$providers = array(
            'none' => __('Do not display an Email SignUp','seedprod'),
			'database' => __('Database','seedprod'),
            'feedburner' => __('FeedBurner','seedprod'),
		);
	 
		if(has_filter('seed_csp3_email_list_providers')) {
			$providers = apply_filters('seed_csp3_email_list_providers', $providers);
		}
	 
		return $providers;
	}

    /*
     * Export Subscribers
     */
    function export_all_subscribers(){
        ob_get_clean();
        global $wpdb;
        $csv_output = '';
        $csv_output .= "Email,Fname,Lname,Clicks,Conversions,City,Country,IP,Created";
        $csv_output .= "\n";
        $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
        $sql = "SELECT * FROM " . $tablename;
        $results = $wpdb->get_results($sql);
        foreach ($results as $result) {
            if(!empty($result->location)){
                $location = json_decode($result->location,true);
                $city = $location['city'];
                $country = $location['country_name'];
            }else{
                $city = '';
                $country = '';
            }
           $csv_output .= $result->email ."," . $result->fname . ",". $result->lname . "," . $result->clicks . "," . $result->conversions . "," . $city . "," . $country . "," . $result->ip . "," . $result->created ."\n";
        }

        $filename = "subscribers_".date("Y-m-d_H-i",time());
        header("Content-type: text/plain");
        header("Content-disposition: attachment; filename=".$filename.".csv");
        print $csv_output;
        die();
    }   

    /*
     * Delete Subscribers
     */
    function delete_all_subscribers(){
        if (current_user_can( 'delete_users' )) {
            global $wpdb;
            $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
            $sql = "TRUNCATE " . $tablename;
            $result = $wpdb->query($sql);
            if($result){
                return true;
            }
        }
    } 

    /*
     * Delete Selected Subscribers
     */
    function delete_selected_subscribers($ids){
        if (current_user_can( 'list_users' )) {
            if(is_array($ids) && !empty($ids)){
                global $wpdb;
                $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
                $sql = "DELETE FROM " . $tablename . " WHERE id IN ( ".implode(",", $ids)." )";
                $result = $wpdb->query($sql);
                if($result){
                    return true;
                }
            }
        }
    } 

	/*
	 * Display Subscribers
	 */
	function display_subscribers($args){
        //Display if we are on the subscribers tab
		if($args['tab'] == 'seed_csp3_tab_3'){
    
            if(!empty($_POST['action'])){
                //$nonce = $_POST['_wpnonce'];
                //var_dump(wp_verify_nonce($nonce, 'buljk-toplevel_page_seed_csp3'));
                if($_POST['action'] == 'delete'){
                    if($this->delete_all_subscribers()){
                        echo '
                        <div id="setting-error-seedprod_error" class="error settings-error below-h2"> 
                        <p><strong>'.__('All subscribers deleted.','seedprod').'</strong></p></div>';
                    }
                }
                if($_POST['action'] == 'delete_selected'){
                    if($this->delete_selected_subscribers($_POST['subscriber'])){
                        echo '
                        <div id="setting-error-seedprod_error" class="error settings-error below-h2"> 
                        <p><strong>'.__('Selected subscribers deleted.','seedprod').'</strong></p></div>';
                    }
                }
            }


            
            // Render Subscriber
            $seed_csp3_subscribers = new SEED_CSP3_SUBSCRIBERS();
            $seed_csp3_subscribers->prepare_items();
            echo '<form id="seed_csp3_search"" method="post">';
            $seed_csp3_subscribers->search_box('Search Emails', 'email'); 
            echo '</form>';
            echo '<form id="seed_csp3_bulk_actions" method="post">';
            $seed_csp3_subscribers->display();
            wp_nonce_field('seed_csp3_subscribers');
            echo '</form>';

        ?>
        <script>
        jQuery(document).ready(function($){
            $(".bottom > .actions").hide();
            $("#doaction").click(function(event) {
                event.preventDefault();
                var action = $('select[name="action"]').val();
                if(action != '-1'){
                    if(action == 'delete'){
                        if(confirm(seed_csp3_msgs.delete_confirm)){
                            $("#seed_csp3_bulk_actions").submit();
                        }
                    }else{
                        $("#seed_csp3_bulk_actions").submit();
                    }
                }
            });
        });
        </script>

        <?php

		}
	}

    function get_ip(){
        $ip = '';
        if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) AND strlen($_SERVER['HTTP_X_FORWARDED_FOR'])>6 ){
            $ip = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
        }elseif( !empty($_SERVER['HTTP_CLIENT_IP']) AND strlen($_SERVER['HTTP_CLIENT_IP'])>6 ){
             $ip = strip_tags($_SERVER['HTTP_CLIENT_IP']);
        }elseif(!empty($_SERVER['REMOTE_ADDR']) AND strlen($_SERVER['REMOTE_ADDR'])>6){
             $ip = strip_tags($_SERVER['REMOTE_ADDR']);
        }//endif
        if(!$ip) $ip="127.0.0.1";
        return strip_tags($ip);
    }

}

$seed_csp3 = new SEED_CSP3_PLUGIN();

// Display Subscribers Class
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class SEED_CSP3_SUBSCRIBERS extends WP_List_Table {
    function get_data($current_page,$per_page){
        // Get records
        global $wpdb;
        $l1 = ($current_page-1)* $per_page;
        $l2 = $per_page;
        $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
        $email = '%'.$_POST['s'].'%';
        $q = "WHERE email LIKE %s ";
        $sql = "SELECT * FROM $tablename $q LIMIT $l1,$l2";
        $safe_sql = $wpdb->prepare($sql,$email);
        $results = $wpdb->get_results($safe_sql);
        $data = array();
        foreach($results as $v){
            // Sep
            $sep = '';
            if($v->fname != '' || $v->lname != ''){
                $sep = '<br>';
            }
            // Format Date
            $date = date(get_option('date_format').' '.get_option('time_format'), strtotime($v->created));

            // Get Gravatar
            $gravatar = '<img src="http://www.gravatar.com/avatar/'.md5($v->email) .'?s=36" alt="Gravatar" style="float:left;padding:2px;backgroun-color:#fff;border:1px solid #ccc;margin-right:8px">';

            // Format email
            $email = "<a href='mailto:{$v->email}'>{$v->email}</a>";

            $ref = $v->id+1000;
            $referrer_url = home_url() . '?ref='.base_convert($ref, 10, 36);

            // Subscriber
            $subscriber = $gravatar.$v->fname.' '.$v->lname.$sep.$email.' <br clear="both"><strong>Referrer URL</strong><br><a href="'.$referrer_url.'" traget="_blank">'.$referrer_url.'</a>';

            // Influence
            $influence = $v->conversions. ' of '. $v->clicks. ' referrals have subscribed to your list';

            $conversions = $v->conversions;
            if($v->conversions != 0){
                $conversion_rate = round(($v->conversions/$v->clicks) * 100).'%';
            }else{
                $conversion_rate = '0%';
            }
            $clicks = $v->clicks;




            
            // Insights
            $insights = '';
            // $insights .= $v->insights;
            // var_export(json_decode($v->insights,true));
            if(!empty($v->location)){
                $location = json_decode($v->location,true);
                $insights .= 'Subscribed from: '.$location['city'].', '.$location['country_name'].'<br>';
            }

            $insights .= __('Subscribed on: ','seedprod').$date;
            $created  = $date;

            // Load Data
            $data[] = array(
                'ID' => $v->id,
                'subscriber' => $subscriber,
                'clicks' => $clicks,
                'conversions' => $conversions,
                'conversion_rate' => $conversion_rate,
                'created' => $created,
                );
        }
        return $data;
    }

    function get_data_total(){
        global $wpdb;
        if(empty($_POST['s']))
            $_POST['s'] = '';

        $tablename = $wpdb->prefix . SEED_CSP3_TABLENAME;
        $email = '%'.$_POST['s'].'%';
        $q = "WHERE email LIKE %s ";
        $sql = "SELECT count(id) FROM $tablename $q";
        $safe_sql = $wpdb->prepare($sql,$email);
        $results = $wpdb->get_var($safe_sql);
        return $results;
    }

    function get_sortable_columns() {
      $sortable_columns = array(
        'clicks'  => array('clicks',false),
        'conversions' => array('conversions',false),
        'conversion_rate'   => array('conversion_rate',false),
        'created'   => array('created',false)
      );
      return $sortable_columns;
    }

    function usort_reorder( $a, $b ) {
      // If no sort, default to created
      $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'created';
      // If no order, default to asc
      $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
      // Determine sort order
      $result = strcmp( $a[$orderby], $b[$orderby] );
      // Send final sort direction to usort
      return ( $order === 'asc' ) ? $result : -$result;
    }

    function get_columns(){
      $columns = array(
        'cb'        => '<input type="checkbox" />',
        'subscriber' => __('Subscribers','seedprod'),
        'clicks'    => __('Clicks','seedprod'),
        'conversions'    => __('# People Signed Up','seedprod'),
        'conversion_rate'    => __('Conversion Rate','seedprod'),
        'created'      => __('Created','seedprod'),
      );
      return $columns;
    }
    function prepare_items() {
      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();
      $this->_column_headers = array($columns, $hidden, $sortable);
      $per_page = 10;
      $current_page = $this->get_pagenum();
      $total_items = $this->get_data_total();
      $this->set_pagination_args( array(
        'total_items' => $total_items,
        'per_page'    => $per_page
      ) );
      $data = $this->get_data($current_page,$per_page);
      usort( $data, array( &$this, 'usort_reorder' ) );
      $this->items = $data;
    }

    function column_default( $item, $column_name ) {
      switch( $column_name ) {
        case 'subscriber':
        case 'clicks':
        case 'conversions':
        case 'conversion_rate':
        case 'created':
          return $item[ $column_name ];
        default:
          return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function get_bulk_actions() {
      $actions = array(
        'export'    => __('Export All','seedprod'),
        'delete'    => __('Delete All','seedprod'),
        'delete_selected'    => __('Delete Selected','seedprod'),
      );
      return $actions;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="subscriber[]" value="%s" />', $item['ID']
        );
    }

    function column_subscriber($item) {
      $actions = array(
                //'profile'      => sprintf('<a href="?page=%s&action=%s&book=%s">Profile</a>',$_REQUEST['page'],'profile',$item['ID']),
            );
      return sprintf('%1$s %2$s', $item['subscriber'], $this->row_actions($actions) );
    }
}


function seed_csp3_get_settings(){
    $s1 = get_option('seed_csp3_settings_1');
    $s2 = get_option('seed_csp3_settings_2');
    $s3 = get_option('seed_csp3_settings_4');
    if(empty($s1))
        $s1 = array();

    if(empty($s2))
        $s2 = array();

    if(empty($s3))
        $s3 = array();

    $options = $s1 + $s2 + $s3;
    return $options;
}


