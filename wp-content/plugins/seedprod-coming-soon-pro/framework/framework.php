<?php
/**
 * seed_csp3 Framework
 *
 * @package WordPress
 * @subpackage seed_csp3
 * @since 0.1.0
 */

class SEED_CSP3
{
    public $plugin_version = SEED_CSP3_VERSION;
    public $plugin_name = SEED_CSP3_PLUGIN_NAME;
    
    /**
     * Holds defined menus
     */
    public $pages = array( );
    
    /**
     *  Holds defined tabs, sections and fields
     */
    public $options = array( );
    
    /**
     * Load Hooks
     */
    function __construct( )
    {
        
        if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ){
            add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts'  ) );
            add_action( 'admin_menu', array( &$this, 'create_menus'  ) );
            add_action( 'admin_init', array( &$this, 'reset_defaults' ) );
            add_action( 'admin_init', array( &$this, 'create_settings' ) );
            add_filter( 'plugin_action_links', array( &$this, 'plugin_action_links' ), 10, 2 );
        }
        
    }
    
    
    /**
     * Reset the settings page. Reset works per settings id.
     *
     */
    function reset_defaults( )
    {
        if ( isset( $_POST[ 'reset' ] ) ) {
            $option_page = $_POST[ 'option_page' ];
            check_admin_referer( $option_page . '-options' );
            //$defaults = array( );
            // foreach ( $this->options as $k ) {
            //     switch ( $k[ 'type' ] ) {
            //         case 'menu':
            //         case 'setting':
            //         case 'section':
            //         case 'tab':
            //             break;
            //         default:
            //             if ( $k[ 'setting_id' ] === $_POST[ 'option_page' ] ) {
            //                 if ( isset( $k[ 'default_value' ] ) ) {
            //                     $defaults[ $k[ 'id' ] ] = $k[ 'default_value' ];
            //                 }
            //             }
            //     }
            // }
            
            //$_POST[ $_POST[ 'option_page' ] ] = $defaults;
            require_once(SEED_CSP3_PLUGIN_PATH.'inc/defaults.php');
            //var_dump($seed_csp3_settings_deafults[$_POST[ 'option_page' ]]);
            $_POST[ $_POST[ 'option_page' ] ] = $seed_csp3_settings_deafults[$_POST[ 'option_page' ]];
            add_settings_error( 'general', 'seed_csp3-settings-reset', __( "Settings reset." ), 'updated' );
        }
        
    }
    
    /**
     * Properly enqueue styles and scripts for our theme options page.
     *
     * This function is attached to the admin_enqueue_scripts action hook.
     *
     * @since  0.1.0
     * @param string $hook_suffix The name of the current page we are on.
     */
    function admin_enqueue_scripts( $hook_suffix )
    {
        if ( !in_array( $hook_suffix, $this->pages ) )
            return;
        
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'wp-lists' );
        wp_enqueue_script( 'seed_csp3-framework-js', SEED_CSP3_PLUGIN_URL . 'framework/settings-scripts.js', array( 'jquery' ), $this->plugin_version );
        wp_enqueue_script( 'theme-preview' );
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'media-upload' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'seed_csp3-framework-css', SEED_CSP3_PLUGIN_URL . 'framework/settings-style.css', false, $this->plugin_version );
    }

    /**
     * Get all option settings
     *
     * @since 0.1.0
     */
    // function get_options( )
    // {
    //     $settings = array( );
    //     foreach ( $this->options as $k ) {
    //         switch ( $k[ 'type' ] ) {
    //             case 'setting':
    //                 $s = get_option( $k[ 'id' ]);
    //                 if(is_array($s)){
    //                     $settings = $settings + $s; 
    //                 }
    //                 break;
    //         }
    //     }
    //     return $settings;
    // }

    
    /**
     * Creates WordPress Menu pages from an array in the config file.
     *
     * This function is attached to the admin_menu action hook.
     *
     * @since 0.1.0
     */
    function create_menus( )
    {
        foreach ( $this->options as $k => $v ) {
            if ( $v[ 'type' ] == 'menu' ) {
                if ( empty( $v[ 'menu_name' ] ) ) {
                    $v[ 'menu_name' ] = $v[ 'page_name' ];
                }
                if ( empty( $v[ 'capability' ] ) ) {
                    $v[ 'capability' ] = 'manage_options';
                }
                if ( empty( $v[ 'callback' ] ) ) {
                    $v[ 'callback' ] = array(
                        &$this,
                        'option_page' 
                    );
                }
                if ( empty( $v[ 'icon_url' ] ) ) {
                    $v[ 'icon_url' ] = SEED_CSP3_PLUGIN_URL . 'framework/settings-menu-icon-16x16.png';
                }
                if ( empty( $v[ 'menu_slug' ] ) ) {
                    $v[ 'menu_slug' ]                 = sanitize_title( $v[ 'page_name' ] );
                    $this->options[ $k ][ 'menu_slug' ] = $v[ 'menu_slug' ];
                }
                if ( $v[ 'menu_type' ] == 'add_submenu_page' ) {
                    $this->pages[ ] = call_user_func_array( $v[ 'menu_type' ], array(
                        $v[ 'parent_slug' ],
                        $v[ 'page_name' ],
                        $v[ 'menu_name' ],
                        $v[ 'capability' ],
                        $v[ 'menu_slug' ],
                        $v[ 'callback' ] 
                    ) );
                } else {
                    $this->pages[ ] = call_user_func_array( $v[ 'menu_type' ], array(
                        $v[ 'page_name' ],
                        $v[ 'menu_name' ],
                        $v[ 'capability' ],
                        $v[ 'menu_slug' ],
                        $v[ 'callback' ],
                        $v[ 'icon_url' ] 
                    ) );
                }
            }
        }
    }
    
    /**
     * Display settings link on plugin page
     */
    function plugin_action_links( $links, $file )
    {
        $plugin_file = SEED_CSP3_FILE;
        
        if ( $file == $plugin_file ) {
            $settings_link = '<a href="options-general.php?page=' . $this->options[ 0 ][ 'menu_slug' ] . '">Settings</a>';
            array_unshift( $links, $settings_link );
        }
        return $links;
    }
    
    
    /**
     * Allow Tabs on the Settings Page
     *
     */
    function plugin_options_tabs( )
    {
        $page        = $_REQUEST[ 'page' ];
        $uses_tabs   = false;
        $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : false;
        
        //Check if this config uses tabs
        foreach ( $this->options as $v ) {
            if ( $v[ 'type' ] == 'tab' ) {
                $uses_tabs = true;
            }
        }
        
        // If uses tabs then generate the tabs
        if ( $uses_tabs ) {
            echo '<h2 class="nav-tab-wrapper" style="padding-left:20px">';
            $c = 1;
            foreach ( $this->options as $v ) {
                if ( isset( $v[ 'menu_slug' ] ) ) {
                    if ( $v[ 'menu_slug' ] == $page && $v[ 'type' ] == 'tab' ) {
                        $active = '';
                        if ( $current_tab ) {
                            $active = $current_tab == $v[ 'id' ] ? 'nav-tab-active' : '';
                        } elseif ( $c == 1 ) {
                            $active = 'nav-tab-active';
                        }
                        echo '<a class="nav-tab ' . $active . '" href="?page=' . $v[ 'menu_slug' ] . '&tab=' . $v[ 'id' ] . '">' . $v[ 'label' ] . '</a>';
                        $c++;
                    }
                }
            }
            echo '<a class="nav-tab seed_csp3-preview thickbox-preview" href="'.home_url().'?cs_preview=true&TB_iframe=true&width=640&height=632" title="'.__('&larr; Close Window','seedprod').'">'.__('Live Preview','seedprod').'</a>';
            if(defined('SEED_CSP_API_KEY') === false){
                echo '<a class="nav-tab seed_csp3-support" href="http://www.seedprod.com/support/" target="_blank" style="float:right;">'.__('Support','seedprod').'</a>';
            }
            echo '</h2>';

        }
    }
    
    /**
     * Get the layout for the page. classic|2-col
     *
     */
    function get_page_layout( )
    {
        $layout = 'classic';
        foreach ( $this->options as $v ) {
            switch ( $v[ 'type' ] ) {
                case 'menu';
                    $page = $_REQUEST[ 'page' ];
                    if ( $page == $v[ 'menu_slug' ] ) {
                        if ( isset( $v[ 'layout' ] ) ) {
                            $layout = $v[ 'layout' ];
                        }
                    }
                    break;
            }
        }
        return $layout;
    }
    
    /**
     * Render the option pages.
     *
     * @since 0.1.0
     */
    function option_page( )
    {
        $page   = $_REQUEST[ 'page' ];
        $layout = $this->get_page_layout();
        ?>
        <div class="wrap columns-2 seed_csp3">
        <?php screen_icon(); ?>
            <h2><?php echo $this->plugin_name; ?> <span class="seed_csp3-version"> <?php echo SEED_CSP3_VERSION; ?></span></h2>
            <?php //settings_errors() ?>
            <?php $this->plugin_options_tabs(); ?>
            <?php if ( $layout == '2-col' ): ?>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content" >
            <?php endif; ?>
                    <?php if(!empty($_GET['tab']))
                            do_action( 'seed_csp3_render_page', array('tab'=>$_GET['tab'])); 
                    ?>
                    <form action="options.php" method="post">
                    <p>
                    <!-- <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'seedprod' ); ?>" class="button-primary"/> -->
                    <?php if(!empty($_GET['tab']) && $_GET['tab'] != 'seed_csp3_tab_3') { ?>
                    <input id="reset" name="reset" type="submit" value="<?php _e( 'Reset Settings', 'seedprod' ); ?>" class="button-secondary"/>    
                    <?php } ?>
                    </p>
                            <?php
                            $show_submit = false;
                            foreach ( $this->options as $v ) {
                                if ( isset( $v[ 'menu_slug' ] ) ) {
                                    if ( $v[ 'menu_slug' ] == $page ) {
                                        switch ( $v[ 'type' ] ) {
                                            case 'menu';
                                                break;
                                            case 'tab';
                                                $tab = $v;
                                                if ( empty( $default_tab ) )
                                                    $default_tab = $v[ 'id' ];
                                                break;
                                            case 'setting':
                                                $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
                                                if ( $current_tab == $tab[ 'id' ] ) {
                                                    settings_fields( $v[ 'id' ] );
                                                    $show_submit = true;
                                                }
        
                                                break;
                                            case 'section':
                                                $current_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $default_tab;
                                                if ( $current_tab == $tab[ 'id' ] or $current_tab === false ) {
                                                    if ( $layout == '2-col' ) {
                                                        echo '<div id="'.$v[ 'id' ].'" class="postbox seedprod-postbox">';
                                                        $this->do_settings_sections( $v[ 'id' ],$show_submit );
                                                        echo '</div>';
                                                    } else {
                                                        do_settings_sections( $v[ 'id' ] );
                                                    }
                                                                      
                                                }
                                                break;
                                                
                                        }
                                    }
                                }
                            }
                        ?>
                    <?php if($show_submit): ?>
                    <p>
                    <!-- <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'seedprod' ); ?>" class="button-primary"/> -->
                    <input id="reset" name="reset" type="submit" value="<?php _e( 'Reset Settings', 'seedprod' ); ?>" class="button-secondary"/>    
                    </p>
                    <?php endif; ?>
                    </form> 

                    <?php if ( $layout == '2-col' ): ?> 
                    </div> <!-- #post-body-content -->

                
                </div> <!-- #post-body --> 
            </div> <!-- #poststuff --> 
            <?php endif; ?>
        </div> <!-- .wrap -->

        <!-- JS login to confirm setting resets. -->    
        <script>
            jQuery(document).ready(function($) {
                $('#reset').click(function(e){
                    if(!confirm('<?php _e( 'This tabs settings be deleted and reset to the defaults. Are you sure you want to reset?', 'seedprod' ); ?>')){
                        e.preventDefault();
                    }
                });
            });
        </script>
        <?php
    }
    
    /**
     * Create the settings options, sections and fields via the WordPress Settings API
     *
     * This function is attached to the admin_init action hook.
     *
     * @since 0.1.0
     */
    function create_settings( )
    {
        foreach ( $this->options as $k => $v ) {
            switch ( $v[ 'type' ] ) {
                case 'menu':
                    $menu_slug = $v[ 'menu_slug' ];
                    break;
                case 'setting':
                    if ( empty( $v[ 'validate_function' ] ) ) {
                        $v[ 'validate_function' ] = array(
                             &$this,
                            'validate_machine' 
                        );
                    }
                    register_setting( $v[ 'id' ], $v[ 'id' ], $v[ 'validate_function' ] );
                    $setting_id                         = $v[ 'id' ];
                    $this->options[ $k ][ 'menu_slug' ] = $menu_slug;
                    break;
                case 'section':
                    if ( empty( $v[ 'desc_callback' ] ) ) {
                        $v[ 'desc_callback' ] = array(
                             &$this,
                            '__return_empty_string' 
                        );
                    } else {
                        $v[ 'desc_callback' ] = $v[ 'desc_callback' ];
                    }
                    add_settings_section( $v[ 'id' ], $v[ 'label' ], $v[ 'desc_callback' ], $v[ 'id' ] );
                    $section_id                         = $v[ 'id' ];
                    $this->options[ $k ][ 'menu_slug' ] = $menu_slug;
                    break;
                case 'tab':
                    $this->options[ $k ][ 'menu_slug' ] = $menu_slug;
                    break;
                default:
                    if ( empty( $v[ 'callback' ] ) ) {
                        $v[ 'callback' ] = array(
                             &$this,
                            'field_machine' 
                        );
                    }
                    
                    add_settings_field( $v[ 'id' ], $v[ 'label' ], $v[ 'callback' ], $section_id, $section_id, array(
                         'id' => $v[ 'id' ],
                        'desc' => ( isset( $v[ 'desc' ] ) ? $v[ 'desc' ] : '' ),
                        'setting_id' => $setting_id,
                        'class' => ( isset( $v[ 'class' ] ) ? $v[ 'class' ] : '' ),
                        'type' => $v[ 'type' ],
                        'default_value' => ( isset( $v[ 'default_value' ] ) ? $v[ 'default_value' ] : '' ),
                        'option_values' => ( isset( $v[ 'option_values' ] ) ? $v[ 'option_values' ] : '' ) 
                    ) );
                    
            }
        }
    }
    
    /**
     * Create a field based on the field type passed in.
     *
     * @since 0.1.0
     */
    function field_machine( $args )
    {
        extract( $args ); //$id, $desc, $setting_id, $class, $type, $default_value, $option_values
        

        // Load defaults
        $defaults = array( );
        foreach ( $this->options as $k ) {
            switch ( $k[ 'type' ] ) {
                case 'setting':
                case 'section':
                case 'tab':
                    break;
                default:
                    if ( isset( $k[ 'default_value' ] ) ) {
                        $defaults[ $k[ 'id' ] ] = $k[ 'default_value' ];
                    }
            }
        }
        $options = get_option( $setting_id );
        
        $options = wp_parse_args( $options, $defaults );
        
        $path = SEED_CSP3_PLUGIN_PATH . 'framework/field-types/' . $type . '.php';
        if ( file_exists( $path ) ) {
            // Show Field
            include( $path );
            // Show description
            if ( !empty( $desc ) ) {
                echo "<small class='description'>{$desc}</small>";
            }
        }
        
    }
    
    /**
     * Validates user input before we save it via the Options API. If error add_setting_error
     *
     * @since 0.1.0
     * @param array $input Contains all the values submitted to the POST.
     * @return array $input Contains sanitized values.
     * @todo Figure out best way to validate values.
     */
    function validate_machine( $input )
    {
        $option_page = $_POST['option_page'];
        foreach ( $this->options as $k ) {
            switch ( $k[ 'type' ] ) {
                case 'menu':
                case 'setting':
                    if(isset($k['id']))
                        $setting_id = $k['id'];
                case 'section':
                case 'tab';
                    break;
                default:
                    if ( !empty( $k[ 'validate' ] ) && $setting_id == $option_page ) {
                        $validation_rules = explode( ',', $k[ 'validate' ] );

                        foreach ( $validation_rules as $v ) {
                            $path = SEED_CSP3_PLUGIN_PATH . 'framework/validations/' . $v . '.php';
                            if ( file_exists( $path ) ) {
                                // Defaults Values
                                $is_valid  = true;
                                $error_msg = '';

                                // Test Validation
                                include( $path );
                                
                                // Is it valid?
                                if ( $is_valid === false ) {
                                    add_settings_error( $k[ 'id' ], 'seedprod_error', $error_msg, 'error' );
                                    // Unset invalids
                                    unset( $input[ $k[ 'id' ] ] );
                                }
                                
                            }
                        } //end foreach
                        
                    }
            }
        }
        
        return $input;
    }
    
    /**
     * Dummy function to be called by all sections from the Settings API. Define a custom function in the config.
     *
     * @since 0.1.0
     * @return string Empty
     */
    function __return_empty_string( )
    {
        echo '';
    }
    
    
    /**
     * SeedProd version of WP's do_settings_sections
     *
     * @since 0.1.0
     */
    function do_settings_sections( $page, $show_submit )
    {
        global $wp_settings_sections, $wp_settings_fields;
        
        if ( !isset( $wp_settings_sections ) || !isset( $wp_settings_sections[ $page ] ) )
            return;
        
        foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
            echo "<h3><span></span>{$section['title']}</h3>\n";
            echo '<div class="inside">';
            call_user_func( $section[ 'callback' ], $section );
            if ( !isset( $wp_settings_fields ) || !isset( $wp_settings_fields[ $page ] ) || !isset( $wp_settings_fields[ $page ][ $section[ 'id' ] ] ) )
                continue;
            echo '<table class="form-table">';
            $this->do_settings_fields( $page, $section[ 'id' ] );
            echo '</table>';
            if($show_submit): ?>
                <p>
                <input name="submit" type="submit" value="<?php _e( 'Save All Changes', 'seedprod' ); ?>" class="button-primary"/> 
                </p>
            <?php endif;
            echo '</div>';
        }
    }

    function do_settings_fields($page, $section) {
          global $wp_settings_fields;
      
          if ( !isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section]) )
              return;
      
          foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
              echo '<tr valign="top">';
              if ( !empty($field['args']['label_for']) )
                  echo '<th scope="row"><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></th>';
              else
                  echo '<th scope="row"><strong>' . $field['title'] . '</strong><!--<br>'.$field['args']['desc'].'--></th>';
              echo '<td>';
              call_user_func($field['callback'], $field['args']);
              echo '</td>';
              echo '</tr>';
          }
      }
    
}

