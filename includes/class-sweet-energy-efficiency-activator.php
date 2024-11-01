<?php

/**
 * Fired during plugin activation
 *
 * @link       listing-themes.com
 * @since      1.0.0
 *
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sweet_Energy_Efficiency
 * @subpackage Sweet_Energy_Efficiency/includes
 * @author     listingthemes <dev@listing-themes.com>
 */
class Sweet_Energy_Efficiency_Activator {

    public static $see_db_version = 1.0;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$prefix = 'see_';
        
        /*
		add_option($prefix.'checkbox_log_plugin_disable', '1');
		add_option($prefix.'checkbox_log_cron_disable', '1');
		add_option($prefix.'checkbox_log_level_1_disable', '1');
		add_option($prefix.'checkbox_log_level_2_disable', '0');
		add_option($prefix.'checkbox_log_level_3_disable', '0');
		add_option($prefix.'checkbox_log_level_3_disable', '0');
		add_option($prefix.'log_days', '7');
		add_option($prefix.'checkbox_hide_ip', '0');
		add_option($prefix.'checkbox_failed_login_block', '0');
		add_option($prefix.'checkbox_enable_winterlock_dash_styles', '0');

		if (! wp_next_scheduled ( 'wal_my_hourly_event' )) {
			wp_schedule_event(time(), 'hourly', 'wal_my_hourly_event');
         }
         */
    }
    

	public static function plugins_loaded(){

		if ( get_site_option( 'see_db_version' ) === false ||
		     get_site_option( 'see_db_version' ) < self::$see_db_version ) {
			self::see_install();
		}

	}


	// https://codex.wordpress.org/Creating_Tables_with_Plugins
	public static function see_install() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$charset_collate = $wpdb->get_charset_collate();
		// For init version 1.0
		if(get_site_option( 'see_db_version' ) === false)
		{
			// Main table for graphs

			$table_name = $wpdb->prefix . 'see_graph';

			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				`idgraph` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(160) COLLATE utf8_unicode_ci NULL,
				`description` text COLLATE utf8_unicode_ci,
                `ratings_number` int(11) DEFAULT NULL,
                `unit` varchar(160) COLLATE utf8_unicode_ci NULL,
                `layout` varchar(160) COLLATE utf8_unicode_ci NULL,
                `json_data` text COLLATE utf8_unicode_ci,
				PRIMARY KEY  (idgraph)
			) $charset_collate COMMENT='Sweet energy efficiency plugin';";
		
            dbDelta( $sql );
            
			$sql = "INSERT INTO `$table_name` (`idgraph`, `title`, `description`, `ratings_number`, `unit`, `layout`, `json_data`) VALUES
            (NULL, 'Energy Efficiency Rating', 'Energy Efficiency Rating for EU', 7, 'energy efficient', 'basic', '[ {\"color\": \"#238735\" , \"from\": \"92\" , \"to\": \"100\" , \"label\": \"A\" }, {\"color\": \"#37c130\" , \"from\": \"81\" , \"to\": \"99\" , \"label\": \"B\" }, {\"color\": \"#3aea72\" , \"from\": \"69\" , \"to\": \"80\" , \"label\": \"C\" }, {\"color\": \"#e0c738\" , \"from\": \"55\" , \"to\": \"68\" , \"label\": \"D\" }, {\"color\": \"#dda858\" , \"from\": \"39\" , \"to\": \"54\" , \"label\": \"E\" }, {\"color\": \"#ffa114\" , \"from\": \"21\" , \"to\": \"38\" , \"label\": \"F\" }, {\"color\": \"#f25746\" , \"from\": \"1\" , \"to\": \"20\" , \"label\": \"G\" }, {\"color\": \"\" , \"from\": \"\" , \"to\": \"\" , \"label\": \"\" } ]');";
		
            dbDelta( $sql );

            

			update_option( 'see_db_version', "1" );
		}
	
		update_option( 'see_db_version', self::$see_db_version );
	}

}
