# WC Dummy Product Generator - Setup Guide

## Installation Steps

### 1. Prerequisites
- WordPress installed and running
- WooCommerce plugin installed and activated
- PHP 7.4 or higher
- Write permissions on `/wp-content/plugins/` directory.

### 2. Activate the Plugin
1. Log in to WordPress admin panel
2. Go to **Plugins** in the left sidebar
3. Look for "WC Dummy Product Generator"
4. Click **Activate**

### 3. Navigate to Plugin Settings
1. In the left sidebar, you'll see a new menu item: **Dummy Products** (with a shopping cart icon)
2. Click on it to open the settings page

## Using the Plugin

### Generate Simple Products
1. Set **Number of Products**: e.g., 10
2. Set **Product Type**: "Simple Products"
3. Set **Base Price**: e.g., 29.99
4. Check **Add Random Product Images**
5. Click **Generate Products**

### Generate Variable Products
1. Set **Number of Products**: e.g., 5
2. Set **Product Type**: "Variable Products"
3. Each variable product will have:
   - 3 variations (different colors and sizes)
   - Independent pricing for each variation
   - Independent stock for each variation
4. Click **Generate Products**

### Generate Mixed Products
1. Set **Product Type**: "Mixed (50/50)"
2. This will create a 50/50 split of simple and variable products

## Features Explained

### Product Images
- When you check "Add Random Product Images", each product will have a unique placeholder image
- Images are downloaded from external service (https://picsum.photos/)
- Images are automatically added to WordPress media library
- Images are attached to the product

### Pricing
- Base price is the starting point
- Each product gets a random price within ±30% of the base price
- For example: base price $29.99 → products range from ~$20.99 to ~$38.99

### Categories
- Leave blank to assign to a random existing category
- Select a specific category to assign all products to that category
- Category must exist in your WooCommerce setup

### Stock Management
- All products have stock management enabled
- Simple products: 5-100 units
- Variable products: 5-50 units per variation

## Product Information Generated

Each product includes:
- **Name**: Random from a predefined list (Wireless Headphones, USB-C Cable, etc.)
- **Description**: Realistic description generated from templates
- **Price**: Randomized around base price
- **Stock**: Enabled with random quantity
- **Status**: Published (live on store)
- **Image**: Random placeholder image (if enabled)

## Troubleshooting

### Products not generating
- Ensure WooCommerce is activated
- Check that you have "manage_woocommerce" capability
- Check browser console for JavaScript errors

### Images not attaching
- Ensure `/wp-content/uploads/` directory exists and is writable
- Check PHP has permission to download from external URLs
- Check firewall isn't blocking access to image service

### Variations not showing
- Variable products are created with color and size attributes
- 3 variations are created per variable product
- All variations are published by default

## Notes

- Product generation is fast (via AJAX)
- Progress bar shows generation status
- Maximum 100 products per generation session
- All created products will appear in your WooCommerce shop

## Support

If you encounter any issues:
1. Check that WooCommerce is activated
2. Ensure user has admin permissions
3. Check WordPress error logs
4. Verify file/directory permissions

## Uninstall

Simply deactivate and delete the plugin. Generated products will remain in your WordPress database.

