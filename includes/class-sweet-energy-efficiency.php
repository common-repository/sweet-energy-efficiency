<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 * @author     listingthemes <dev@listing-themes.com>
 */
class Sweet_Energy_Efficiency {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sweet_Energy_Efficiency_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SWEET_ENERGY_EFFICIENCY_VERSION' ) ) {
			$this->version = SWEET_ENERGY_EFFICIENCY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sweet-energy-efficiency';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_plugins_upgrade_hooks();

        $this->define_shortcode_hooks();
        $this->define_widget_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sweet_Energy_Efficiency_Loader. Orchestrates the hooks of the plugin.
	 * - Sweet_Energy_Efficiency_i18n. Defines internationalization functionality.
	 * - Sweet_Energy_Efficiency_Admin. Defines all hooks for the admin area.
	 * - Sweet_Energy_Efficiency_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sweet-energy-efficiency-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/helper-functions.php';


		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sweet-energy-efficiency-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sweet-energy-efficiency-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sweet-energy-efficiency-public.php';
        
        // Load Winter MVC core
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/Winter_MVC/init.php';
			
		// Load Elementor Elements
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor-elements/elementor-init.php';

		$this->loader = new Sweet_Energy_Efficiency_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sweet_Energy_Efficiency_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sweet_Energy_Efficiency_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sweet_Energy_Efficiency_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/**
		 * Adding Plugin Admin Menu
		 */
		$this->loader->add_action(
			'admin_menu',
			$plugin_admin,
			'plugin_menu'
        );
        
        $this->loader->add_action(
			'wp_ajax_sweet-energy-efficiency_action',
			$plugin_admin,
			'sweet_energy_efficiency_action'
		);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sweet_Energy_Efficiency_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sweet_Energy_Efficiency_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
    }
    
    public function define_plugins_upgrade_hooks()
	{
		require_once  plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sweet-energy-efficiency-activator.php';

		$this->loader->add_action( 'plugins_loaded', 'Sweet_Energy_Efficiency_Activator', 'plugins_loaded' );
    }
    
    public function define_shortcode_hooks()
    {
        require(plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/see_graph.php');

    }

    public function define_widget_hooks()
    {
        require(plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/see_graph.php');

    }

}
