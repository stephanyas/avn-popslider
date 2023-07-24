<?php

if(! class_exists('AVN_PopSlider_Shortcode')){
    class AVN_PopSlider_Shortcode{
        function __construct(){
            add_shortcode('avn_popslider', array($this, 'add_shortcode')); 
        }

        public function add_shortcode($atts = array(), $content = null, $tag = ''){

            $atts = array_change_key_case((array) $atts, CASE_LOWER);

            extract(shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ), 
                $atts,
                $tag
            ));

            if(!empty($id)){
                $id = array_map('absint', explode(',', $id));
            }

            ob_start();
            require(AVN_POPSLIDER_PATH . 'views/avn-popslider_shortcode.php');
            wp_enqueue_script('avn-popslider-main-jq');
            wp_enqueue_script('avn-popslider-options-js');
            wp_enqueue_style('avn-popslider-main-css');
            wp_enqueue_style('avn-popslider-style-css');
            return ob_get_clean();
        }
    }
}