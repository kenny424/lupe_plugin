<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lupe
 * @subpackage lupe/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    lupe
 * @subpackage lupe/includes
 * @author     Your Name <email@example.com>
 */
class lupe_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'lupe_forms';
		$table_name_result = $wpdb->prefix . 'lupe_forms_result';
		$sql = "CREATE TABLE " . $table_name . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(100) NOT NULL,
				shortcode varchar(100) NOT NULL,
				form_structure text NOT NULL,
				form_mail_subject varchar(255) NOT NULL,
				form_mail_to varchar(255) NOT NULL,
				form_mail_from varchar(255) NOT NULL,
				date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY  (id)
		);
		CREATE TABLE " . $table_name_result . " (
				id int(11) NOT NULL AUTO_INCREMENT,
				id_form int(11) NOT NULL,
				result varchar(255) NOT NULL,
				date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				INDEX  (id_form),
				PRIMARY KEY  (id)
		);
		ALTER TABLE `wp_lupe_forms_result` ADD FOREIGN KEY (`id_form`) REFERENCES `wp_lupe_forms`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

}
