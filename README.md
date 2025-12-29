# Hot Mess - WordPress WooCommerce Theme

A custom WordPress theme for Hot Sauce Co, featuring a spicy product catalog with heat level ratings, Scoville measurements, and streamlined checkout experience.

## Version

**Current Version:** 2.0.0
**Last Updated:** December 29, 2024
**WordPress Tested:** 6.7+
**WooCommerce Tested:** 9.8.0+
**PHP Required:** 8.1+

---

## Theme Overview

Hot Mess is a custom-built WordPress theme specifically designed for hot sauce e-commerce. It features:

- Custom product grid with heat intensity indicators
- Scoville Heat Unit (SHU) rating system
- Variable product support with heat level variations
- Streamlined single-page checkout
- Custom cart and thank you page experiences
- ACF (Advanced Custom Fields) integration
- Optimized for performance and conversion

---

## Features

### 🌶️ Product Display
- **Heat Level Badges:** Visual indicators (Mild, Medium, Hot, Extra Hot)
- **Scoville Ratings:** Display precise heat measurements in SHU
- **Heat Intensity Dots:** Visual representation of spice level (1-5 dots)
- **Variable Products:** Support for multiple heat variations per product
- **Custom Product Grid:** Responsive 3-column layout with product cards
- **Product Images:** Custom gallery with thumbnail support

### 🛒 Shopping Experience
- **Custom Cart Page:** Enhanced cart with heat level display
- **Streamlined Checkout:** Single-page checkout optimized for conversion
- **Order Thank You Page:** Custom post-purchase experience with next steps
- **AJAX Add to Cart:** Seamless product additions without page reload
- **Cart Fragments:** Real-time cart updates

### 🎨 Design & UI
- **Custom Typography:** Big Noodle Titling + KG Bless Your Heart fonts
- **Responsive Design:** Mobile-first approach
- **Custom Color Scheme:** Red/black theme matching brand identity
- **Heat-Based Color Coding:** Visual differentiation by spice level
- **Smooth Animations:** Fade-ins, hover effects, and transitions

### ⚡ Performance
- **Optimized Assets:** Minified CSS/JS
- **Selective Loading:** WooCommerce scripts only where needed
- **Minimal Dependencies:** Lightweight codebase
- **Fast Checkout:** Removed unnecessary WooCommerce fields

---

## File Structure

```
hot_mess_claudeiaV2/
├── assets/
│   ├── css/
│   │   ├── main.css              # Primary stylesheet
│   │   └── woocommerce.css       # WooCommerce-specific styles
│   ├── js/
│   │   ├── main.js               # Core JavaScript functionality
│   │   └── woocommerce.js        # WooCommerce AJAX handlers
│   └── fonts/                    # Custom font files
├── includes/
│   └── acf.php                   # ACF field configurations
├── woocommerce/
│   ├── cart/
│   │   └── cart.php              # Custom cart template
│   ├── checkout/
│   │   └── thankyou.php          # Custom thank you page
│   └── single-product/
│       ├── product-image.php     # Product gallery (v9.7.0)
│       └── product-thumbnails.php # Thumbnail gallery (v9.8.0)
├── acf-json/                     # ACF field group JSON files
├── functions.php                 # Theme functions and hooks
├── header.php                    # Header template
├── footer.php                    # Footer template
├── front-page.php                # Homepage template
├── page.php                      # Default page template
├── index.php                     # Blog/archive template
├── single-product.php            # Single product template
├── archive-product.php           # Shop/products archive
├── style.css                     # Theme metadata stylesheet
└── README.md                     # This file
```

---

## Dependencies

### Required Plugins
1. **WooCommerce** (9.8.0+) - E-commerce functionality
2. **Advanced Custom Fields Pro** (Latest) - Custom fields for products
   - Heat Level field (select)
   - Scoville Rating field (number)
   - Products section fields (homepage)

### Optional Plugins
- **WP Migrate DB** - Database migration (development)
- **WooCommerce PayPal Payments** - Payment gateway

---

## Installation

1. **Upload Theme:**
   ```bash
   cd wp-content/themes/
   git clone [repository-url] hot_mess_claudeiaV2
   ```

2. **Activate Theme:**
   - Go to WordPress Admin > Appearance > Themes
   - Activate "Hot Mess"

3. **Install Required Plugins:**
   - Install and activate WooCommerce
   - Install and activate Advanced Custom Fields Pro

4. **Import ACF Fields:**
   - ACF field groups are auto-imported from `acf-json/` directory
   - Verify fields exist in ACF admin

5. **Configure WooCommerce:**
   - Complete WooCommerce setup wizard
   - Set up payment methods
   - Configure shipping options

6. **Add Products:**
   - Create products with heat_level and scoville_rating custom fields
   - Add product images
   - Set up variable products for multiple heat levels

---

## Custom Fields (ACF)

### Product Fields
- **heat_level** (Select)
  - Options: mild, medium, hot, extra-hot
  - Used for visual heat badges

- **scoville_rating** (Number)
  - Range: 0 - 2,200,000+ SHU
  - Displays precise heat measurement

### Homepage Fields
- **products_badge** (Text) - Section badge text
- **products_title** (Text) - Section heading
- **products_description** (Textarea) - Section description
- **products_limit** (Number) - Max products to display

---

## Helper Functions

Located in `functions.php`:

- `get_heat_intensity_from_scoville($scoville)` - Converts SHU to intensity level (1-5)
- `get_heat_intensity_level($heat_level)` - Converts heat level to intensity (1-5)
- `display_heat_intensity($intensity)` - Outputs visual heat dots HTML

---

## Recent Changes (v2.0.0)

### December 29, 2024

#### ✅ Template Updates
- Updated `product-image.php` from v3.6.0 → v9.7.0
- Updated `product-thumbnails.php` from v3.6.0 → v9.8.0
- Added backups: `BACKUP_v3.6.0_*.php` files

#### 🗑️ Cleanup
Removed unused/backup files:
- `bak_page-checkout.php` (unused backup)
- `BAK_checkout-emergency.php` (experimental code)
- `style-backup.css` (old stylesheet backup)
- `woocommerce/checkout/bak_page-checkout.php` (duplicate backup)
- `assets/js/checkout.js` (never enqueued)
- `assets/js/checkout-minimal.js` (experimental, unused)

#### 📝 Documentation
- Added `TEMPLATE_UPDATE_CHANGELOG.md` - Detailed update log
- Added `UNUSED_CSS_COMMENTED_OUT.md` - CSS cleanup notes
- Created comprehensive README.md

#### 🔧 Technical Improvements
- Better variable product placeholder handling
- Improved product instance validation
- Enhanced security with proper escaping
- Added ProductType enum support

---

## Customization Guide

### Changing Heat Levels
Edit the heat level options in `functions.php` and update ACF field choices.

### Modifying Product Grid
Edit `archive-product.php` - adjust grid columns, card layout, or displayed information.

### Customizing Checkout
Edit `page.php` (contains checkout customizations) or WooCommerce templates.

### Adding Custom Styles
- General styles: `assets/css/main.css`
- WooCommerce-specific: `assets/css/woocommerce.css`

### JavaScript Customization
- Core functionality: `assets/js/main.js`
- WooCommerce AJAX: `assets/js/woocommerce.js`

---

## WooCommerce Template Overrides

This theme overrides the following WooCommerce templates:

| Template | Version | Status | Notes |
|----------|---------|--------|-------|
| `cart.php` | Custom | ⚠️ Customized | Custom heat badge display |
| `thankyou.php` | Custom | ⚠️ Customized | Custom design with embedded CSS |
| `product-image.php` | 9.7.0 | ✅ Up to date | Standard with enhancements |
| `product-thumbnails.php` | 9.8.0 | ✅ Up to date | Standard with validation |
| `archive-product.php` | Custom | ⚠️ Custom | Fully custom shop page |
| `single-product.php` | Custom | ⚠️ Custom | Custom heat badge integration |

**Note:** Templates marked as "Customized" will show version warnings in WooCommerce status - this is expected and safe to ignore.

---

## Browser Support

- Chrome/Edge (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Mobile Safari (iOS 14+)
- Chrome Mobile (Android 10+)

---

## Performance Notes

### Optimizations
- CSS/JS enqueued with version numbers for cache busting
- WooCommerce scripts loaded conditionally
- Selective WooCommerce feature removal (zoom, lightbox)
- Optimized cart fragments for AJAX updates

### Known Issues
- Embedded CSS in some templates (thankyou.php, page.php) - planned for extraction
- 550+ lines of commented CSS in main files - planned for cleanup

---

## Development

### Local Development Setup
```bash
# Navigate to theme directory
cd wp-content/themes/hot_mess_claudeiaV2

# Make changes to files
# Test thoroughly on local WordPress installation
```

### Code Standards
- Follow WordPress Coding Standards
- Use proper escaping for all output
- Comment complex logic
- Test all WooCommerce functionality after changes

### Testing Checklist
- [ ] Product pages display correctly
- [ ] Cart functions properly
- [ ] Checkout process works end-to-end
- [ ] Variable products show all variations
- [ ] Heat badges display correctly
- [ ] Mobile responsive on all pages
- [ ] AJAX add to cart works
- [ ] Payment processing completes

---

## Support & Maintenance

### Backup Files
Template backups are stored with `BACKUP_` prefix:
- `woocommerce/single-product/BACKUP_v3.6.0_product-image.php`
- `woocommerce/single-product/BACKUP_v3.6.0_product-thumbnails.php`

### Reverting Template Updates
```bash
# Revert to older template version
cp woocommerce/single-product/BACKUP_v3.6.0_product-image.php woocommerce/single-product/product-image.php
```

### Future Updates
When updating WooCommerce:
1. Check template version compatibility in WooCommerce > Status
2. Review customized templates for needed updates
3. Test thoroughly on staging before production
4. Keep backups of working templates

---

## Changelog

### Version 2.0.0 (December 29, 2024)
- Updated WooCommerce templates to latest versions
- Removed 6 unused backup/experimental files
- Added comprehensive documentation
- Improved code organization and security

### Version 1.1.1 (December 14, 2024)
- Enhanced checkout flow
- Fixed post-order processing delay
- Updated CSS/JS version numbers

### Version 1.0.0 (December 13, 2024)
- Initial theme release
- Custom product grid with heat levels
- WooCommerce integration
- ACF custom fields setup

---

## Credits

**Theme Developer:** Spring in Alaska (www.springinalaska.com.au) with the assistance of Claudia
**Built For:** Hot Sauce Co
**WordPress Version:** 6.7+
**WooCommerce Version:** 9.8.0+

---

## License

This is a proprietary theme built for Hot Sauce Co. All rights reserved.

---

## Contact & Support

For theme support, customization requests, or bug reports, please contact the site administrator.
