<?php
class EVL_Utilities {
    public static function getError($code) {
        $message = "";

        switch ($code) {
            case 'IVL_PLG': 
                $message = "Invalid plugin in shortcode ...";
                break;
            case 'IVL_VID': 
                $message = "Missing or invalid video id provided ...";
                break;
            case 'IVL_CFG': 
                $message = "Invalid plugin configuration ...";
                break;
            default:
                $message = "Something is not configured properly ...";
                break;
        }

        return "<strong>EVL: </strong>".$message;
    }

    public static function checkForUpdate($current) {
        $url = esc_url_raw( 'https://api.github.com/repos/MindzGroupTechnologies/WPEmbedVideoLink/releases' );
        $releases = self::getURLData($url);
        if(empty($releases)) {
            return false;
        } else {
            if(is_array($releases)) {
                $latest = $releases[0]->tag_name;
                return version_compare($current, $latest, '<');
            } else {
                return false;
            }
        }
    }

    public static function getURLData($evl_url, $parse_json=true) {
        // request the content from remote
        $videos_result = wp_remote_get( $evl_url );
        
        // get the response code and message
        $response_code = wp_remote_retrieve_response_code( $videos_result );
        $response_message = wp_remote_retrieve_response_message( $videos_result );

        // check if the request was successful
        if ( $response_code != 200 ) {
            return null;
        }

        // check if the response contains results
        if( is_array($videos_result) ) {
            // get the response body
            $body = wp_remote_retrieve_body( $videos_result );
            
            // parse and return the object from json if requested
            if($parse_json) {
                return json_decode($body);
            }

            // return the body otherwise
            return $body;
        } else {
            // return null if response contains no results
            return null;
        }
    }
}