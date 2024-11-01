<?php

namespace See\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class SeeElementorBase extends Widget_Base {
        /**
         * data array
         *
         * @var array
         */
        protected $data = array();

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
		return 'see-base';
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
		return esc_html__( 'See Widget Name', 'sweet-energy-efficiency' );
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
		return '';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'see-elementor' ];
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
            /* TAB_STYLE */ 
            $this->start_controls_section(
                    'section_form_css',
                    [
                            'label' => esc_html__( 'Custom сss', 'sweet-energy-efficiency' ),
                            'tab' => Controls_Manager::TAB_STYLE,
                    ]
            );

            $this->add_control(
                    'custom_css_title',
                    [
                            'raw' => esc_html__( 'Add your own custom CSS here', 'sweet-energy-efficiency' ),
                            'type' => Controls_Manager::RAW_HTML,
                    ]
            );

            $this->add_control(
                    'custom_css',
                    [
                            'type' => Controls_Manager::CODE,
                            'label' => esc_html__( 'Custom CSS', 'sweet-energy-efficiency' ),
                            'language' => 'css',
                            'render_type' => 'ui',
                            'show_label' => false,
                            'separator' => 'none',
                    ]
            );

            $this->add_control(
                    'custom_css_description',
                    [
                            'raw' => esc_html__( 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector', 'sweet-energy-efficiency' ),
                            'type' => Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-descriptor',
                    ]
            );

            $this->end_controls_section();
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
                $this->enqueue_styles_scripts();
                $this->add_page_settings_css();
	}

        
        public function view($view_file = '', $element = NULL, $print = false)
        {
            if(empty($view_file)) return false;
            $file = false;
            if(is_child_theme() && file_exists(get_stylesheet_directory().'/sweet-energy-efficiency/elementor-elements/views/'.$view_file.'.php'))
            {
                $file = get_stylesheet_directory().'/sweet-energy-efficiency/elementor-elements/views/'.$view_file.'.php';
            }
            elseif(file_exists(get_template_directory().'/sweet-energy-efficiency/elementor-elements/views/'.$view_file.'.php'))
            {
                $file = get_template_directory().'/sweet-energy-efficiency/elementor-elements/views/'.$view_file.'.php';
            }
            elseif(file_exists(SWEET_ENERGY_EFFICIENCY_PATH.'elementor-elements/views/'.$view_file.'.php'))
            {
                $file = SWEET_ENERGY_EFFICIENCY_PATH.'elementor-elements/views/'.$view_file.'.php';
            }

            if($file)
            {
                extract($element);
                if($print) {
                    include $file;
                } else {
                    ob_start();
                    include $file;
                    return ob_get_clean();
                }
            }
            else
            {
                if($print) {
                    echo 'View file not found in: '.esc_html($file);
                } else {
                    return 'View file not found in: '.esc_html($file);
                } 
            }
        }
                
        public function generate_renders_tabs($selectors = array(), $tab_prefix='', $enable_options = array(), $disable_options = array()) {
            /* margin */
            //$options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition','image_size_control];
            $options = ['typo','color','background','border','border_radius','padding','shadow']; // default
            
            /* defined */
            if(is_string($enable_options)){
                switch($enable_options) {
                    case 'block': $enable_options = ['typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'text-block': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'text': $enable_options = ['align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                    break;
                    case 'full': $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                    case deafult: $enable_options = ['margin','align','typo','color','background','border','border_radius','padding','shadow','transition'];
                                 break;
                }
            }
            
            /* enable options */
            if(!empty($enable_options)){
                $options = $enable_options;
            }
            $options_flip = array_flip($options);
            /* disable options */
            if(!empty($disable_options)){
                foreach ($disable_options as $value) {
                    if(isset($options_flip[$value]))
                        unset($options[$options_flip[$value]]);
                }
            }
            $tabs_enable = true;
            if(wmvc_count($selectors) == 1){
                $tabs_enable = false;
            }
            if($tabs_enable)
            $this->start_controls_tabs( $tab_prefix.'_style' );
            foreach($selectors as $key => $selector)
                $this->_generate_tabs($selector, $key, $tab_prefix, $options, $tabs_enable);
            if($tabs_enable)
            $this->end_controls_tabs();
            
        }
        
        public function _generate_tabs($selector='', $prefix = '', $type='', $options = array(), $tabs_enable = true) {
                if(empty($selector)) return false;
                
                if(empty($prefix) || $prefix == 'normal'){
                    $selector = $selector;
                    $prefix = 'normal';
                }
                else 
                    $selector = sprintf($selector,':'.$prefix);
                
                if($tabs_enable)
                    $this->start_controls_tab(
                            $type.'_'.$prefix.'_style',
                            [
                                    'label' => ucfirst($prefix),
                            ]
                    );
                
                if(in_array('typo',$options))
                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                                'name' => $type.'_typo'.$prefix,
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('align',$options))
                $this->add_responsive_control(
                    $type.'_align'.$prefix,
                    [
                            'label' => esc_html__( 'Alignment', 'sweet-energy-efficiency' ),
                            'type' => Controls_Manager::CHOOSE,
                            'options' => [
                                    'left' => [
                                            'title' => esc_html__( 'Left', 'sweet-energy-efficiency' ),
                                            'icon' => 'eicon-text-align-left',
                                    ],
                                    'center' => [
                                            'title' => esc_html__( 'Center', 'sweet-energy-efficiency' ),
                                            'icon' => 'eicon-text-align-center',
                                    ],
                                    'right' => [
                                            'title' => esc_html__( 'Right', 'sweet-energy-efficiency' ),
                                            'icon' => 'eicon-text-align-right',
                                    ],
                                    'justify' => [
                                            'title' => esc_html__( 'Justified', 'sweet-energy-efficiency' ),
                                            'icon' => 'eicon-text-align-justify',
                                    ],
                            ],
                            'selectors' => [
                                $selector => 'text-align: {{VALUE}};',
                            ],
                    ]
                );
                
                if(in_array('color',$options))
                $this->add_responsive_control(
                        $type.'_color'.$prefix,
                        [
                                'label' => esc_html__( 'Color', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'color: {{VALUE}};',
                                ],
                        ]
                );
    
                if(in_array('background',$options))
                $this->add_responsive_control(
                        $type.'_background'.$prefix,
                        [
                                'label' => esc_html__( 'Background', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::COLOR,
                                'selectors' => [
                                        $selector => 'background-color: {{VALUE}};',
                                ],
                        ]
                );
                
                if(in_array('border',$options))
                $this->add_group_control(
                        Group_Control_Border::get_type(), [
                                'name' => $type.'_border'.$prefix,
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('border_radius',$options))
                $this->add_responsive_control(
                        $type.'_border_radius'.$prefix,
                        [
                                'label' => esc_html__( 'Border Radius', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%' ],
                                'selectors' => [
                                        $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('padding',$options))
                $this->add_responsive_control(
                        $type.'_padding'.$prefix,
                        [
                                'label' => esc_html__( 'Padding', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                
                if(in_array('margin',$options))
                $this->add_responsive_control(
                        $type.'_margin'.$prefix,
                        [
                                'label' => esc_html__( 'Margin', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', 'em', '%' ],
                                'selectors' => [
                                        $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                ],
                        ]
                );
                
                if(in_array('shadow',$options))
                $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                                'name' => $type.'_box_shadow'.$prefix,
                                'exclude' => [
                                        'field_shadow_position',
                                ],
                                'selector' => $selector,
                        ]
                );
                
                if(in_array('transition',$options))
                $this->add_responsive_control(
                        $type.'_transition'.$prefix,
                        [
                                'label' => esc_html__( 'Transition Duration', 'sweet-energy-efficiency' ),
                                'type' => Controls_Manager::SLIDER,
                                'range' => [
                                        'px' => [
                                                'min' => 0,
                                                'max' => 3000,
                                        ],
                                ],
                                'selectors' => [
                                    $selector => 'transition-duration: {{SIZE}}ms',
                                ],
                        ]
                );
                if (in_array('image_size_control', $options)) {
                    $this->add_responsive_control(
                         $type.'_image_size_control_max_heigth'.$prefix,
                        [
                            'label' => esc_html__('Max Height', 'sweet-energy-efficiency'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'vw' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', 'vw','%' ],
                            'selectors' => [
                                $selector => 'max-height: {{SIZE}}{{UNIT}}',
                            ],
                            
                        ]
                    );
            
                    $this->add_responsive_control(
                         $type.'_image_size_control_max_width'.$prefix,
                        [
                            'label' => esc_html__('Max Width', 'sweet-energy-efficiency'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'vw' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', 'vw','%' ],
                            'selectors' => [
                                $selector => 'max-width: {{SIZE}}{{UNIT}}',
                            ],
                            
                        ]
                    );
            
                    $this->add_responsive_control(
                         $type.'_image_size_control_heigth'.$prefix,
                        [
                            'label' => esc_html__('Height', 'sweet-energy-efficiency'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'vw' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', 'vw','%' ],
                            'selectors' => [
                                $selector => 'height: {{SIZE}}{{UNIT}}',
                            ],
                            
                        ]
                    );
            
                    $this->add_responsive_control(
                         $type.'_image_size_control_width'.$prefix,
                        [
                            'label' => esc_html__('Width', 'sweet-energy-efficiency'),
                            'type' => Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 1500,
                                ],
                                'vw' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'size_units' => [ 'px', 'vw','%' ],
                            'selectors' => [
                                $selector => 'width: {{SIZE}}{{UNIT}}',
                            ],
                            
                        ]
                    );
                }
                if($tabs_enable)
                    $this->end_controls_tab();
            }
            
        
        /**
	 * @param $post_css Post
	 */
	public function add_page_settings_css() {
                $settings = $this->get_settings();
		$custom_css = $settings['custom_css'];
		$custom_css = trim( $custom_css );

		if ( empty( $custom_css ) ) {
			return;
		}
                
		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';


		$custom_css = str_replace( 'selector', '#see_el_' . $this->get_id(), $custom_css );
                
		echo '<style>'.wmvc_xss_clean($custom_css).'</style>';
	}
        
        private function break_css($css)
        {

            $results = array();
            preg_match_all('/(.+?)\s?\{\s?(.+?)\s?\}/', $css, $matches);
            foreach($matches[0] AS $i=>$original)
                foreach(explode(';', $matches[2][$i]) AS $attr)
                    if (strlen(trim($attr)) > 0) // for missing semicolon on last element, which is legal
                    {
                        list($name, $value) = explode(':', $attr);
                        $results[$matches[1][$i]][trim($name)] = trim($value);
                    }
            return $results;
        }

        public function generate_icon($icon, $attributes = [], $tag = 'i' ){
                if ( empty( $icon['library'] ) ) {
			return false;
		}
		$output = '';
		// handler SVG Icon
		if ( 'svg' === $icon['library'] ) {
			$output = \Elementor\Icons_Manager::render_svg_icon( $icon['value'] );
		} else {
			$output = $this->render_icon_html( $icon, $attributes, $tag );
		}

		return $output;
        }
        
	public function render_icon_html( $icon, $attributes = [], $tag = 'i' ) {
		$icon_types = \Elementor\Icons_Manager::get_icon_manager_tabs();
		if ( isset( $icon_types[ $icon['library'] ]['render_callback'] ) && is_callable( $icon_types[ $icon['library'] ]['render_callback'] ) ) {
			return call_user_func_array( $icon_types[ $icon['library'] ]['render_callback'], [ $icon, $attributes, $tag ] );
		}

		if ( empty( $attributes['class'] ) ) {
			$attributes['class'] = $icon['value'];
		} else {
			if ( is_array( $attributes['class'] ) ) {
				$attributes['class'][] = $icon['value'];
			} else {
				$attributes['class'] .= ' ' . $icon['value'];
			}
		}
		return '<' . $tag . ' ' . Utils::render_html_attributes( $attributes ) . '></' . $tag . '>';
	}

	public static function render_svg_icon( $value ) {
		if ( ! isset( $value['id'] ) ) {
			return '';
		}

		return Svg_Handler::get_inline_svg( $value['id'] );
	}

        public function enqueue_styles_scripts() {
    
        }

        public function is_edit_mode_load() {

                if (isset($this->is_edit_mode) &&  null !== $this->is_edit_mode ) {
                        return $this->is_edit_mode;
                }

                // Ajax request as Editor mode
                $actions = array(
                        'elementor',
                        // Templates
                        'elementor_get_templates',
                        'elementor_save_template',
                        'elementor_get_template',
                        'elementor_delete_template',
                        'elementor_export_template',
                        'elementor_import_template',
                );

                if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $actions)) {
                        return true;
                }

                if (isset($_REQUEST['elementor-preview'])) {
                        return true;
                }
       
                return false;
        }

}