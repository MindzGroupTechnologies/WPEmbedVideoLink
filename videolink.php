<?php
class VideoLink {
    public function __construct () {
        // register shortcode
        add_shortcode( 'evl', array( $this, 'evl_process_shortcode' ) ); 

        // add frontend styles
        add_action( 'wp_enqueue_scripts', array( $this, 'evl_register_styles' ) );
        
        // add admin styles
        add_action( 'admin_enqueue_scripts', array($this, 'evl_register_styles_admin') );

        // create admin menu item
        add_action( 'admin_menu', array( $this, 'evl_create_menu_item' ) );

        // initialize configurations
        add_action( 'admin_init', array( $this, 'evl_init_config' ) );

        // add plugin settings link in the insttalled plugin list 
        $plugin = plugin_basename( EVL_PLUGIN_FILE );
        add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_add_settings_link' ) );
    }

    // function to add the settings link in the installed plugin list for this plugin 
    public function plugin_add_settings_link( $links ) {
        // build the settings link
        $settings_link = '<a href="admin.php?page=evl-config">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link );

        // build update available link
        $pluginInfo = get_plugin_data( EVL_PLUGIN_FILE );
        
        if($this->checkForUpdate($pluginInfo['Version'])){
            $settings_link = '<a target="_blank" style="color: orange;" href="https://github.com/MindzGroupTechnologies/WPEmbedVideoLink/releases/latest">' . __( 'Updates' ) . ' ' . __( 'Available' ). '</a>';
            array_push( $links, $settings_link );
        }
        return $links;
    }

    // function to set the initial configuration
    public function evl_init_config() {
        // migrate v0.0.4 configs
        $old_config_0_0_4 = get_option('evl_config_api_key');
        if(!empty($old_config_0_0_4)){
            register_setting('evl_config_youtube', 'evl_youtube_api_key' );
            add_option( 'evl_youtube_api_key', $old_config_0_0_4 );
            delete_option( 'evl_config_api_key' );
        }
        else {
            register_setting('evl_config_youtube', 'evl_youtube_api_key' );
        }
        register_setting('evl_config_vimeo', 'evl_vimeo_client_id' );
        register_setting('evl_config_vimeo', 'evl_vimeo_client_secret' );
    }

    // function to create a menu item
    public function evl_create_menu_item() {
        add_menu_page( 'Embed Video Link Config', 'Video Link', 'manage_options', 'evl-config', array ($this, 'about_page' ), 'dashicons-video-alt3', 20 );
        add_submenu_page( 'evl-config', 'About', 'About', 'manage_options', 'evl-config', array($this, 'about_page') );
        add_submenu_page( 'evl-config', 'EVL YouTube Configuration', 'YouTube Videos', 'manage_options', 'evl-youtube-config', array($this, 'youtube_options_page') );
        add_submenu_page( 'evl-config', 'EVL Vimeo Configuration', 'Vimeo Videos', 'manage_options', 'evl-vimeo-config', array($this, 'vimeo_options_page') );
    }
    // function to return the configuration page
    public function about_page() {?>
        hello
    <?php
    }

    // function to return the configuration page for youtube
    public function youtube_options_page() {?>
        <div class="evl">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-xs-offset-2 col-xs-10">
                            <h2>EVL YouTube Configurations</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizontal" action="options.php" method="post">
                            <?php settings_fields( 'evl_config_youtube' )?>
                            <?php do_settings_sections( 'evl_config_youtube' )?>
                            <div class="form-group">
                                <label for="evl_youtube_api_key" class="control-label col-xs-2">Google API Key</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_youtube_api_key" name="evl_youtube_api_key" value="<?php echo get_option( "evl_youtube_api_key", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-offset-2 col-xs-10">
                                    <?php submit_button()?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    // function to return the configuration page for vimeo
    public function vimeo_options_page() {?>
        <div class="evl">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-xs-offset-2 col-xs-10">
                            <h2>EVL Vimeo Configurations</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizontal" action="options.php" method="post">
                            <?php settings_fields( 'evl_config_vimeo' )?>
                            <?php do_settings_sections( 'evl_config_vimeo' )?>
                            <div class="form-group">
                                <label for="evl_vimeo_client_id" class="control-label col-xs-2">Client ID</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_vimeo_client_id" name="evl_vimeo_client_id" value="<?php echo get_option( "evl_vimeo_client_id", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="evl_vimeo_client_secret" class="control-label col-xs-2">Cliend Secret</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_vimeo_client_secret" name="evl_vimeo_client_secret" value="<?php echo get_option( "evl_vimeo_client_secret", "" ) ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-2">Access Token</label>
                                <div class="col-xs-10">
                                    <input class="form-control" readonly value="<?php echo ($this->hasVimeoAccessToken()?'Please provide valid Vimeo App Credential':'You have a access token') ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-offset-2 col-xs-10">
                                    <?php submit_button()?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    //function for get vimeo access token
    public function hasVimeoAccessToken()
    {
        // $client_id = get_option( 'evl_vimeo_client_id', null );
        // $client_secret = get_option( 'evl_vimeo_client_secret', null );
        // if()
        return true;
    }

    // function to register styles
    public function evl_register_styles() {
        wp_register_style( 'evl', plugins_url( '/assets/css/main.min.css', __FILE__ ) );
        wp_enqueue_style( 'evl' );
    }

    // function to register admin styles
    public function evl_register_styles_admin($hook) {
        // Load only on ?page=evl-youtube-config
        $option_pages = array(
            'video-link_page_evl-youtube-config', 
            'toplevel_page_evl-config',
            'video-link_page_evl-vimeo-config');
        if( !in_array( $hook, $option_pages ) ) {
            return;
        }
        $this->evl_register_styles();
    }

    // function to render shortcode
    public function evl_process_shortcode( $attributes, $content = null ) {
        extract( shortcode_atts( array(
            'vid' => '',
            'plug' => 'YouTube',
            'promote' => 'true'
        ), $attributes ) );
        
        if(empty($plug)) {
            return EVL_Utilities::getError('IVL_PLG'); 
        }

        switch ($plug) {
            case 'YouTube':
                $youtube_plugin = new EVL_YouTube_Plugin($vid, $content);
                return $youtube_plugin->render();
                break;            
            default:
                echo "this is it";
                return EVL_Utilities::getError('IVL_PLG');
                break;
        }
    }

    // function to retrive the YouTube video information
    public function evl_getVideoInfo($evl_url) {
        // the video info
        $videos_result = wp_remote_get( $evl_url );
        
        $response_code = wp_remote_retrieve_response_code( $videos_result );
        $response_message = wp_remote_retrieve_response_message( $videos_result );

        if ( $response_code != 200 ) {
            return null;
        }

        if( is_array($videos_result) ) {
            $json = json_decode($videos_result['body']);
        }
        
        return $json;
    }

    public function checkForUpdate($current) {
        $response = wp_remote_get( esc_url_raw( 'https://api.github.com/repos/MindzGroupTechnologies/WPEmbedVideoLink/releases') );

        $response_code = wp_remote_retrieve_response_code( $response );
        $response_message = wp_remote_retrieve_response_message( $response );
        
        if ( $response_code != 200 ) {
            return false;
        }

        if( is_array($response) ) {
            $body = wp_remote_retrieve_body($response);
            $releases = json_decode($body);
            if(is_array($releases)) {
                $latest = $releases[0]->tag_name;
                return version_compare($current, $latest, '<');
            } else {
                return false;
            }
        }
    }
}