<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codecanyon.net/item/acf-front-form-with-visual-composer-integration/21347225
 * @since      1.0.0
 *
 * @package    Acf_Front_Form
 * @subpackage Acf_Front_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Acf_Front_Form
 * @subpackage Acf_Front_Form/public
 * @author     Mourad Arifi <arifi.armedia@gmail.com>
 */
if ( ! class_exists ( 'Acf_Front_Form_Public' ) ):
class Acf_Front_Form_Public {

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

	/**
	 * Initialization
	 *
	 * @return void
	 */
	public function Init(){

		$this->options = get_option( $this->plugin_name );

		$this->options['form_head'] = isset( $this->options['form_head'] ) ? $this->options['form_head'] : 1;
		$this->options['enque_js'] = isset( $this->options['enque_js'] ) ? $this->options['enque_js'] : 0;
		$this->options['enque_uploader'] = isset( $this->options['enque_uploader'] ) ? $this->options['enque_uploader'] : 0;
		//

		// acf_form_head before get_header()
		if( $this->options['form_head'] == 1 ){

			if ( defined( 'ACF_HEAD_HOOK' ) ){

				add_action( ACF_HEAD_HOOK, [ $this, 'acf_front_form_head' ]);
			}else{

				add_action( 'wp_head', [ $this, 'acf_front_form_head' ], 5);
			}
			
			
		}
		
		// acf_enqueue_uploader before get_footer()
		if( $this->options['enque_uploader'] == 1 ){
			add_action( 'wp_footer', [ $this, 'acf_front_form_enqueue_uploader' ], 1);
		}

		// Place ACF inline JS
		if( $this->options['enque_js'] == 1 ){
			add_action( 'wp_enqueue_scripts', [ $this, 'acf_front_form_acf_inline_js' ] );
		}
	}

	/**
	 * Call to acf_form_head() function
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf_form_head/
	 * @return void
	 */
	public function acf_front_form_head(){
		if( !is_admin() ){
			acf_form_head();
		}
	}
	/**
	 * Call to acf_enqueue_uploader() function
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf_form_head/
	 * @return void
	 */
	public function acf_front_form_enqueue_uploader(){
		if( !is_admin() ){
			acf_enqueue_uploader();
		}
	}
	/**
	 * ACF inline JS
	 * 
	 * @link https://www.advancedcustomfields.com/resources/acf_form/#ajax
	 * @return void
	 */
	public function acf_front_form_acf_inline_js(){
		wp_add_inline_script( $this->plugin_name, "(function( $ ) {acf.do_action('append', $('#popup-id'));})( jQuery );");
	}

}
endif;
