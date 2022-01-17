<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lupe
 * @subpackage lupe/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    lupe
 * @subpackage lupe/public
 * @author     Your Name <email@example.com>
 */
class lupe_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	public function load_public_lupe_style () {

			require plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/lupe-public-display.php';
			wp_enqueue_style( $this->lupe, plugin_dir_url(__FILE__)  . 'css/lupe-public.css', false, $this->version );
			wp_enqueue_style( $this->lupe, plugin_dir_url(__FILE__)  . 'css/bootstrap.min.css', false, $this->version );
			//	wp_enqueue_style( $this->lupe, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	 public function load_public_lupe_js () {

		 wp_enqueue_script('jquery');// Include Wordpress Jquery
		 wp_enqueue_script('jquery-ui-sortable');// Include Wordpress Jquery Ui

		 wp_enqueue_script( 'lupe-form-builder-html-generator', plugin_dir_url(__FILE__) . 'js/lupe-html-generator.js', '', $this->version, false );
		 wp_enqueue_script( 'lupe-form-validate', plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', '', $this->version, false );
		 wp_enqueue_script( 'lupe-form-tether', plugin_dir_url(__FILE__) . 'js/tether.min.js', '', $this->version, false );
		 wp_enqueue_script( 'lupe-form-bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', '', $this->version, false );
		 wp_localize_script( 'lupe-form-builder', 'ajax_form_object', array(
				 'ajaxurl' => admin_url( 'admin-ajax.php' ),
				 'ajax_nonce' => wp_create_nonce('check_lupe_form')
		 ));

		 wp_localize_script( 'lupe-form-builder-html-generator', 'ajax_form_front_object', array(
				 'ajaxurl' => admin_url( 'admin-ajax.php' )
		 ));
		 //wp_enqueue_script( $this->lupe, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
	 }
	 public function front_lupe_form() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'lupe_forms';
		$show_formID = sanitize_key( $_GET['show_form_id'] );
		$show_query = $wpdb->get_results(
			"
			SELECT *
			FROM $table_name
			WHERE id = $show_formID
			"
		);
		foreach ( $show_query as $show_result ) {
			echo $show_result->form_structure;
		}
			die();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

}
