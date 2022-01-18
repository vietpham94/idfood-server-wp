<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://acffrontform.com
 * @since      1.0.0
 *
 * @package    Acf_Front_Form
 * @subpackage Acf_Front_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Front_Form
 * @subpackage Acf_Front_Form/admin
 * @author     Mourad Arifi <arifi.armedia@gmail.com>
 */
if ( !class_exists('Acf_Front_Form_Admin') ) :
class Acf_Front_Form_Admin {

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
	 * Admin settings
	 * 
	 * @since 1.2.0
	 * @var array
	 */
	protected $settings;

	/**
	 * 
	 */
	protected $doc_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $admin_settings ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = $admin_settings;
		//
		$v = explode( '.', $this->version );
		$doc_version = 'release-'. $v[0] . '.' . $v[1];
		//
		$this->doc_url = $this->settings['doc_uri'] . $doc_version .'/index.html';

	}

	/**
	 * Setting menu
	 * 
	 * @return void
	 */
	public function acf_front_form_menu() {
		add_options_page( $this->settings['display_settings'] , $this->settings['display'], 'manage_options', $this->plugin_name, array( $this, 'acf_front_form_settings' ) );
	}
	/**
	 * Render admin settings page
	 *
	 * @return void
	 */
	public function acf_front_form_settings(){

		require_once( 'partials/acf_front_form-admin-display.php' );
	}
	/**
	 * Update settings on submit
	 *
	 * @return void
	 */
	public function options_update() {

		register_setting( $this->plugin_name, $this->plugin_name, array($this, 'validate') );
		
	 }
	/**
	 * Validate form settings
	 *
	 * @param [type] $input
	 * @return void
	 */
	public function validate( $input ) {
		// All checkboxes inputs        
		$valid = array();
	
		//Cleanup
		$valid['form_head'] = (isset($input['form_head']) && !empty($input['form_head'])) ? 1 : 0;
		$valid['enque_uploader'] = (isset($input['enque_uploader']) && !empty($input['enque_uploader'])) ? 1 : 0;
		$valid['enque_js'] = (isset($input['enque_js']) && !empty($input['enque_js'])) ? 1 : 0;

		return $valid;
	 }

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	   $settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge( $settings_link, $links );
	
	}
	/**
	 * Add docs and meta link to the plugins page.
	 *
	 * @since    1.0.1
	 */
	public function add_row_meta( $links, $file ) {
		/**
		 * Doc http://wptips.me/how-to-use-plugin_action_links-and-plugin_row_meta-filters/
		 */
		if ( strpos( $file, $this->settings['plugin_file'] ) !== false ) {
			$meta_link = [
				'<a href="' . $this->doc_url . '" target="_blank">' . __('Read the docs', $this->plugin_name) . '</a>',
				array_pop( $links )
			];
			
			$links = array_merge( $links,  $meta_link );
		}

		return $links;
	
	}
	public function get_doc_url(){

		return $this->doc_url;
	}
}
endif;
