<?php
if ( !class_exists('seedprod_auto_update') ) {
    class seedprod_auto_update
    {
        /**
         * The plugin current version
         * @var string
         */
        public $current_version;

        /**
         * The plugin remote update path
         * @var string
         */
        public $update_path;

        /**
         * Plugin Slug (plugin_directory/plugin_file.php)
         * @var string
         */
        public $plugin_slug;

        /**
         * Plugin name (plugin_file)
         * @var string
         */
        public $slug;

        public $plugin_api_key;
        public $plugin_domain;


        /**
         * Initialize a new instance of the WordPress Auto-Update class
         * @param string $current_version
         * @param string $update_path
         * @param string $plugin_slug
         */
        function __construct($current_version, $update_path, $plugin_slug,$plugin_api_key,$plugin_domain)
        {
            // Set the class public variables
            $this->current_version = $current_version;
            $this->update_path = $update_path;
            $this->plugin_slug = $plugin_slug;
            $this->plugin_api_key = $plugin_api_key;
            $this->plugin_domain = $plugin_domain;
            list ($t1, $t2) = explode('/', $plugin_slug);
            $this->slug = str_replace('.php', '', $t2);

            // define the alternative API for updating checking
            add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));

            // Define the alternative response for information checking
            add_filter('plugins_api', array(&$this, 'check_info'), 10, 3);

            // Add any extra messages from the server
            add_action('after_plugin_row_seedprod-coming-soon-pro/seedprod-coming-soon-pro.php', array(&$this, 'csp3_plugin_row') ,9);
        }

        /**
         * Add our self-hosted autoupdate plugin to the filter transient
         *
         * @param $transient
         * @return object $ transient
         */
        public function check_update($transient)
        {

            if (empty($transient->checked)) {
                return $transient;
            }
            

            // Get the remote version
            $remote_version = $this->getRemote_version();

            // If a newer version is available, add the update
            if (version_compare($this->current_version, $remote_version->new_version, '<')) {
                $obj = new stdClass();
                $obj->slug = $this->slug;
                $obj->new_version = $remote_version->new_version;
                $obj->url = 'http://www.seedprod.com';
                $obj->package = $remote_version->download_link;
                $obj->upgrade_notice = $remote_version->upgrade_notice;
                $transient->response[$this->plugin_slug] = $obj;
            }
            return $transient;
        }

        /**
         * Add our self-hosted description to the filter
         *
         * @param boolean $false
         * @param array $action
         * @param object $arg
         * @return bool|object
         */
        public function check_info($false, $action, $arg)
        {

            if ($arg->slug === $this->slug) {
                $information = $this->getRemote_information();
                return $information;
            }
            return $false;
        }

        /**
         * Return the remote version
         * @return string $remote_version
         */
        public function getRemote_version()
        {
            $request = wp_remote_post($this->update_path, array('body' => array('action' => 'version','slug' => $this->plugin_slug,'api_key' => $this->plugin_api_key,'domain' => $this->plugin_domain,'installed_version' => $this->current_version)));
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                $response = unserialize($request['body']);
                update_option(basename($this->slug).'_update_msg',$response->message);
                return unserialize($request['body']);
            }
            return false;
        }

        /**
         * Get information about the remote version
         * @return bool|object
         */
        public function getRemote_information()
        {
            $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info','slug' => $this->plugin_slug,'api_key' => $this->plugin_api_key,'domain' => $this->plugin_domain,'installed_version' => $this->current_version)));
            
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                var_dump(unserialize($request['body']));
                return unserialize($request['body']);
            }
            return false;
        }


        public function csp3_plugin_row($plugin_name)
        {
            $msg = get_option(basename($this->slug).'_update_msg');
            if(!empty($msg)){
            echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">'.$msg.'</div></td>';
            }
        }

    }
}