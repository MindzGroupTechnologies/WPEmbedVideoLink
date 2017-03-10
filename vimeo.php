<?php
class EVL_Vimeo_Plugin extends EVL_Plugin_Base {
    private $content = '';
    private $key = '';
    private $vid = '';

    public function __construct ($vid, $content) {
        $this->key = get_option( "evl_vimeo_access_token", "" );
        $this->vid = $vid;
        $this->content = $content;
    }

    // function to parse the goolge api duration format
    public static function parseVimeoDuration($duration) {
        $t = round($duration);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }

    public function render() {
        // check if the api key is configured
        if(empty($this->key)) {
            return EVL_Utilities::getError('IVL_CFG');
        }

        // check if vid is provided
        if(empty($this->vid)) {
            return EVL_Utilities::getError('IVL_VID');
        }
        // $queryParams = array(
        //     'id' => $this->vid,
        //     'part' => 'contentDetails,statistics,snippet',
        //     'key'=> $this->key
        // );

        //$query = http_build_query($queryParams);
        $args = array (
            'headers' => array (
                'Authorization' => 'Bearer ' . $this->key
            )
        );

        $video_url = 'https://api.vimeo.com/videos/'.$this->vid;

        $vid_info = EVL_Utilities::getURLData($video_url, $args);
        ob_start();
            include ('render_vimeo.php');
        return ob_get_clean();
    }
}