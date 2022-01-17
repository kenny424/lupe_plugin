<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Lupe
 * Description:       This is a plugin for wordpress
 * Version:           1.0.0
 * Author:            424
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
define( 'lupe_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lupe-activator.php
 */
function activate_lupe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lupe-activator.php';
	lupe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lupe-deactivator.php
 */
function deactivate_lupe() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lupe-deactivator.php';
	lupe_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lupe' );
register_deactivation_hook( __FILE__, 'deactivate_lupe' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lupe.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lupe() {

	$plugin = new lupe();
	$plugin->run();

}
run_lupe();
