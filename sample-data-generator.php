<?php
/**
 * Plugin Name: Sample Data Generator
 * Description: Generate sample WordPress posts and WooCommerce products (optional) for testing and development
 * Version: 1.0.0
 * Author: Yogesh Sambare
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sample-data-generator
 * Domain Path: /languages
 * Requires PHP: 7.4
 *
 * @package Sample_Data_Generator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SMDG_VERSION', '1.0.0' );
define( 'SMDG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMDG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SMDG_PLUGIN_FILE', __FILE__ );

require_once SMDG_PLUGIN_DIR . 'includes/class-sample-data-generator.php';
require_once SMDG_PLUGIN_DIR . 'includes/class-smdg-admin.php';
require_once SMDG_PLUGIN_DIR . 'includes/class-smdg-product-generator.php';
require_once SMDG_PLUGIN_DIR . 'includes/class-smdg-post-generator.php';

function sample_data_generator_init() {
	if ( ! class_exists( 'Sample_Data_Generator' ) ) {
		return;
	}
	Sample_Data_Generator::init();
}

add_action( 'plugins_loaded', 'sample_data_generator_init' );

function sample_data_generator_activate() {
	flush_rewrite_rules();
}

register_activation_hook( SMDG_PLUGIN_FILE, 'sample_data_generator_activate' );

function sample_data_generator_deactivate() {
	flush_rewrite_rules();
}

register_deactivation_hook( SMDG_PLUGIN_FILE, 'sample_data_generator_deactivate' );
