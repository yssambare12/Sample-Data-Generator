<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_DPG_Generator {

	private $product_names = array(
		'Wireless Headphones',
		'USB-C Cable',
		'Phone Case',
		'Screen Protector',
		'Portable Charger',
		'USB Hub',
		'Keyboard',
		'Mouse',
		'Monitor Stand',
		'Desk Lamp',
		'Phone Mount',
		'Cable Organizer',
		'Webcam Cover',
		'Laptop Bag',
		'Cooling Pad',
		'Laptop Stand',
		'Mechanical Keyboard',
		'Wireless Mouse',
		'Gaming Headset',
		'Desk Organizer',
	);

	private $description_templates = array(
		'High-quality %s with premium materials and excellent durability.',
		'Professional-grade %s designed for daily use and maximum comfort.',
		'This %s offers exceptional value and outstanding performance.',
		'Premium %s with ergonomic design and modern aesthetics.',
		'Reliable %s built to last with superior craftsmanship.',
		'Innovative %s combining style and functionality perfectly.',
		'Durable %s with advanced features for tech enthusiasts.',
		'Practical %s ideal for both work and personal use.',
	);

	private $attributes = array(
		'color' => array( 'Black', 'White', 'Blue', 'Red', 'Silver', 'Gold' ),
		'size'  => array( 'Small', 'Medium', 'Large', 'X-Large' ),
	);

	public function generate_products( $count, $type, $category_id, $base_price, $add_images ) {
		$created_products = array();
		$errors           = array();

		for ( $i = 0; $i < $count; $i++ ) {
			try {
				$product_type = $type;
				if ( 'mixed' === $type ) {
					$product_type = ( 0 === $i % 2 ) ? 'simple' : 'variable';
				}

				if ( 'variable' === $product_type ) {
					$product_id = $this->create_variable_product( $category_id, $base_price, $add_images );
				} else {
					$product_id = $this->create_simple_product( $category_id, $base_price, $add_images );
				}

				if ( $product_id ) {
					$created_products[] = $product_id;
				}
			} catch ( Exception $e ) {
				$errors[] = $e->getMessage();
			}
		}

		if ( empty( $created_products ) ) {
			return array(
				'success' => false,
				'message' => esc_html__( 'Failed to create products.', 'wc-dummy-product-generator' ),
				'errors'  => $errors,
			);
		}

		return array(
			'success'  => true,
			'message'  => sprintf(
				esc_html__( 'Successfully created %d product(s)!', 'wc-dummy-product-generator' ),
				count( $created_products )
			),
			'products' => $created_products,
			'errors'   => $errors,
		);
	}

	private function create_simple_product( $category_id, $base_price, $add_images ) {
		$product_name = $this->get_random_product_name();
		$price        = $this->get_random_price( $base_price );

		$product = new WC_Product_Simple();
		$product->set_name( $product_name );
		$product->set_description( $this->get_random_description( $product_name ) );
		$product->set_price( $price );
		$product->set_regular_price( $price );
		$product->set_stock_quantity( rand( 5, 100 ) );
		$product->set_manage_stock( true );
		$product->set_status( 'publish' );

		if ( $category_id > 0 ) {
			$product->set_category_ids( array( $category_id ) );
		} else {
			$cat_ids = $this->get_random_categories();
			if ( ! empty( $cat_ids ) ) {
				$product->set_category_ids( $cat_ids );
			}
		}

		if ( $add_images ) {
			$image_id = $this->get_placeholder_image();
			if ( $image_id ) {
				$product->set_image_id( $image_id );
			}
		}

		$product_id = $product->save();

		return $product_id ? $product_id : false;
	}

	private function create_variable_product( $category_id, $base_price, $add_images ) {
		$product_name = $this->get_random_product_name();
		$price        = $this->get_random_price( $base_price );

		$product = new WC_Product_Variable();
		$product->set_name( $product_name . ' - Variable' );
		$product->set_description( $this->get_random_description( $product_name ) );
		$product->set_status( 'publish' );

		if ( $category_id > 0 ) {
			$product->set_category_ids( array( $category_id ) );
		} else {
			$cat_ids = $this->get_random_categories();
			if ( ! empty( $cat_ids ) ) {
				$product->set_category_ids( $cat_ids );
			}
		}

		if ( $add_images ) {
			$image_id = $this->get_placeholder_image();
			if ( $image_id ) {
				$product->set_image_id( $image_id );
			}
		}

		$product_id = $product->save();

		if ( ! $product_id ) {
			return false;
		}

		$attributes = array();
		foreach ( $this->attributes as $attr_name => $attr_values ) {
			$attribute = new WC_Product_Attribute();
			$attribute->set_name( $attr_name );
			$attribute->set_options( $attr_values );
			$attribute->set_visible( true );
			$attribute->set_variation( true );
			$attributes[] = $attribute;
		}
		$product->set_attributes( $attributes );
		$product->save();

		$this->create_product_variations( $product_id, $price );

		return $product_id;
	}

	private function create_product_variations( $product_id, $base_price ) {
		$colors = $this->attributes['color'];
		$sizes  = $this->attributes['size'];

		$variation_count = min( count( $colors ), 3 );

		for ( $i = 0; $i < $variation_count; $i++ ) {
			$variation = new WC_Product_Variation();
			$variation->set_parent_id( $product_id );
			$variation->set_attributes(
				array(
					'color' => $colors[ $i ],
					'size'  => $sizes[ $i % count( $sizes ) ],
				)
			);

			$variation_price = $this->get_random_price( $base_price );
			$variation->set_price( $variation_price );
			$variation->set_regular_price( $variation_price );
			$variation->set_stock_quantity( rand( 5, 50 ) );
			$variation->set_manage_stock( true );
			$variation->set_status( 'publish' );

			$variation->save();
		}
	}

	private function get_random_product_name() {
		return $this->product_names[ array_rand( $this->product_names ) ];
	}

	private function get_random_description( $product_name ) {
		$template = $this->description_templates[ array_rand( $this->description_templates ) ];
		return sprintf( $template, $product_name );
	}

	private function get_random_price( $base_price ) {
		$variance     = $base_price * 0.3;
		$random_price = $base_price + ( rand( -100, 100 ) / 100 ) * $variance;
		return round( max( $random_price, 1 ), 2 );
	}

	private function get_random_categories() {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'number'     => 1,
		) );

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			return array( $categories[0]->term_id );
		}

		return array();
	}

	private function get_placeholder_image() {
		$random_id     = rand( 1000, 9999 );
		$placeholder_url = 'https://picsum.photos/300/300?random=' . $random_id;

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$temp_file = download_url( $placeholder_url, 300 );

		if ( is_wp_error( $temp_file ) ) {
			return false;
		}

		$filename     = 'product-image-' . rand( 1000, 9999 ) . '.jpg';
		$uploads_dir = wp_upload_dir();
		$destination = $uploads_dir['path'] . '/' . $filename;

		if ( ! file_exists( $uploads_dir['path'] ) ) {
			wp_mkdir_p( $uploads_dir['path'] );
		}

		if ( ! rename( $temp_file, $destination ) ) {
			@unlink( $temp_file );
			return false;
		}

		$wp_filetype = wp_check_filetype( $destination, null );

		$attachment_args = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment_args, $destination );

		if ( is_wp_error( $attachment_id ) ) {
			@unlink( $destination );
			return false;
		}

		$attach_data = wp_generate_attachment_metadata( $attachment_id, $destination );

		if ( ! is_wp_error( $attach_data ) ) {
			wp_update_attachment_metadata( $attachment_id, $attach_data );
		}

		return $attachment_id;
	}
}
