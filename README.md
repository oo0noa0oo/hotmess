# Hotmess WordPress Theme

A custom WordPress theme built with WooCommerce integration and advanced checkout functionality.

## Features

- Custom WooCommerce product and checkout templates
- ACF (Advanced Custom Fields) integration with JSON field groups
- Custom fonts and styling
- Responsive design with custom CSS
- Modular JavaScript for enhanced interactivity
- Custom cart and checkout pages

## Installation

1. Clone this repository into your WordPress themes directory:
   ```bash
   git clone https://github.com/oo0noa0oo/hotmess.git wp-content/themes/hotmess
   ```

2. Activate the theme in WordPress admin panel under Appearance > Themes

3. Ensure all required plugins are installed:
   - WooCommerce
   - Advanced Custom Fields (ACF)

## File Structure

```
hotmess/
├── acf-json/                  # ACF field group JSON files
├── assets/
│   ├── css/                   # Stylesheets
│   ├── fonts/                 # Custom font files
│   └── js/                    # JavaScript files
├── includes/
│   └── acf.php               # ACF configuration
├── woocommerce/              # WooCommerce template overrides
│   ├── cart/
│   └── checkout/
├── functions.php             # Theme functions
├── header.php                # Header template
├── footer.php                # Footer template
├── style.css                 # Main stylesheet
└── README.md                 # This file
```

## Key Templates

- **header.php** - Site header with navigation
- **footer.php** - Site footer
- **front-page.php** - Homepage template
- **archive-product.php** - Product listing page
- **single-product.php** - Individual product page
- **page.php** - Default page template
- **index.php** - Fallback template

## WooCommerce Customizations

- Custom product image and thumbnail templates
- Enhanced checkout flow with minimal and full versions
- Custom thank you page

## Development

### CSS Files
- `style.css` - Main theme stylesheet
- `assets/css/main.css` - Additional styles
- `assets/css/woocommerce.css` - WooCommerce-specific styles

### JavaScript Files
- `assets/js/main.js` - Core functionality
- `assets/js/checkout.js` - Checkout page enhancements
- `assets/js/checkout-minimal.js` - Minimal checkout version
- `assets/js/woocommerce.js` - WooCommerce integrations

## Requirements

- WordPress 5.0+
- PHP 7.4+
- WooCommerce 4.0+
- Advanced Custom Fields (ACF) 5.0+

## License

This theme is proprietary and created for Hotmess.

## Support

For issues and support, please contact the development team.
