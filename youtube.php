<?php
class EVL_YouTube_Plugin extends EVL_Plugin_Base {
    private $content = '';
    private $key = '';
    private $vid = '';

    public function __construct ($vid, $content) {
        $this->key = get_option( "evl_youtube_api_key", "" );
        $this->vid = $vid;
        $this->content = $content;
    }

    // function to parse the goolge api duration format
    public static function parseYouTubeDuration($duration) {
        $match = array();
        preg_match("/PT(\d+H)?(\d+M)?(\d+S)?/i", $duration, $match);

        $hours = (int)($match[1]);
        $minutes = (int)($match[2]);
        $seconds = (int)($match[3]);

        return sprintf('%02d:%02d:%02d', $hours,$minutes,$seconds);//($hours!=0?$hours.':':'').($minutes!=0?$minutes.':':'').($seconds!=0?$seconds.'':'');
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
        $queryParams = array(
            'id' => $this->vid,
            'part' => 'contentDetails,statistics,snippet',
            'key'=> $this->key
        );

        $query = http_build_query($queryParams);

        $video_url = 'https://www.googleapis.com/youtube/v3/videos?'.$query;

        $vid_info = EVL_Utilities::getURLData($video_url);
        ob_start();
            include ('render_youtube.php');
        return ob_get_clean();
    }
}