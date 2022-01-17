<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lupe
 * @subpackage lupe/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    lupe
 * @subpackage lupe/admin
 * @author     Your Name <email@example.com>
 */


class lupe_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lupe    The ID of this plugin.
	 */
	private $lupe;

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
	 * @param      string    $lupe       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lupe, $version ) {

		$this->lupe = $lupe;
		$this->version = $version;
	}
	 /**
 	 * Register the stylesheets for the admin area.
 	 *
 	 * @since    1.0.0
 	 */
	public function load_admin_lupe_style () {

			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lupe-admin-table-list.php';
			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lupe-admin-add-form.php';
			require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lupe-admin-form-result.php';

			wp_register_style( 'softech-form-style', plugin_dir_url(__FILE__) . 'css/lupe-admin.css', false, $this->version );
	    wp_register_style( 'softech-form-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', false, $this->version );
	    wp_enqueue_style('softech-form-style');
	    wp_enqueue_style('softech-form-bootstrap');

			//	wp_enqueue_style( $this->lupe, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	 public function load_admin_lupe_js () {

		 wp_enqueue_script('jquery');// Include Wordpress Jquery
		 wp_enqueue_script('jquery-ui-sortable');// Include Wordpress Jquery Ui
		 wp_enqueue_script( 'lupe-form-builder', plugin_dir_url(__FILE__) . 'js/lupe-builder.js', '', $this->version, false );
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

	 public function load_lupe_form() {
		 global $wpdb;

		 $table_name = $wpdb->prefix . 'lupe_forms';
		 $formID = sanitize_key( $_GET['formID'] );
		 $query = $wpdb->get_results(
		 "
			 SELECT *
			 FROM $table_name
			 WHERE id = $formID

		 "
	 	 );
		 foreach ( $query as $result )
		 {
			 echo $result->form_structure;
		 }
		 die();
	 }
	 /**
	 	* Build admin main menu
	 	*/
	 	public function admin_menu_lupe () {
			add_menu_page( 'Lupe forms',
										 'Lupe',
										 'manage_options',
										 'lupe-form-builder-list',
										 'lupe_form_builder_list',
										 'dashicons-email',
										 6
			);
			add_submenu_page( 'lupe-form-builder-list',
	                      'Lupe формы',
	                      'Lupe формы',
	                      'manage_options',
	                      'lupe-form-builder-list',
	                      'lupe_form_builder_list'
	    );

	    add_submenu_page( 'lupe-form-builder-list',
	                      'Создать форму',
	                      'Создать форму',
	                      'manage_options',
	                      'lupe-new-form',
	                      'lupe_wp_form_page_handler'
	    );
			add_submenu_page( 'lupe-form-builder-list',
	                      'Результаты форм',
	                      'Результаты форм',
	                      'manage_options',
	                      'lupe-form-result-list',
	                      'lupe_form_result_list'
	    );
	 	}

		public function save_lupe_form() {
			check_ajax_referer( 'check_lupe_form', 'security' );
			global $wpdb;
			$table_name = $wpdb->prefix . 'lupe_forms';

			//get the submitted data and decode it
			$formID = sanitize_key( $_POST['formId'] );
			$formName = sanitize_text_field( $_POST['formName'] );
			$formSubject = sanitize_text_field( $_POST['formSubject'] );
			$formTo = sanitize_email( $_POST['formTo'] );
			$formFrom = sanitize_email( $_POST['formFrom'] );
			$formFields1 = sanitize_text_field( $_POST['formFields'] );
			$formFields = str_replace('\"','"',$formFields1);

			$data = array(
					'name' => $formName,
					'shortcode' => '',
					'form_structure' => $formFields,
					'form_mail_subject' => $formSubject,
					'form_mail_to' => $formTo,
					'form_mail_from' => $formFrom
					);

			$data_update = array(
					'name' => $formName,
					'shortcode' => '[lupe-form-builder id="'.$formID.'" name="'.$formName.'"]',
					'form_structure' => $formFields,
					'form_mail_subject' => $formSubject,
					'form_mail_to' => $formTo,
					'form_mail_From' => $formFrom
					);
			if($formID == "false")
			{
			 $wpdb->insert($table_name, $data);
			 $last_id = $wpdb->insert_id;

			 $updateshortcode=array(
													'shortcode' => '[lupe-form-builder id="'.$last_id.'" name="'.$formName.'"]'
											);
			 $wpdb->update( $table_name, $updateshortcode, array('id' => $last_id));
			}
			else
			{
			 $wpdb->update($table_name, $data_update, array('id' => $formID));
			}
			echo json_encode('your form would have saved successfully!');
			die();
		}
}
