<?php

add_shortcode('see_graph', 'register_see_graph_shortcode');
function register_see_graph_shortcode($atts, $content){
    $atts = shortcode_atts(array(
        'id'=>NULL,
        'value'=>NULL,
        'wdk_field_id'=>NULL,
    ), $atts);
    
    global $Winter_MVC;

    $page = 'see_shortcodes';
    $function = 'see_graph';

    static $widget_id;

    $widget_id++;

    $atts['widget_id'] = 'sh_'.$widget_id;

    if(!empty($atts['wdk_field_id']) && function_exists('run_wpdirectorykit')) {
        global $wdk_listing_id;
        if(!empty($wdk_listing_id)){
            $atts['value'] = wdk_field_value ($atts['wdk_field_id'], $wdk_listing_id);
        }
    }
    
    $Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
    $Winter_MVC->load_helper('basic');
    $output = $Winter_MVC->load_controller($page, $function, array($atts, $content));
    
    return $output;
}

?>