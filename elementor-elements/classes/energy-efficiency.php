<?php

namespace See\Elementor\Widgets;

use See\Elementor\Widgets\SeeElementorBase;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class SeeEnergyEffciency extends SeeElementorBase {

    public $field_id = NULL;
    public $fields_list = array();

    public function __construct($data = array(), $args = null) {

		if ($this->is_edit_mode_load()) {
            $this->enqueue_styles_scripts();
        }

        parent::__construct($data, $args);

    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'sweet-energy-efficiency';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Sweet energy efficiency', 'sweet-energy-efficiency');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-flash';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {
        $this->generate_controls_conf();
        $this->generate_controls_layout();
        $this->generate_controls_styles();
        $this->generate_controls_content();

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();
        global $see_listing_id;
        $this->data['id_element'] = $this->get_id();
        $this->data['settings'] = $this->get_settings();


        $this->data['is_edit_mode']= false;          
        if(Plugin::$instance->editor->is_edit_mode()){
            $this->data['is_edit_mode']= true;
        }

        echo $this->view('energy-efficiency', $this->data);
    }

    private function generate_controls_conf() {
        $this->start_controls_section(
            'tab_conf_main_section',
            [
                'label' => esc_html__('Main', 'sweet-energy-efficiency'),
                'tab' => 1,
            ]
        );

		$this->add_control(
			'widget_id',
			[
				'label' => esc_html__( 'Widget id', 'sweet-energy-efficiency' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 1,
			]
		);

        $this->add_control(
			'value',
			[
				'label' => esc_html__( 'Value', 'sweet-energy-efficiency' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'XX',
			]
		);

        if (function_exists('run_wpdirectorykit')) {
            $this->add_control(
                'wdk_field_id',
                [
                        'label' => esc_html__('WDK field id', 'sweet-energy-efficiency'),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 1,
                        'step' => 1,
                    ]
            );
        }
        
        $this->end_controls_section();
    }

    private function generate_controls_layout() {
    }

    private function generate_controls_styles() {

    }

    private function generate_controls_content() {

    }
            
    public function enqueue_styles_scripts() {
    }
}
