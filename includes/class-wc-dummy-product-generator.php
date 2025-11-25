<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Dummy_Product_Generator {

	public static function init() {
		if ( ! self::is_woocommerce_active() ) {
			add_action( 'admin_notices', array( __CLASS__, 'woocommerce_missing_notice' ) );
			return;
		}

		if ( is_admin() ) {
			new WC_DPG_Admin();
		}
	}

	public static function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) || function_exists( 'WC' );
	}

	public static function woocommerce_missing_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'WC Dummy Product & Post Generator requires WooCommerce to be installed and active.', 'wc-dummy-product-generator' ); ?></p>
		</div>
		<?php
	}
}
