# Sample Data Generator

A simple WordPress plugin to generate sample WordPress posts and WooCommerce products for testing and development purposes.

## Features

### WordPress Posts (Always Available)
- **Realistic Blog Posts**: Generate quality blog content
- **Custom Categories**: Assign posts to specific categories or random ones
- **Featured Images**: Optional placeholder images for posts
- **Bulk Generation**: Create up to 100 posts at once.

### WooCommerce Products (Requires WooCommerce Plugin)
- **Simple Products**: Generate basic WooCommerce products with customizable pricing
- **Variable Products**: Create variable products with multiple attributes (color, size, etc.)
- **Mixed Generation**: Generate a mix of simple and variable products
- **Customizable Options**:
  - Set the number of products to generate (1-100)
  - Choose product type (simple, variable, or mixed)
  - Assign to specific product categories
  - Set base price with automatic variance
  - Optional placeholder images

### General Features
- **User-Friendly Interface**: Simple admin settings page in WordPress dashboard
- **AJAX Generation**: Non-blocking generation with progress tracking
- **No WooCommerce Required**: Post generation works independently

## Installation

1. Download and extract the plugin to `/wp-content/plugins/sample-data-generator/`
2. Go to **Plugins** in the WordPress admin
3. Find "Sample Data Generator" and click **Activate**
4. (Optional) Install and activate WooCommerce if you want to generate products.

## Usage

1. Navigate to **Sample Data** in the WordPress admin menu
2. Choose between **Generate Posts** or **Generate Products** tabs (Products tab only shows if WooCommerce is active)

### Generating Posts
1. Select the **Generate Posts** tab
2. Fill in the form fields:
   - **Number of Posts**: How many posts to create (1-100)
   - **Category**: Select a category or leave blank for random category
   - **Add Featured Images**: Check to add placeholder images
3. Click **Generate Posts**
4. Monitor the progress bar
5. Once complete, view the links to your newly created posts

### Generating Products (Requires WooCommerce)
1. Select the **Generate Products** tab
2. Fill in the form fields:
   - **Number of Products**: How many products to create (1-100)
   - **Product Type**:
     - Simple Products - Standard products
     - Variable Products - Products with variations (color, size, etc.)
     - Mixed - 50/50 split of simple and variable
   - **Category**: Select a category or leave blank for random category
   - **Base Price**: The starting price (products will have ±30% variance)
   - **Add Random Product Images**: Check to add placeholder images
3. Click **Generate Products**
4. Monitor the progress bar
5. Once complete, view the links to your newly created products

## Product Details

### Simple Products
- Realistic product names
- Random descriptions
- Stock management enabled (5-100 units)
- Pricing based on your base price
- Optional images

### Variable Products
- Product name with "Variable" suffix
- 3 product variations per variable product
- Color and Size attributes
- Each variation has unique stock and pricing
- All variations are published and available

## Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **WooCommerce** (Optional): Only required if you want to generate products
  - Product categories should exist (or create one first)

## Notes

- Maximum 100 products per generation session
- Images are downloaded from `https://via.placeholder.com/`
- Product prices vary ±30% from the base price
- All products are published with stock management enabled
- Variable products include realistic variations with different colors and sizes

## Support

For issues or questions, check:
1. You have proper permissions (Editor or Administrator)
2. The plugin directory has write permissions
3. If generating products: Ensure WooCommerce is installed and activated

## Changelog

### Version 1.0.0
- Initial release
- Support for simple products
- Support for variable products
- Settings page with customization options
- AJAX-based product generation

## License

GPL v2 or later



