<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMDG_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_smdg_generate_products', array( $this, 'generate_products_ajax' ) );
		add_action( 'wp_ajax_smdg_generate_posts', array( $this, 'generate_posts_ajax' ) );
	}

	public function add_admin_menu() {
		add_menu_page(
			esc_html__( 'Sample Data', 'sample-data-generator' ),
			esc_html__( 'Sample Data', 'sample-data-generator' ),
			'publish_posts',
			'smdg-content',
			array( $this, 'render_settings_page' ),
			'dashicons-admin-generic',
			56
		);
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' ) || function_exists( 'WC' );
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( 'toplevel_page_smdg-content' !== $hook ) {
			return;
		}

		// Determine if minified files exist
		$css_file = file_exists( SMDG_PLUGIN_DIR . 'admin/css/admin.min.css' ) ? 'admin.min.css' : 'admin.css';
		$js_file  = file_exists( SMDG_PLUGIN_DIR . 'admin/js/admin.min.js' ) ? 'admin.min.js' : 'admin.js';

		wp_enqueue_style(
			'smdg-admin-style',
			SMDG_PLUGIN_URL . 'admin/css/' . $css_file,
			array(),
			SMDG_VERSION
		);

		wp_enqueue_script(
			'smdg-admin-script',
			SMDG_PLUGIN_URL . 'admin/js/' . $js_file,
			array( 'jquery' ),
			SMDG_VERSION,
			true
		);

		wp_localize_script(
			'smdg-admin-script',
			'smdgSettings',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'smdg_generate_nonce' ),
			)
		);
	}

	public function render_settings_page() {
		$is_wc_active = $this->is_woocommerce_active();
		?>
		<div class="wrap smdg-wrapper">
			<h1><?php esc_html_e( 'Sample Data Generator', 'sample-data-generator' ); ?></h1>

			<div class="smdg-tabs">
				<button class="tab-button active" data-tab="products">
					<?php esc_html_e( 'Generate Products', 'sample-data-generator' ); ?>
				</button>
				<button class="tab-button" data-tab="posts">
					<?php esc_html_e( 'Generate Posts', 'sample-data-generator' ); ?>
				</button>
			</div>

			<div class="smdg-content">
				<div id="products-tab" class="tab-content active">
					<div class="smdg-container">
						<div class="smdg-form-card">
							<h2><?php esc_html_e( 'WooCommerce Products', 'sample-data-generator' ); ?></h2>

							<?php if ( ! $is_wc_active ) : ?>
								<div class="smdg-wc-notice">
									<div class="smdg-wc-notice-icon">
										<span class="dashicons dashicons-info"></span>
									</div>
									<div class="smdg-wc-notice-content">
										<h3><?php esc_html_e( 'WooCommerce Required', 'sample-data-generator' ); ?></h3>
										<p><?php esc_html_e( 'To generate WooCommerce products, you need to install and activate the WooCommerce plugin.', 'sample-data-generator' ); ?></p>
										<div class="smdg-wc-notice-actions">
											<?php
											$plugin_slug = 'woocommerce';
											$plugin_file = 'woocommerce/woocommerce.php';

											// Check if WooCommerce is installed but not active
											if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
												$activate_url = wp_nonce_url(
													admin_url( 'plugins.php?action=activate&plugin=' . $plugin_file ),
													'activate-plugin_' . $plugin_file
												);
												?>
												<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary button-large">
													<?php esc_html_e( 'Activate WooCommerce', 'sample-data-generator' ); ?>
												</a>
												<?php
											} else {
												$install_url = wp_nonce_url(
													admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ),
													'install-plugin_' . $plugin_slug
												);
												?>
												<a href="<?php echo esc_url( $install_url ); ?>" class="button button-primary button-large">
													<?php esc_html_e( 'Install WooCommerce', 'sample-data-generator' ); ?>
												</a>
												<?php
											}
											?>
											<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" class="button button-secondary button-large">
												<?php esc_html_e( 'Learn More', 'sample-data-generator' ); ?>
											</a>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<form id="smdg-products-form" class="smdg-form" <?php echo ! $is_wc_active ? 'style="opacity: 0.5; pointer-events: none;"' : ''; ?>>
								<div class="form-group">
									<label for="product-count">
										<?php esc_html_e( 'Number of Products', 'sample-data-generator' ); ?>
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
									<small><?php esc_html_e( 'Create between 1 and 100 products', 'sample-data-generator' ); ?></small>
								</div>

								<div class="form-group">
									<label for="product-type">
										<?php esc_html_e( 'Product Type', 'sample-data-generator' ); ?>
									</label>
									<select id="product-type" name="product_type" required>
										<option value="simple"><?php esc_html_e( 'Simple Products', 'sample-data-generator' ); ?></option>
										<option value="variable"><?php esc_html_e( 'Variable Products', 'sample-data-generator' ); ?></option>
										<option value="mixed"><?php esc_html_e( 'Mixed (50/50)', 'sample-data-generator' ); ?></option>
									</select>
								</div>

								<div class="form-group">
									<label for="product-category">
										<?php esc_html_e( 'Category', 'sample-data-generator' ); ?>
									</label>
									<select id="product-category" name="product_category">
										<option value=""><?php esc_html_e( 'Random Category', 'sample-data-generator' ); ?></option>
										<?php $this->render_product_category_options(); ?>
									</select>
								</div>

								<div class="form-group">
									<label for="base-price">
										<?php esc_html_e( 'Base Price (USD)', 'sample-data-generator' ); ?>
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
									<small><?php esc_html_e( 'Product prices vary around this base price', 'sample-data-generator' ); ?></small>
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
										<?php esc_html_e( 'Add Random Product Images', 'sample-data-generator' ); ?>
									</label>
								</div>

								<div class="form-group">
									<button type="submit" class="button button-primary button-large" id="generate-products-btn">
										<?php esc_html_e( 'Generate Products', 'sample-data-generator' ); ?>
									</button>
								</div>
							</form>

							<div id="smdg-products-progress" class="smdg-progress" style="display: none;">
								<div class="progress-bar">
									<div class="progress-fill"></div>
								</div>
								<p class="progress-text"></p>
							</div>

							<div id="smdg-products-result" class="smdg-result" style="display: none;"></div>
						</div>

						<div class="smdg-info-card">
							<h3><?php esc_html_e( 'Product Info', 'sample-data-generator' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Simple or variable products', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Customizable pricing', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Random product images', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Realistic product data', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Perfect for testing', 'sample-data-generator' ); ?></li>
							</ul>
						</div>
					</div>
				</div>

				<div id="posts-tab" class="tab-content" style="display: none;">
					<div class="smdg-container">
						<div class="smdg-form-card">
							<h2><?php esc_html_e( 'WordPress Posts', 'sample-data-generator' ); ?></h2>

							<form id="smdg-posts-form" class="smdg-form">
								<div class="form-group">
									<label for="post-count">
										<?php esc_html_e( 'Number of Posts', 'sample-data-generator' ); ?>
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
									<small><?php esc_html_e( 'Create between 1 and 100 posts', 'sample-data-generator' ); ?></small>
								</div>

								<div class="form-group">
									<label for="post-category">
										<?php esc_html_e( 'Category', 'sample-data-generator' ); ?>
									</label>
									<select id="post-category" name="post_category">
										<option value=""><?php esc_html_e( 'Random Category', 'sample-data-generator' ); ?></option>
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
										<?php esc_html_e( 'Add Featured Images', 'sample-data-generator' ); ?>
									</label>
								</div>

								<div class="form-group">
									<button type="submit" class="button button-primary button-large" id="generate-posts-btn">
										<?php esc_html_e( 'Generate Posts', 'sample-data-generator' ); ?>
									</button>
								</div>
							</form>

							<div id="smdg-posts-progress" class="smdg-progress" style="display: none;">
								<div class="progress-bar">
									<div class="progress-fill"></div>
								</div>
								<p class="progress-text"></p>
							</div>

							<div id="smdg-posts-result" class="smdg-result" style="display: none;"></div>
						</div>

						<div class="smdg-info-card">
							<h3><?php esc_html_e( 'Post Info', 'sample-data-generator' ); ?></h3>
							<ul>
								<li><?php esc_html_e( 'Realistic blog posts', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Random categories', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Featured images', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Quality content', 'sample-data-generator' ); ?></li>
								<li><?php esc_html_e( 'Ready to publish', 'sample-data-generator' ); ?></li>
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
		check_ajax_referer( 'smdg_generate_nonce', 'nonce' );

		if ( ! current_user_can( 'publish_posts' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Permission denied', 'sample-data-generator' ) ) );
		}

		if ( ! $this->is_woocommerce_active() ) {
			wp_send_json_error( array( 'message' => esc_html__( 'WooCommerce is required to generate products.', 'sample-data-generator' ) ) );
		}

		$product_count    = intval( $_POST['product_count'] ?? 5 );
		$product_type     = sanitize_text_field( $_POST['product_type'] ?? 'simple' );
		$product_category = intval( $_POST['product_category'] ?? 0 );
		$base_price       = floatval( $_POST['base_price'] ?? 29.99 );
		$add_images       = isset( $_POST['add_images'] ) ? 1 : 0;

		$product_count = min( max( $product_count, 1 ), 100 );

		$generator = new SMDG_Product_Generator();
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
		check_ajax_referer( 'smdg_generate_nonce', 'nonce' );

		if ( ! current_user_can( 'publish_posts' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Permission denied', 'sample-data-generator' ) ) );
		}

		$post_count    = intval( $_POST['post_count'] ?? 5 );
		$post_category = intval( $_POST['post_category'] ?? 0 );
		$add_images    = isset( $_POST['add_images'] ) ? 1 : 0;

		$post_count = min( max( $post_count, 1 ), 100 );

		$generator = new SMDG_Post_Generator();
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
