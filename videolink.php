<?php
class VideoLink {
    public function __construct () {
        add_shortcode( 'evl', array( $this, 'evl_process_shortcode' ) ); 
        add_action( 'admin_menu', array( $this, 'evl_create_menu_item' ) );
        add_action( 'admin_init', array( $this, 'evl_init_config' ) );

        $plugin = plugin_basename( EVL_PLUGIN_FILE );
        add_filter( "plugin_action_links_$plugin", array($this, 'plugin_add_settings_link' ) );

        $this->evl_register_styles();
    }

    public function plugin_add_settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=evl-config">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    public function evl_init_config() {
        register_setting('evl_config', 'evl_config_api_key');
    }

    public function evl_create_menu_item() {
        add_menu_page( 'Embed Video Link Config', 'Video Link', 'administrator', 'evl-config', array ($this, 'options_page' ), 'dashicons-video-alt3', 20 );
    }

    public function options_page() {?>
        <div class="evl">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-xs-offset-2 col-xs-10">
                            <h2>Embed Video Link Configurations</h2>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizontal" action="options.php" method="post">
                            <?php settings_fields( 'evl_config' )?>
                            <?php do_settings_sections( 'evl_config' )?>
                            <div class="form-group">
                                <label for="evl_config_api_key" class="control-label col-xs-2">Google API Key</label>
                                <div class="col-xs-10">
                                    <input type="text" class="form-control" id="evl_config_api_key" name="evl_config_api_key" value="<?php echo get_option( "evl_config_api_key", "" ) ?>" />
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

    public function evl_register_styles() {
        wp_register_style( 'evl', plugins_url( '/assets/css/main.min.css', __FILE__ ) );
        wp_enqueue_style( 'evl' );
    }

    public function evl_process_shortcode( $attributes, $content = null ) {
        extract( shortcode_atts( array(
            'vid' => '',
            'promote' => 'true'
        ), $attributes ) );
        $key = get_option( "evl_config_api_key", "" );

        if(empty($key)) {
            return "";
        }

        $video_url = 'https://www.googleapis.com/youtube/v3/videos?id='.$vid.'&part=id,contentDetails,statistics,topicDetails,snippet&key='.$key;

        $vid_info = $this->evl_getVideoInfo($video_url);    

        return 
            '<blockquote class="evl">
                <div class="row">
                    <div class="col-md-12 vid_message">'.$content.'</div>
                    <div class="col-md-5 col-sm-5">
                        <a target="_blank" href="https://www.youtube.com/watch?v='.$vid.'">
                            <img src="https://i.ytimg.com/vi/'.$vid.'/maxresdefault.jpg"/>
                        </a>
                    </div>
                    <div class="col-md-7 col-sm-7">
                        <div class="row">
                            <div class="col-md-12 vid_title">
                                <a target="_blank" href="https://www.youtube.com/watch?v='.$vid.'">'.$vid_info->items[0]->snippet->title.'</a>
                            </div>
                            <div class="col-md-12 vid_description">
                                description: <strong>'.$vid_info->items[0]->snippet->description.'</strong>
                            </div>
                            <div class="col-md-12 vid_info">
                                duration: <strong>'.$this->evl_parseDuration($vid_info->items[0]->contentDetails->duration).'</strong>
                            </div>
                            <div class="col-md-12 vid_statistics">
                                views: <strong>'.$vid_info->items[0]->statistics->viewCount.'</strong>
                            </div>
                            <div class="col-md-12 vid_channel">
                                by: <strong>'.$vid_info->items[0]->snippet->channelTitle.'</strong>
                            </div>
                            <div class="col-md-12 vid_source">
                                source: <strong>YouTube</strong>
                            </div>
                        </div>
                    </div>
                </div>'
                .($promote=='true'?
                '<span class="promote">
                    Get this plugin <a href="https://www.mindzgrouptech.net/wordpress-plugin-embed-video-link">here</a>.
                </span>':'').
            '</blockquote>';
    }

    public function evl_getVideoInfo($evl_url) {
        // the video info
        $videos_result = wp_remote_get( $evl_url );
        
        // 
        $json = json_decode($videos_result['body']);
        
        $response_code = wp_remote_retrieve_response_code( $videos_result );
        $response_message = wp_remote_retrieve_response_message( $videos_result );

        if ( $response_code != 200 ) {
            return null;
        }

        return $json;
    }

    public function evl_parseDuration($duration) {
        $match = array();
        preg_match("/PT(\d+H)?(\d+M)?(\d+S)?/i", $duration, $match);

        $hours = (int)($match[1]);
        $minutes = (int)($match[2]);
        $seconds = (int)($match[3]);

        return ($hours!=0?$hours.' hours ':'').($minutes!=0?$minutes.' minutes ':'').($seconds!=0?$seconds.' seconds':'');
    }
}