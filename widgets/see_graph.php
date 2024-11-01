<?php


class See_Graph_Widget extends WP_Widget{
    
    public static $multiple_instance=false;
    
    public static $widget_id=0;
    
    private $defaults = array(
        'title' => '',
        'idgraph' => '',
        'value' => 'XX'
    );

    function __construct()
    {
        $this->defaults['title'] = __('Energy Efficiency Graph', 'sweet-energy-efficiency');


        $options = array(
            'title' => __('Energy Efficiency Graph', 'sweet-energy-efficiency'),
            'name'  => __('Energy Efficiency Graph', 'sweet-energy-efficiency'),
        );
        
        parent::__construct('See_Graph_Widget', $options['name'], $options);
    }
    

    /**
     * Generates the back-end layout for the widget
     */
    public function form($instance) {

        static $form_counter = 1;

        $form_counter++;

        $instance = wp_parse_args((array) $instance, $this->defaults);

        // get list of graphs
        global $Winter_MVC;
        
		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
        $Winter_MVC->model('graph_m');
        $graphs = $Winter_MVC->graph_m->get();

        // The widget content 

        foreach ($this->defaults as $key => $val) {
            if($key == 'idgraph'):
                ?>
                <p>
                    <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(__('Graph', 'sweet-energy-efficiency')); ?></label>
                    <select class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>">
                        <?php if(is_array($graphs))
                                foreach($graphs as $graph): ?>
                        <option value="<?php echo esc_attr($graph->idgraph); ?>" <?php if($instance[$key] == $graph->idgraph)echo 'selected="selected"';?>><?php echo esc_html($graph->title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <?php
            else:
                ?>
                <p>
                    <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                    <input type="text" class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>" value="<?php echo esc_attr($instance[$key]); ?>" />
                </p>
                <?php
            endif;
        }
    }
    
    /**
     * Processes the widget's values
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Update values
        foreach ($this->defaults as $key => $val) {
            if (substr_count($key, 'embed') > 0) {
                $instance[$key] = $new_instance[$key];
            } else {
                $instance[$key] = strip_tags(stripslashes($new_instance[$key]));
            }
        }

        return $instance;
    }
    
    public function widget($args, $instance)
    {
        if(self::$multiple_instance === true)return;
        
        extract($args);
        extract($instance);
        $atts = array_merge($instance, $args);
        
        $atts = shortcode_atts(array(
            'idgraph'=>'',
            'value'=>'',
            'title'=>''
        ), $atts);

        self::$widget_id++;

        $atts['widget_id'] = 'wi_'.self::$widget_id;
        
        global $Winter_MVC;

        $page = 'see_widgets';
        $function = 'see_graph';

		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
        $output = $Winter_MVC->load_controller($page, $function, array($atts));
        
        echo wp_kses_post($before_widget);
        if(!empty($title))
            echo wp_kses_post($before_title.$title.$after_title);
            echo wp_kses_post("$output");
        echo wp_kses_post($after_widget);
        
        //self::$multiple_instance = true;
    }

    
}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "See_Graph_Widget" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'See_Graph_Widget' );});
