<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              listing-themes.com
 * @since             1.0.0
 * @package           Sweet_Energy_Efficiency
 *
 * @wordpress-plugin
 * Plugin Name:       Sweet Energy Efficiency
 * Plugin URI:        https://wpdirectorykit.com/plugins/sweet-energy-efficiency.html
 * Description:       Graphically Visually present Energy Efficiency Class / Label / Rating / Scale with related consumption values.
 * Version:           1.0.6
 * Author:            wpdirectorykit.com
 * Author URI:        https://wpdirectorykit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sweet-energy-efficiency
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SWEET_ENERGY_EFFICIENCY_VERSION', '1.0.6' );
define( 'SWEET_ENERGY_EFFICIENCY_NAME', 'see' );
define( 'SWEET_ENERGY_EFFICIENCY_PATH', plugin_dir_path( __FILE__ ) );
define( 'SWEET_ENERGY_EFFICIENCY_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sweet-energy-efficiency-activator.php
 */
function activate_sweet_energy_efficiency() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sweet-energy-efficiency-activator.php';
	Sweet_Energy_Efficiency_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sweet-energy-efficiency-deactivator.php
 */
function deactivate_sweet_energy_efficiency() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sweet-energy-efficiency-deactivator.php';
	Sweet_Energy_Efficiency_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sweet_energy_efficiency' );
register_deactivation_hook( __FILE__, 'deactivate_sweet_energy_efficiency' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sweet-energy-efficiency.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sweet_energy_efficiency() {

	$plugin = new Sweet_Energy_Efficiency();
	$plugin->run();

}
run_sweet_energy_efficiency();

?>