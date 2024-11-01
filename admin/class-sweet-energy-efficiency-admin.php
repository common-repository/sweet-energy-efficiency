<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/admin
 * @author     listingthemes <dev@listing-themes.com>
 */
class Sweet_Energy_Efficiency_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

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
		 * defined in Sweet_Energy_Efficiency_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sweet_Energy_Efficiency_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sweet-energy-efficiency-admin.css', array(), $this->version, 'all' );

        wp_enqueue_style( 'wp-color-picker' );


		wp_register_style('sweet-energy-efficiency_basic_wrapper', plugin_dir_url( __FILE__ ).'css/basic.css', false, '1.0.0' );

		wp_register_style( 'dataTables-select', plugin_dir_url( __FILE__ ) . 'css/select.dataTables.min.css' );

		wp_register_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', false, '1.0.0' );
		
		wp_enqueue_style( 'font-awesome' );

		wp_enqueue_style( 'sweet-energy-efficiency-style', plugin_dir_url( __FILE__ ) . 'css/style.css', false, '1.0.0' );

        if(is_rtl()){
           wp_enqueue_style( 'sweet-energy-efficiency-rtl',  plugin_dir_url( __FILE__ ) . 'css/style_rtl.css');
		}

                
        if(get_option('wal_checkbox_enable_winterlock_dash_styles') > 0){
            wp_enqueue_style('winter-activity-admin-ui-dashboard', plugin_dir_url( __FILE__ ) . 'css/frontend-dashboard.css', array(), '1.1' );
        }

        wp_enqueue_style( 'contact-admin', plugin_dir_url( __FILE__ ) . 'css/contact-admin.css', false, '1.0.0'  );

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
		 * defined in Sweet_Energy_Efficiency_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sweet_Energy_Efficiency_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sweet-energy-efficiency-admin.js', array( 'jquery' ), $this->version, false );

		wp_dequeue_script('datatables');
		wp_deregister_script('datatables');

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sweet-energy-efficiency-admin.js', array( 'jquery' ), $this->version, false );

        wp_register_script( 'datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array( 'jquery' ), false, false );
        wp_register_script( 'dataTables-responsive', plugin_dir_url( __FILE__ ) . 'js/dataTables.responsive.js', array( 'jquery' ), false, false );
        wp_register_script( 'dataTables-select', plugin_dir_url( __FILE__ ) . 'js/dataTables.select.min.js', array( 'jquery' ), false, false );

        wp_enqueue_script( 'wp-color-picker');
    }

	/**
	 * Admin AJAX
	 */

	public function sweet_energy_efficiency_action()
	{
		global $Winter_MVC;

		$page = '';
		$function = '';

		if(isset($_POST['page']))$page = wmvc_xss_clean($_POST['page']);
		if(isset($_POST['function']))$function = wmvc_xss_clean($_POST['function']);

		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
		$Winter_MVC->load_controller($page, $function, array());
	}

    /**
	 * Admin Page Display
	 */
	public function admin_page_display() {
		global $Winter_MVC;

		$page = '';
        $function = '';

		if(isset($_GET['page']))$page = wmvc_xss_clean($_GET['page']);
		if(isset($_GET['function']))$function = wmvc_xss_clean($_GET['function']);

		$Winter_MVC = new MVC_Loader(plugin_dir_path( __FILE__ ).'../');
		$Winter_MVC->load_helper('basic');
		$Winter_MVC->load_controller($page, $function, array());
	}

    
		/**
		 * To add Plugin Menu and Settings page
		 */
		public function plugin_menu() {

            ob_start();

            add_menu_page(__('Energy Efficiency','sweet-energy-efficiency'), __('Energy Efficiency','sweet-energy-efficiency'), 
                'manage_options', 'sweet-energy-efficiency', array($this, 'admin_page_display'),
                //plugin_dir_url( __FILE__ ) . 'resources/logo.png',
                'dashicons-chart-bar',
                51 );
			
            add_submenu_page('sweet-energy-efficiency', 
                            __('Manage Graphs','sweet-energy-efficiency'), 
                            __('Manage Graphs','sweet-energy-efficiency'),
                            'manage_options', 'sweet-energy-efficiency', array($this, 'admin_page_display'));
							
			add_submenu_page('sweet-energy-efficiency', 
                            __('Add Graph','sweet-energy-efficiency'), 
                            __('Add Graph','sweet-energy-efficiency'),
							'manage_options', 'see_add_graph', array($this, 'admin_page_display'));

			add_submenu_page('sweet-energy-efficiency', 
                            __('Contact Us','sweet-energy-efficiency'), 
                            __('Contact Us','sweet-energy-efficiency'),
							'manage_options', 'see_contact', array($this, 'admin_page_display'));

		}

}

?>