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

        return ($hours!=0?$hours.':':'').($minutes!=0?$minutes.':':'').($seconds!=0?$seconds.'':'');
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

        $vid_info = $this->getURLData($video_url);

        return 
            '<blockquote class="evl">
                <div class="row">
                    <div class="col-md-12 vid_message">'.$this->content.'</div>
                    <div class="col-md-5 col-sm-5">
                        <a target="_blank" href="https://www.youtube.com/watch?v='.$this->vid.'">
                            <img src="https://i.ytimg.com/vi/'.$this->vid.'/maxresdefault.jpg"/>
                        </a>
                    </div>
                    <div class="col-md-7 col-sm-7">
                        <div class="row">
                            <div class="col-md-12 vid_title">
                                <a target="_blank" href="https://www.youtube.com/watch?v='.$this->vid.'">'.$vid_info->items[0]->snippet->title.'</a>
                            </div>
                            <div class="col-md-12 vid_description">
                                description: <strong>'.$vid_info->items[0]->snippet->description.'</strong>
                            </div>
                            <div class="col-md-12 vid_info">
                                duration: <strong>'.$this->parseYouTubeDuration($vid_info->items[0]->contentDetails->duration).'</strong>
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
                .($this->promote=='true'?
                '<span class="promote">
                    Get this plugin <a href="https://www.mindzgrouptech.net/wordpress-plugin-embed-video-link">here</a>.
                </span>':'').
            '</blockquote>';
    }
}