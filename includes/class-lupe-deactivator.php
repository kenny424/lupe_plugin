<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lupe
 * @subpackage lupe/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    lupe
 * @subpackage lupe/includes
 * @author     Your Name <email@example.com>
 */
class lupe_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;
    $table_name = $wpdb->prefix . 'lupe_forms';
    $table_name_result = $wpdb->prefix . 'lupe_forms_result';
    $sql = "DROP TABLE $table_name_result, $table_name";
    $wpdb->query($sql);
	}

}
