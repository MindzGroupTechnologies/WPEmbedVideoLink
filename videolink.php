<?php
class EVL_Front {
    public function __construct () {
        // register shortcode
        add_shortcode( 'evl', array( $this, 'processShortcode' ) ); 

        // add frontend styles
        add_action( 'wp_enqueue_scripts', array( $this, 'registerStyles' ) );
    }

    // function to register styles
    public function registerStyles() {
        wp_register_style( 'evl', plugins_url( '/assets/css/main.min.css', __FILE__ ) );
        wp_enqueue_style( 'evl' );
    }

    // function to render shortcode
    public function processShortcode( $provided_attr, $content = null ) {
        $default_attr = array (
            'vid' => '',
            'plug' => 'YouTube',
            'promote' => 'true'
        );

        $attrs = shortcode_atts( $default_attr, $provided_attr, 'evl' );
        
        if(empty($attrs['plug'])) {
            return EVL_Utilities::getError('IVL_PLG'); 
        }

        switch ($attrs['plug']) {
            case 'YouTube':
                $youtube_plugin = new EVL_YouTube_Plugin($attrs['vid'], $content);
                return $youtube_plugin->render();
                break;            
            default:
                echo "this is it";
                return EVL_Utilities::getError('IVL_PLG');
                break;
        }
    }
}