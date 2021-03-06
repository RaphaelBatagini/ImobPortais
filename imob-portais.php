<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://brainmade.com.br
 * @since             1.0.0
 * @package           Imob_Portais
 *
 * @wordpress-plugin
 * Plugin Name:       ImobPortais
 * Plugin URI:        https://github.com/RaphaelBatagini/ImobPortais
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Raphael Batagini
 * Author URI:        https://brainmade.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       imob-portais
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-imob-portais-activator.php
 */
function activate_imob_portais() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-imob-portais-activator.php';
	Imob_Portais_Activator::activate();
	wp_schedule_event(time(), 'daily', 'load_imob_portais_cron');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-imob-portais-deactivator.php
 */
function deactivate_imob_portais() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-imob-portais-deactivator.php';
	Imob_Portais_Deactivator::deactivate();
	wp_clear_scheduled_hook('load_imob_portais_cron');
}

register_activation_hook( __FILE__, 'activate_imob_portais' );
register_deactivation_hook( __FILE__, 'deactivate_imob_portais' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-imob-portais.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_imob_portais() {

	$plugin = new Imob_Portais();
	$plugin->run();

}
run_imob_portais();
