<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SMDG_Post_Generator {

	private $post_titles = array(
		'Getting Started with WordPress',
		'10 Tips for Better Blog Content',
		'How to Optimize Your Website',
		'The Future of Web Development',
		'Best Practices for SEO',
		'Building a Strong Online Presence',
		'Content Marketing Strategy Guide',
		'Understanding WordPress Themes',
		'Maximizing Your Blog Traffic',
		'Social Media for Business',
		'Email Marketing Best Practices',
		'Creating Engaging Content',
		'Website Security Tips',
		'Mobile-First Design Approach',
		'Analytics and Data Insights',
		'User Experience Optimization',
		'Landing Page Design Guide',
		'Video Content Strategy',
		'Blogging for Beginners',
		'Advanced WordPress Features',
	);

	private $post_content_templates = array(
		'Learn more about %s and how it can benefit your business. In this comprehensive guide, we explore the key concepts and best practices.',
		'Discover the latest trends in %s. Our experts share insights and actionable tips to help you stay ahead of the competition.',
		'This detailed article covers everything you need to know about %s. From basics to advanced techniques, we have it all.',
		'Are you interested in %s? Read this guide to understand the fundamentals and practical applications.',
		'Master %s with our step-by-step tutorial. We break down complex concepts into easy-to-understand sections.',
		'Explore the world of %s. Learn from industry leaders and implement proven strategies in your business.',
		'Complete guide to %s for beginners and professionals alike. Gain valuable knowledge and practical skills.',
		'Understand %s better with our in-depth analysis. We provide real-world examples and case studies.',
	);

	public function generate_posts( $count, $post_category, $add_images ) {
		$created_posts = array();
		$errors        = array();

		for ( $i = 0; $i < $count; $i++ ) {
			try {
				$post_id = $this->create_post( $post_category, $add_images );
				if ( $post_id ) {
					$created_posts[] = $post_id;
				}
			} catch ( Exception $e ) {
				$errors[] = $e->getMessage();
			}
		}

		if ( empty( $created_posts ) ) {
			return array(
				'success' => false,
				'message' => esc_html__( 'Failed to create posts.', 'sample-data-generator' ),
				'errors'  => $errors,
			);
		}

		return array(
			'success' => true,
			'message' => sprintf(
				esc_html__( 'Successfully created %d post(s)!', 'sample-data-generator' ),
				count( $created_posts )
			),
			'posts'   => $created_posts,
			'errors'  => $errors,
		);
	}

	private function create_post( $post_category, $add_images ) {
		$post_title   = $this->get_random_title();
		$post_content = $this->get_random_content( $post_title );

		$post_args = array(
			'post_type'    => 'post',
			'post_title'   => $post_title,
			'post_content' => $post_content,
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
		);

		if ( $post_category > 0 ) {
			$post_args['post_category'] = array( $post_category );
		} else {
			$cat_ids = $this->get_random_categories();
			if ( ! empty( $cat_ids ) ) {
				$post_args['post_category'] = $cat_ids;
			}
		}

		$post_id = wp_insert_post( $post_args );

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return false;
		}

		if ( $add_images ) {
			$image_id = $this->get_placeholder_image();
			if ( $image_id ) {
				set_post_thumbnail( $post_id, $image_id );
			}
		}

		return $post_id;
	}

	private function get_random_title() {
		return $this->post_titles[ array_rand( $this->post_titles ) ];
	}

	private function get_random_content( $title ) {
		$template = $this->post_content_templates[ array_rand( $this->post_content_templates ) ];
		$content  = sprintf( $template, strtolower( $title ) );

		$content .= "\n\n<h2>Key Points:</h2>\n";
		$content .= "<ul>\n";
		for ( $i = 0; $i < 5; $i++ ) {
			$content .= "<li>Important point " . ( $i + 1 ) . " about this topic</li>\n";
		}
		$content .= "</ul>\n\n";
		$content .= "<p>This guide provides comprehensive information on the subject. Apply these insights to your work and see the results.</p>";

		return $content;
	}

	private function get_random_categories() {
		$categories = get_categories( array(
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
		$placeholder_url = 'https://picsum.photos/400/300?random=' . $random_id;

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$temp_file = download_url( $placeholder_url, 300 );

		if ( is_wp_error( $temp_file ) ) {
			return false;
		}

		$filename     = 'post-image-' . rand( 1000, 9999 ) . '.jpg';
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
