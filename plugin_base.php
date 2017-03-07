<?php 
abstract class EVL_Plugin_Base {
    public $promote = true;

    abstract function render();

    protected function getURLData($evl_url, $parse_json=true) {
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