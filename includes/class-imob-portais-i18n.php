<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://brainmade.com.br
 * @since      1.0.0
 *
 * @package    Imob_Portais
 * @subpackage Imob_Portais/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Imob_Portais
 * @subpackage Imob_Portais/includes
 * @author     Raphael Batagini <raphabatagini@gmail.com>
 */
class Imob_Portais_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'imob-portais',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
