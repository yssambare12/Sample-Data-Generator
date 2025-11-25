<?php
/**
 * Plugin Name: WC Dummy Product & Post Generator
 * Description: Generate dummy WooCommerce products and WordPress posts for testing
 * Version: 1.0.0
 * Author: Yogesh Sambare
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wc-dummy-product-generator
 * Domain Path: /languages
 * Requires: WooCommerce
 * Requires PHP: 7.4
 *
 * @package WC_Dummy_Product_Generator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_DPG_VERSION', '1.0.0' );
define( 'WC_DPG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WC_DPG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WC_DPG_PLUGIN_FILE', __FILE__ );

require_once WC_DPG_PLUGIN_DIR . 'includes/class-wc-dummy-product-generator.php';
require_once WC_DPG_PLUGIN_DIR . 'includes/class-wc-dpg-admin.php';
require_once WC_DPG_PLUGIN_DIR . 'includes/class-wc-dpg-generator.php';
require_once WC_DPG_PLUGIN_DIR . 'includes/class-wc-dpg-post-generator.php';

function wc_dummy_product_generator_init() {
	if ( ! class_exists( 'WC_Dummy_Product_Generator' ) ) {
		return;
	}
	WC_Dummy_Product_Generator::init();
}

add_action( 'plugins_loaded', 'wc_dummy_product_generator_init' );

function wc_dummy_product_generator_activate() {
	// Activation logic
}

register_activation_hook( WC_DPG_PLUGIN_FILE, 'wc_dummy_product_generator_activate' );

function wc_dummy_product_generator_deactivate() {
	// Deactivation logic
}

register_deactivation_hook( WC_DPG_PLUGIN_FILE, 'wc_dummy_product_generator_deactivate' );
