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
}