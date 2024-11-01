<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 * @author     listingthemes <dev@listing-themes.com>
 */
class Sweet_Energy_Efficiency_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sweet-energy-efficiency',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
