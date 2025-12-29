// SIMPLE OPTIMIZATION: Just modify script loading without creating new files
function hotsauce_enqueue_assets() {
    // Main styles (always load these)
    wp_enqueue_style('hotsauce-main', get_template_directory_uri() . '/assets/css/main.css', [], '1.0.2');
    wp_enqueue_style('hotsauce-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', ['hotsauce-main'], '1.0.2');
    
    // Always load main script
    wp_enqueue_script('hotsauce-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], '1.0.2', true);
    
    // WooCommerce integration
    if (class_exists('WooCommerce')) {
        
        if (is_checkout()) {
            // CHECKOUT PAGE: Only load essential WooCommerce scripts
            wp_enqueue_script('wc-checkout');
            
            // DON'T load the heavy woocommerce.js file on checkout
            // Just provide basic AJAX params for any needed functionality
            wp_localize_script('hotsauce-main', 'hotsauce_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('hotsauce_nonce'),
                'checkout_url' => wc_get_checkout_url(),
            ]);
            
        } else {
            // OTHER PAGES: Load full functionality
            wp_enqueue_script('wc-add-to-cart');
            wp_enqueue_script('wc-cart-fragments');
            
            wp_enqueue_script('hotsauce-woocommerce', 
                get_template_directory_uri() . '/assets/js/woocommerce.js', 
                ['jquery', 'wc-add-to-cart', 'wc-cart-fragments'], 
                '1.0.2', 
                true
            );
            
            wp_localize_script('hotsauce-woocommerce', 'hotsauce_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
                'nonce' => wp_create_nonce('hotsauce_nonce'),
                'cart_url' => wc_get_cart_url(),
                'checkout_url' => wc_get_checkout_url(),
                'currency_symbol' => get_woocommerce_currency_symbol(),
            ]);
            
            wp_localize_script('hotsauce-woocommerce', 'hotsauce_settings', [
                'auto_open_cart' => true,
                'cart_redirect' => false,
            ]);
        }
        
        // Ensure WooCommerce parameters are available
        wp_localize_script('hotsauce-main', 'woocommerce_params', [
            'currency_symbol' => get_woocommerce_currency_symbol(),
        ]);
    }
}