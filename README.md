# WC Dummy Product Generator

A simple WordPress plugin to generate dummy WooCommerce products for testing and development purposes.

## Features

- **Simple Products**: Generate basic WooCommerce products with customizable pricing
- **Variable Products**: Create variable products with multiple attributes (color, size, etc.)
- **Mixed Generation**: Generate a mix of simple and variable products
- **Customizable Options**:
  - Set the number of products to generate (1-100)
  - Choose product type (simple, variable, or mixed)
  - Assign to specific product categories
  - Set base price with automatic variance
  - Optional placeholder images
- **User-Friendly Interface**: Simple admin settings page in WordPress dashboard
- **AJAX Generation**: Non-blocking product generation with progress tracking

## Installation

1. Download and extract the plugin to `/wp-content/plugins/wc-dummy-product-generator/`
2. Ensure WooCommerce is installed and activated
3. Go to **Plugins** in the WordPress admin
4. Find "WC Dummy Product Generator" and click **Activate**

## Usage

1. Navigate to **Dummy Products** in the WordPress admin menu
2. Fill in the form fields:
   - **Number of Products**: How many products to create (1-100)
   - **Product Type**:
     - Simple Products - Standard products
     - Variable Products - Products with variations (color, size, etc.)
     - Mixed - 50/50 split of simple and variable
   - **Category**: Select a category or leave blank for random category
   - **Base Price**: The starting price (products will have ±30% variance)
   - **Add Random Product Images*: Check to add placeholder images
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

## WooCommerce Requirements

- WooCommerce plugin must be installed and activated
- Product categories should exist (or create one first)

## Notes

- Maximum 100 products per generation session
- Images are downloaded from `https://via.placeholder.com/`
- Product prices vary ±30% from the base price
- All products are published with stock management enabled
- Variable products include realistic variations with different colors and sizes

## Support

For issues or questions, check:
1. WooCommerce is activated
2. You have proper administrator permissions
3. The plugin directory has write permissions

## Changelog

### Version 1.0.0
- Initial release
- Support for simple products
- Support for variable products
- Settings page with customization options
- AJAX-based product generation

## License

GPL v2 or later

