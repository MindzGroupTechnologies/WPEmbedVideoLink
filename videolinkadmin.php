<?php
class EVL_Admin {
    public function __construct () {
        // add admin styles
        add_action( 'admin_enqueue_scripts', array($this, 'registerStyles') );
        
        // create admin menu item
        add_action( 'admin_menu', array( $this, 'createAdminMenu' ) );
        
        // initialize configurations
        add_action( 'admin_init', array( $this, 'initConfig' ) );
        
        // hook the update option event
        add_action( 'updated_option', array ( $this, 'checkSaved' ), 10, 3 );
        
        // hook the update option event
        add_action( 'added_option', array ( $this, 'checkAdded' ), 10, 2 );

        // add plugin settings link in the insttalled plugin list
        $plugin = plugin_basename( EVL_PLUGIN_FILE );
        add_filter( "plugin_action_links_$plugin", array( $this, 'addSettingsLink' ) );
    }
    
    public function checkSaved($option, $old_value, $value) {
        //echo "hello updated";
        if($option =='evl_vimeo_client_id' || $option =='evl_vimeo_client_secret') {
            self::getVimeoAccessToken();
        }
    }
    
    public function checkAdded($option, $value) {
        if($option =='evl_vimeo_client_id' || $option =='evl_vimeo_client_secret') {
            self::getVimeoAccessToken();
        }
    }
    
    // function to register styles
    public function registerStyles($hook) {
        // Load only on ?page=evl-youtube-config
        $option_pages = array(
        'video-link_page_evl-youtube-config',
        'toplevel_page_evl-plugin',
        'video-link_page_evl-vimeo-config');
        if( !in_array( $hook, $option_pages ) ) {
            return;
        } else {
            wp_register_style( 'evl', plugins_url( '/assets/css/main.min.css', __FILE__ ) );
            wp_enqueue_style( 'evl' );
        }
    }
    
    // function to create a menu item
    public function createAdminMenu() {
        add_menu_page( 'Embed Video Link Config', 'Video Link', 'manage_options', 'evl-plugin', array ($this, 'about_page' ), 'dashicons-video-alt3', 20 );
        add_submenu_page( 'evl-plugin', 'About', 'About', 'manage_options', 'evl-plugin', array($this, 'about_page') );
        add_submenu_page( 'evl-plugin', 'EVL YouTube Configuration', 'YouTube Videos', 'manage_options', 'evl-youtube-config', array($this, 'youtube_options_page') );
        add_submenu_page( 'evl-plugin', 'EVL Vimeo Configuration', 'Vimeo Videos', 'manage_options', 'evl-vimeo-config', array($this, 'vimeo_options_page') );
    }
    
    // function to set the initial configuration
    public function initConfig() {
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

    private function getVimeoAccessToken() {
        
        $client_id = get_option( 'evl_vimeo_client_id', null );
        $client_secret = get_option( 'evl_vimeo_client_secret', null );
        $args = array (
            'headers' => array (
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret )
            ),
            'body' => array( 'grant_type' => 'client_credentials')
        );
        $response = EVL_Utilities::postURLData('https://api.vimeo.com/oauth/authorize/client', $args);
        if(!empty($response)) {
            update_option( 'evl_vimeo_access_token', $response->access_token );
        }
        else{
            update_option( 'evl_vimeo_access_token', '' );
        }
    }
    
    // function to add the settings link in the installed plugin list for this plugin
    public function addSettingsLink( $links ) {
        // build the settings link
        $settings_link = '<a href="admin.php?page=evl-plugin">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link );
        
        // build update available link
        $pluginInfo = get_plugin_data( EVL_PLUGIN_FILE );
        
        if(EVL_Utilities::checkForUpdate($pluginInfo['Version'])) {
            $settings_link = '<a target="_blank" style="color: orange;" href="https://github.com/MindzGroupTechnologies/WPEmbedVideoLink/releases/latest">' . __( 'Updates' ) . ' ' . __( 'Available' ). '</a>';
            array_push( $links, $settings_link );
        }
        
        return $links;
    }
    
    // function to return the configuration page
    public function about_page() {
        include 'options_about.php';
    }
    
    // function to return the configuration page for youtube
    public function youtube_options_page() {
        include 'options_youtube.php';
    }
    
    // function to return the configuration page for vimeo
    public function vimeo_options_page() {
        include 'options_vimeo.php';
    }
    
    //function for get vimeo access token
    public function hasVimeoAccessToken()
    {
        // $client_id = get_option( 'evl_vimeo_client_id', null );
        // $client_secret = get_option( 'evl_vimeo_client_secret', null );
        if(!empty(get_option( 'evl_vimeo_access_token', null ))) {
            return true;
        }
        return false;
    }
}