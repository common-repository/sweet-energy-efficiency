<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Wpdirectorykit
 * @subpackage Wpdirectorykit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpdirectorykit
 * @subpackage Wpdirectorykit/admin
 * @author     listing-themes.com <dev@listing-themes.com>
 */
class See_elementor {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The name of Elementor Category.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $elementor_category_name = 'see-elementor';
    
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name='sweet-energy-efficiency', $version='1.0' ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdirectorykit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdirectorykit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'sweet-energy-efficiency-elementor-main', plugin_dir_url( __FILE__ ) . 'assets/css/sweet-energy-efficiency-main.css', array(), $this->version, 'all' );
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdirectorykit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdirectorykit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	
		wp_enqueue_script( 'sweet-energy-efficiency-elementor-main', plugin_dir_url( __FILE__ ) . 'assets/js/sweet-energy-efficiency-main.js', array(), $this->version, 'all' );
    }

	/**
	 * Load class/script files
	 *
	 * @since    1.0.0
	 */
	public function loader() {
        
    }

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
		require_once plugin_dir_path( __FILE__ ) . 'classes/elementor-base.php';
        require_once plugin_dir_path( __FILE__ ) . 'classes/energy-efficiency.php';

		do_action('sweet-energy-efficiency/elementor-elements/includes');
    }
   
	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {

		$this->add_widget('See\Elementor\Widgets\SeeEnergyEffciency');
	
		do_action('sweet-energy-efficiency/elementor-elements/register_widget', $this);
    }
        
    public function add_widget($class = ''){
        if(class_exists($class))
        {
            $object = new $class();
            \Elementor\Plugin::instance()->widgets_manager->register( $object );
        };
    }

    /**
     * Register Widget
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function register_modules() {
        
    }       

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
		$this->register_modules();
	}

	/**
	 * Add elementor category
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */  
    function load_category( $elements_manager ) {
        $elements_manager->add_category(
            $this->elementor_category_name,
            [
                'title' => esc_html__( 'Sweet Energy Efficiency', 'sweet-energy-efficiency' ),
                'icon' => 'fa fa-plug',
            ]
        );
    }


    /**
     * Start Elementor Addon
     */
    public function run() { 

		add_action( 'wp_enqueue_scripts',[ $this, 'enqueue_styles' ]);
		add_action( 'wp_enqueue_scripts',[ $this, 'enqueue_scripts' ]);

        add_action( 'elementor/elements/categories_registered', [ $this, 'load_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'on_widgets_registered' ] );

		do_action('sweet-energy-efficiency/elementor-elements/run');

        /* load module */
        $this->loader();
    }
}

/* Init lib after elementor loaded */
add_action( 'elementor/init', function() {
	$see_elementor = new See_elementor();
	$see_elementor -> run();
	do_action('sweet-energy-efficiency/elementor-elements/init');
});
