(function ($) {
	'use strict';

	$(document).ready(function () {
		initTabs();
		initProductsForm();
		initPostsForm();
	});

	function initTabs() {
		$('.tab-button').on('click', function () {
			const tabName = $(this).data('tab');

			$('.tab-button').removeClass('active');
			$(this).addClass('active');

			$('.tab-content').removeClass('active').hide();
			$('#' + tabName + '-tab').addClass('active').show();
		});
	}

	function initProductsForm() {
		const form = $('#wc-dpg-products-form');
		const submitBtn = $('#generate-products-btn');
		const progressDiv = $('#wc-dpg-products-progress');
		const progressFill = progressDiv.find('.progress-fill');
		const progressText = progressDiv.find('.progress-text');
		const resultDiv = $('#wc-dpg-products-result');

		form.on('submit', function (e) {
			e.preventDefault();

			if (!form[0].checkValidity()) {
				alert('Please fill in all required fields');
				return;
			}

			const productCount = parseInt($('#product-count').val());
			const productType = $('#product-type').val();
			const productCategory = $('#product-category').val();
			const basePrice = parseFloat($('#base-price').val());
			const addImages = $('#add-product-images').is(':checked') ? 1 : 0;

			submitBtn.prop('disabled', true);
			progressDiv.show();
			resultDiv.hide();
			progressFill.css('width', '0%');
			progressText.text('Generating products...');

			$.ajax({
				url: wcDpgSettings.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'wc_dpg_generate_products',
					nonce: wcDpgSettings.nonce,
					product_count: productCount,
					product_type: productType,
					product_category: productCategory,
					base_price: basePrice,
					add_images: addImages,
				},
				success: function (response) {
					progressFill.css('width', '100%');
					progressText.text('Complete!');

					if (response.success) {
						showResult(resultDiv, response.data, true);
					} else {
						showResult(resultDiv, response.data, false);
					}

					submitBtn.prop('disabled', false);
					setTimeout(function () {
						progressDiv.fadeOut();
					}, 500);
				},
				error: function () {
					resultDiv
						.removeClass('success error')
						.addClass('error')
						.html('<p>Error occurred while generating products. Please try again.</p>')
						.show();

					progressDiv.hide();
					submitBtn.prop('disabled', false);
				},
			});
		});
	}

	function initPostsForm() {
		const form = $('#wc-dpg-posts-form');
		const submitBtn = $('#generate-posts-btn');
		const progressDiv = $('#wc-dpg-posts-progress');
		const progressFill = progressDiv.find('.progress-fill');
		const progressText = progressDiv.find('.progress-text');
		const resultDiv = $('#wc-dpg-posts-result');

		form.on('submit', function (e) {
			e.preventDefault();

			if (!form[0].checkValidity()) {
				alert('Please fill in all required fields');
				return;
			}

			const postCount = parseInt($('#post-count').val());
			const postCategory = $('#post-category').val();
			const addImages = $('#add-post-images').is(':checked') ? 1 : 0;

			submitBtn.prop('disabled', true);
			progressDiv.show();
			resultDiv.hide();
			progressFill.css('width', '0%');
			progressText.text('Generating posts...');

			$.ajax({
				url: wcDpgSettings.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'wc_dpg_generate_posts',
					nonce: wcDpgSettings.nonce,
					post_count: postCount,
					post_category: postCategory,
					add_images: addImages,
				},
				success: function (response) {
					progressFill.css('width', '100%');
					progressText.text('Complete!');

					if (response.success) {
						showResult(resultDiv, response.data, true);
					} else {
						showResult(resultDiv, response.data, false);
					}

					submitBtn.prop('disabled', false);
					setTimeout(function () {
						progressDiv.fadeOut();
					}, 500);
				},
				error: function () {
					resultDiv
						.removeClass('success error')
						.addClass('error')
						.html('<p>Error occurred while generating posts. Please try again.</p>')
						.show();

					progressDiv.hide();
					submitBtn.prop('disabled', false);
				},
			});
		});
	}

	function showResult(resultDiv, data, isSuccess) {
		let html = '<p><strong>' + data.message + '</strong></p>';

		if (isSuccess && data.products) {
			html += '<p><strong>Created Products:</strong></p><ul>';
			data.products.forEach(function (productId) {
				const editLink = window.location.origin + '/wp-admin/post.php?post=' + productId + '&action=edit';
				html += '<li><a href="' + editLink + '" target="_blank">Product ID: ' + productId + '</a></li>';
			});
			html += '</ul>';
		}

		if (isSuccess && data.posts) {
			html += '<p><strong>Created Posts:</strong></p><ul>';
			data.posts.forEach(function (postId) {
				const editLink = window.location.origin + '/wp-admin/post.php?post=' + postId + '&action=edit';
				html += '<li><a href="' + editLink + '" target="_blank">Post ID: ' + postId + '</a></li>';
			});
			html += '</ul>';
		}

		if (data.errors && data.errors.length > 0) {
			html += '<p><strong>Warnings:</strong></p><ul>';
			data.errors.forEach(function (error) {
				html += '<li>' + error + '</li>';
			});
			html += '</ul>';
		}

		resultDiv
			.removeClass('success error')
			.addClass(isSuccess ? 'success' : 'error')
			.html(html)
			.show();
	}
})(jQuery);
