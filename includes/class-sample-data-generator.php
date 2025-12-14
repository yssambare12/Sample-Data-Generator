<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sample_Data_Generator {

	public static function init() {
		if ( is_admin() ) {
			new SMDG_Admin();
		}
	}

	public static function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) || function_exists( 'WC' );
	}
}
