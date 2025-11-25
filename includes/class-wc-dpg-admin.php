<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_DPG_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_wc_dpg_generate_products', array( $this, 'generate_products_ajax' ) );
		add_action( 'wp_ajax_wc_dpg_generate_posts', array( $this, 'generate_posts_ajax' ) );
	}

	public function add_admin_menu() {
		add_menu_page(
			esc_html__( 'Dummy Content', 'wc-dummy-product-generator' ),
			esc_html__( 'Dummy Content', 'wc-dummy-product-generator' ),
			'manage_woocommerce',
			'wc-dummy-content',
			array( $this, 'render_settings_page' ),
			'dashicons-admin-generic',
			56
		);
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( 'toplevel_page_wc-dummy-content' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'wc-dpg-admin-style',
			WC_DPG_PLUGIN_URL . 'admin/css/admin.css',
			array(),
			WC_DPG_VERSION
		);

		wp_enqueue_script(
			'wc-dpg-admin-script',
			WC_DPG_PLUGIN_URL . 'admin/js/admin.js',
			array( 'jquery' ),
			WC_DPG_VERSION,
			true
		);

		wp_localize_script(
			'wc-dpg-admin-script',
			'wcDpgSettings',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wc_dpg_generate_nonce' ),
			)
		);
	}

	public function render_settings_page() {
		?>
		<div class="wrap wc-dpg-wrapper">
			<h1><?php esc_html_e( 'Dummy Content Generator', 'wc-dummy-product-generator' ); ?></h1>

			<div class="wc-dpg-tabs">
				<button class="tab-button active" data-tab="products">
					<?php esc_html_e( 'Generate Products', 'wc-dummy-product-generator' ); ?>
				</button>
				<button class="tab-button" data-tab="posts">
					<?php esc_html_e( 'Generate Posts', 'wc-dummy-product-generator' ); ?>
				</button>
			</div>

			<div class="wc-dpg-content">
				<div id="products-tab" class="tab-content active">
					<div class="wc-dpg-container">
						<div class="wc-dpg-form-card">
							<h2><?php esc_html_e( 'WooCommerce Products', 'wc-dummy-product-generator' ); ?></h2>

							<form id="wc-dpg-products-form" class="wc-dpg-form">
								<div class="form-group">
									<label for="product-count">
										<?php esc_html_e( 'Number of Products', 'wc-dummy-product-generator' ); ?>
									</label>
									<input
										type="number"
										id="product-count"
										name="product_count"
										value="5"
										min="1"
										max="100"
										required
									>
									<small><?php esc_html_e( 'Create between 1 and 100 products', 'wc-dummy-product-generator' ); ?></small>
								</div>

								<div class="form-group">
									<label for="product-type">
										<?php esc_html_e( 'Product Type', 'wc-dummy-product-generator' ); ?>
									</label>
									<select id="product-type" name="product_type" required>
										<option value="simple"><?php esc_html_e( 'Simple Products', 'wc-dummy-product-generator' ); ?></option>
										<option value="variable"><?php esc_html_e( 'Variable Products', 'wc-dummy-product-generator' ); ?></option>
										<option value="mixed"><?php esc_html_e( 'Mixed (50/50)', 'wc-dummy-product-generator' ); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="product-category">
										<?php esc_html_e( 'Category', 'wc-dummy-product-generator' ); ?>
									</label>
									<select id="product-category" name="product_category">
										<option value=""><?php esc_html_e( 'Random Category', 'wc-dummy-product-generator' ); ?></option>
										<?php $this->render_product_category_options(); ?>
									</select>
								</div>

								<div class="form-group">
									<label for="base-price">
										<?php esc_html_e( 'Base Price (USD)', 'wc-dummy-product-generator' ); ?>
									</label>
									<input
										type="number"
										id="base-price"
										name="base_price"
										value="29.99"
										step="0.01"
										min="1"
										required
									>
									<small><?php esc_html_e( 'Product prices vary around this base price', 'wc-dummy-product-generator' ); ?></small>
								</div>

								<div class="form-group checkbox">
									<input
										type="checkbox"
										id="add-product-images"
										name="add_images"
										value="1"
										checked
									>
									<label for="add-product-images">
										<?php esc_html_e( 'Add Random Product Images', 'wc-dummy-product-generator' ); ?>
									</label>
								</div>

								<div class="form-group">
									<button type="submit" class="button button-primary button-large" id="generate-products-btn">
										<?php esc_html_e( 'Generate Products', 'wc-dummy-product-generator' ); ?>
									</button>
								</div>
							</form>

							<div id="wc-dpg-products-progress" class="wc-dpg-progress" style="display: none;">
								<div class="progress-bar">
									<div class="progress-fill"></div>
								</div>
								<p class="progress-text"></p>
							</div>

							<div id="wc-dpg-products-result" class="wc-dpg-result" style="display: none;"></div>
						</div>

						<div class="wc-dpg-info-card">
							<h3><?php esc_html_e( 'Product Info', 'wc-dummy-product-generator' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Simple or variable products', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Customizable pricing', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Random product images', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Realistic product data', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Perfect for testing', 'wc-dummy-product-generator' ); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<div id="posts-tab" class="tab-content" style="display: none;">
					<div class="wc-dpg-container">
						<div class="wc-dpg-form-card">
							<h2><?php esc_html_e( 'WordPress Posts', 'wc-dummy-product-generator' ); ?></h2>

							<form id="wc-dpg-posts-form" class="wc-dpg-form">
								<div class="form-group">
									<label for="post-count">
										<?php esc_html_e( 'Number of Posts', 'wc-dummy-product-generator' ); ?>
									</label>
									<input
										type="number"
										id="post-count"
										name="post_count"
										value="5"
										min="1"
										max="100"
										required
									>
									<small><?php esc_html_e( 'Create between 1 and 100 posts', 'wc-dummy-product-generator' ); ?></small>
								</div>

								<div class="form-group">
									<label for="post-category">
										<?php esc_html_e( 'Category', 'wc-dummy-product-generator' ); ?>
									</label>
									<select id="post-category" name="post_category">
										<option value=""><?php esc_html_e( 'Random Category', 'wc-dummy-product-generator' ); ?></option>
										<?php $this->render_post_category_options(); ?>
									</select>
								</div>

								<div class="form-group checkbox">
									<input
										type="checkbox"
										id="add-post-images"
										name="add_images"
										value="1"
										checked
									>
									<label for="add-post-images">
										<?php esc_html_e( 'Add Featured Images', 'wc-dummy-product-generator' ); ?>
									</label>
								</div>

								<div class="form-group">
									<button type="submit" class="button button-primary button-large" id="generate-posts-btn">
										<?php esc_html_e( 'Generate Posts', 'wc-dummy-product-generator' ); ?>
									</button>
								</div>
							</form>

							<div id="wc-dpg-posts-progress" class="wc-dpg-progress" style="display: none;">
								<div class="progress-bar">
									<div class="progress-fill"></div>
								</div>
								<p class="progress-text"></p>
							</div>

							<div id="wc-dpg-posts-result" class="wc-dpg-result" style="display: none;"></div>
						</div>

						<div class="wc-dpg-info-card">
							<h3><?php esc_html_e( 'Post Info', 'wc-dummy-product-generator' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Realistic blog posts', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Random categories', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Featured images', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Quality content', 'wc-dummy-product-generator' ); ?></li>
								<li><?php esc_html_e( 'Ready to publish', 'wc-dummy-product-generator' ); ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	private function render_product_category_options() {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		) );

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				printf(
					'<option value="%d">%s</option>',
					esc_attr( $category->term_id ),
					esc_html( $category->name )
				);
			}
		}
	}

	private function render_post_category_options() {
		$categories = get_categories( array(
			'hide_empty' => false,
		) );

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				printf(
					'<option value="%d">%s</option>',
					esc_attr( $category->term_id ),
					esc_html( $category->name )
				);
			}
		}
	}

	public function generate_products_ajax() {
		check_ajax_referer( 'wc_dpg_generate_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Permission denied', 'wc-dummy-product-generator' ) ) );
		}

		$product_count    = intval( $_POST['product_count'] ?? 5 );
		$product_type     = sanitize_text_field( $_POST['product_type'] ?? 'simple' );
		$product_category = intval( $_POST['product_category'] ?? 0 );
		$base_price       = floatval( $_POST['base_price'] ?? 29.99 );
		$add_images       = isset( $_POST['add_images'] ) ? 1 : 0;

		$product_count = min( max( $product_count, 1 ), 100 );

		$generator = new WC_DPG_Generator();
		$result    = $generator->generate_products(
			$product_count,
			$product_type,
			$product_category,
			$base_price,
			$add_images
		);

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}

	public function generate_posts_ajax() {
		check_ajax_referer( 'wc_dpg_generate_nonce', 'nonce' );

		if ( ! current_user_can( 'publish_posts' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Permission denied', 'wc-dummy-product-generator' ) ) );
		}

		$post_count    = intval( $_POST['post_count'] ?? 5 );
		$post_category = intval( $_POST['post_category'] ?? 0 );
		$add_images    = isset( $_POST['add_images'] ) ? 1 : 0;

		$post_count = min( max( $post_count, 1 ), 100 );

		$generator = new WC_DPG_Post_Generator();
		$result    = $generator->generate_posts(
			$post_count,
			$post_category,
			$add_images
		);

		if ( $result['success'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}
}
