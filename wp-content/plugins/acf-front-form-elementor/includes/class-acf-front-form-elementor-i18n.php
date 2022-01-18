<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    ACF_FRONT_FORM_Elementor
 * @subpackage ACF_FRONT_FORM_Elementor/includes
 * @author     Mourad Arifi <arifi.armedia@gmail.com>
 */
class ACF_FRONT_FORM_Elementor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'acf-front-form-elementor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/lang/'
		);

	}
}
