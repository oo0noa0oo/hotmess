<?php

// Hide PHP deprecation warnings from plugins
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');

// Required includes
require_once(get_template_directory() . '/includes/acf.php');

/**
 * Hot Sauce Co WordPress Theme - Functions
 * "Fix Post-Order Processing Delay" Version
 */

// Fix ACF translation loading issue (WordPress 6.7+)
add_action('init', function() {
    if (function_exists('load_textdomain')) {
        $acf_lang_file = WP_PLUGIN_DIR . '/advanced-custom-fields/lang/acf-' . get_locale() . '.mo';
        if (file_exists($acf_lang_file)) {
            load_textdomain('acf', $acf_lang_file);
        }
    }
});

// Theme support and setup
function hotsauce_theme_setup() {
    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

    // Register custom square image size for product grid
    add_image_size('product-square', 600, 600, true); // 600x600 hard crop
}
add_action('after_setup_theme', 'hotsauce_theme_setup');

// Remove all gallery features (zoom, lightbox, slider)
function hotsauce_remove_gallery_features() {
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'hotsauce_remove_gallery_features', 20);

// Disable WooCommerce gallery scripts for stacked layout
function hotsauce_disable_gallery_scripts() {
    if (is_product()) {
        wp_dequeue_script('wc-single-product');
        wp_dequeue_script('zoom');
        wp_dequeue_script('flexslider');
        wp_dequeue_script('photoswipe');
        wp_dequeue_script('photoswipe-ui-default');
    }
}
add_action('wp_enqueue_scripts', 'hotsauce_disable_gallery_scripts', 99);

// Remove duplicate product title and other elements from WooCommerce single product page
function hotsauce_remove_product_elements() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
}
add_action('init', 'hotsauce_remove_product_elements');

// Enhanced script enqueuing with proper dependencies
function hotsauce_enqueue_assets() {
    // Main styles
    wp_enqueue_style('hotsauce-main', get_template_directory_uri() . '/assets/css/main.css', [], '1.1.1');
    wp_enqueue_style('hotsauce-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', ['hotsauce-main'], '1.1.1');
    
    // Main JavaScript
    wp_enqueue_script('hotsauce-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], '1.1.1', true);
    
    // WooCommerce integration
    if (class_exists('WooCommerce')) {
        // Ensure WooCommerce core scripts are loaded first
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
        wp_enqueue_script('wc-checkout');
        
        // Load WooCommerce single product gallery handler
        if (is_singular('product')) {
            wp_enqueue_script('wc-single-product');
        }
        
        // Our custom WooCommerce script with proper dependencies
        wp_enqueue_script('hotsauce-woocommerce', 
            get_template_directory_uri() . '/assets/js/woocommerce.js', 
            ['jquery', 'wc-add-to-cart', 'wc-cart-fragments', 'wc-checkout', 'wc-single-product'], 
            '1.0.1', 
            true
        );
        
        // Localize script with necessary data
        wp_localize_script('hotsauce-woocommerce', 'hotsauce_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'nonce' => wp_create_nonce('hotsauce_nonce'),
            'cart_url' => wc_get_cart_url(),
            'checkout_url' => wc_get_checkout_url(),
            'currency_symbol' => get_woocommerce_currency_symbol(),
        ]);
        
        // Settings for our custom functionality
        wp_localize_script('hotsauce-woocommerce', 'hotsauce_settings', [
            'auto_open_cart' => true,
            'cart_redirect' => false,
        ]);
        
        // Additional WooCommerce parameters
        wp_localize_script('hotsauce-main', 'woocommerce_params', [
            'currency_symbol' => get_woocommerce_currency_symbol(),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'hotsauce_enqueue_assets');

// Remove default WooCommerce styles to prevent conflicts
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Enable AJAX add to cart on shop pages
add_filter('woocommerce_loop_add_to_cart_link', 'hotsauce_ajax_add_to_cart_link', 10, 2);
function hotsauce_ajax_add_to_cart_link($link, $product) {
    if ($product && $product->is_purchasable() && $product->is_in_stock()) {
        $link = str_replace('add-to-cart', 'add-to-cart ajax_add_to_cart', $link);
    }
    return $link;
}

// Ensure WooCommerce AJAX parameters are available
add_action('wp_footer', 'hotsauce_ensure_wc_params');
function hotsauce_ensure_wc_params() {
    if (class_exists('WooCommerce') && !wp_script_is('wc-add-to-cart-params', 'done')) {
        wp_localize_script('wc-add-to-cart', 'wc_add_to_cart_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'i18n_view_cart' => esc_attr__('View cart', 'woocommerce'),
            'cart_url' => apply_filters('woocommerce_add_to_cart_redirect', wc_get_cart_url(), null),
            'is_cart' => is_cart(),
            'cart_redirect_after_add' => get_option('woocommerce_cart_redirect_after_add')
        ));
    }
}

// HEAT INTENSITY FUNCTIONS
function get_heat_intensity_from_scoville($scoville_rating) {
    $scoville = intval($scoville_rating);
    
    if ($scoville <= 2500) {
        return 1; // Mild
    } elseif ($scoville <= 10000) {
        return 2; // Medium
    } elseif ($scoville <= 50000) {
        return 3; // Hot
    } else {
        return 4; // Extreme
    }
}

function get_heat_intensity_level($heat_level) {
    $levels = [
        'mild' => 1,
        'medium' => 2,
        'hot' => 3,
        'extreme' => 4
    ];
    return isset($levels[strtolower($heat_level)]) ? $levels[strtolower($heat_level)] : 1;
}

function display_heat_intensity($level, $max = 4) {
    $output = '';
    for ($i = 1; $i <= $max; $i++) {
        $filled = $i <= $level ? 'filled' : 'empty';
        $output .= '<span class="heat-dot ' . $filled . '"></span>';
    }
    return $output;
}

// AJAX HANDLERS
add_action('wp_ajax_get_cart_count', 'hotsauce_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'hotsauce_get_cart_count');
function hotsauce_get_cart_count() {
    check_ajax_referer('hotsauce_nonce', 'nonce');
    
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    
    wp_send_json_success([
        'count' => $count
    ]);
}

add_action('wp_ajax_load_cart_contents', 'hotsauce_load_cart_contents');
add_action('wp_ajax_nopriv_load_cart_contents', 'hotsauce_load_cart_contents');
function hotsauce_load_cart_contents() {
    check_ajax_referer('hotsauce_nonce', 'nonce');
    
    if (WC()->cart->is_empty()) {
        wp_send_json_error([
            'message' => 'Cart is empty'
        ]);
    }
    
    ob_start();
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
        
        if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
            $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
            $product_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
            
            $heat_level = get_field('heat_level', $product_id);
            ?>
            <div class="cart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                <div class="cart-item-image">
                    <?php echo $_product->get_image(array(60, 60)); ?>
                </div>
                <div class="cart-item-details">
                    <h4 class="cart-item-name"><?php echo $product_name; ?></h4>
                    
                    <div class="cart-item-meta">
                        
                        <div class="cart-item-quantity">
                            <input type="number" 
                                   class="mini-cart-quantity" 
                                   value="<?php echo esc_attr($cart_item['quantity']); ?>" 
                                   min="0" 
                                   data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                        </div>
                        <span class="cart-item-subtotal"><?php echo $product_subtotal; ?></span>
                    </div>
                </div>
                <button class="mini-cart-remove" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php
        }
    }
    
    $contents = ob_get_clean();
    $total = WC()->cart->get_cart_total();
    
    wp_send_json_success([
        'contents' => $contents,
        'total' => strip_tags($total)
    ]);
}

add_action('wp_ajax_remove_cart_item', 'hotsauce_remove_cart_item');
add_action('wp_ajax_nopriv_remove_cart_item', 'hotsauce_remove_cart_item');
function hotsauce_remove_cart_item() {
    check_ajax_referer('hotsauce_nonce', 'nonce');
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    
    if (WC()->cart->remove_cart_item($cart_item_key)) {
        wp_send_json_success([
            'message' => 'Item removed from cart'
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Failed to remove item'
        ]);
    }
}

add_action('wp_ajax_update_cart_item_quantity', 'hotsauce_update_cart_item_quantity');
add_action('wp_ajax_nopriv_update_cart_item_quantity', 'hotsauce_update_cart_item_quantity');
function hotsauce_update_cart_item_quantity() {
    check_ajax_referer('hotsauce_nonce', 'nonce');
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity === 0) {
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        WC()->cart->set_quantity($cart_item_key, $quantity);
    }
    
    wp_send_json_success([
        'message' => 'Cart updated'
    ]);
}

// Product display functions - DISABLED (heat badge now on image only)
// add_action('woocommerce_single_product_summary', 'hotsauce_display_custom_product_fields', 25);
// function hotsauce_display_custom_product_fields() {
//     global $product;
//
//     $heat_level = get_field('heat_level', $product->get_id());
//     $scoville = get_field('scoville_rating', $product->get_id());
//
//     if ($heat_level || $scoville) {
//         echo '<div class="custom-product-meta">';
//
//         if ($heat_level) {
//             echo '<span class="heat-level">Heat Level: <span class="heat-badge heat-' . esc_attr($heat_level) . '">' . esc_html(ucfirst($heat_level)) . '</span></span>';
//         }
//
//         if ($scoville) {
//             echo '<span class="scoville">Scoville: <span class="scoville-rating">' . esc_html(number_format($scoville)) . ' SHU</span></span>';
//         }
//
//         echo '</div>';
//     }
// }

// Custom product tabs
add_filter('woocommerce_product_tabs', 'hotsauce_custom_product_tabs');
function hotsauce_custom_product_tabs($tabs) {
    global $product;
    
    $ingredients = get_field('ingredients', $product->get_id());
    $usage_instructions = get_field('usage_instructions', $product->get_id());
    
    if ($ingredients) {
        $tabs['ingredients'] = array(
            'title'    => __('Ingredients', 'woocommerce'),
            'priority' => 25,
            'callback' => 'hotsauce_ingredients_tab_content'
        );
    }
    
    if ($usage_instructions) {
        $tabs['usage'] = array(
            'title'    => __('Usage & Serving', 'woocommerce'),
            'priority' => 30,
            'callback' => 'hotsauce_usage_tab_content'
        );
    }
    
    return $tabs;
}

function hotsauce_ingredients_tab_content() {
    global $product;
    $ingredients = get_field('ingredients', $product->get_id());
    if ($ingredients) {
        echo '<div class="ingredients-content">';
        echo '<h3>Ingredients</h3>';
        echo '<p>' . wp_kses_post($ingredients) . '</p>';
        echo '</div>';
    }
}

function hotsauce_usage_tab_content() {
    global $product;
    $usage_instructions = get_field('usage_instructions', $product->get_id());
    if ($usage_instructions) {
        echo '<div class="usage-content">';
        echo '<h3>Usage & Serving Suggestions</h3>';
        echo '<p>' . wp_kses_post($usage_instructions) . '</p>';
        echo '</div>';
    }
}

// Cart fragments for dynamic updates
add_filter('woocommerce_add_to_cart_fragments', 'hotsauce_cart_count_fragments');
function hotsauce_cart_count_fragments($fragments) {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    
    $fragments['.cart-count'] = '<span class="cart-count ' . ($count > 0 ? 'visible' : 'hidden') . '" id="cartCount">' . $count . '</span>';
    
    return $fragments;
}

// Register footer menu
function register_footer_menu() {
    register_nav_menus(array(
        'footer-menu' => __('Footer Menu', 'textdomain'),
        'footer-secondary' => __('Footer Secondary Menu', 'textdomain'),
    ));
}
add_action('init', 'register_footer_menu');

// Force WooCommerce template override detection
add_action('after_setup_theme', 'hotsauce_force_wc_template_override');
function hotsauce_force_wc_template_override() {
    // Clear WooCommerce template cache
    if (function_exists('wc_clear_template_cache')) {
        wc_clear_template_cache();
    }
    
    // Ensure WooCommerce knows about our template directory
    add_filter('woocommerce_locate_template', 'hotsauce_locate_template', 10, 3);
}

function hotsauce_locate_template($template, $template_name, $template_path) {
    // Check if we have a custom template in our theme
    $theme_template = locate_template(array(
        trailingslashit($template_path) . $template_name,
        $template_name
    ));
    
    if ($theme_template) {
        return $theme_template;
    }
    
    return $template;
}

// Include full cart drawer styles
add_action('wp_head', 'hotsauce_cart_drawer_styles');
function hotsauce_cart_drawer_styles() {
    ?>
    <style>
    /* Cart Drawer Styles - Full Original Version */
    
    </style>
    <?php
}

// 1. DISABLE EVERYTHING THAT HAPPENS AFTER ORDER CREATION
add_action('woocommerce_checkout_order_processed', 'hotsauce_disable_post_order_delays', 1);
function hotsauce_disable_post_order_delays($order_id, $posted_data = null, $order = null) {
    if (WP_DEBUG) {
        error_log("🚀 POST-ORDER: Disabling delays after order #{$order_id} created");
    }
    
    // 1. DISABLE EMAIL NOTIFICATIONS (major delay cause)
    remove_all_actions('woocommerce_order_status_pending_to_processing_notification');
    remove_all_actions('woocommerce_order_status_pending_to_on-hold_notification');
    remove_all_actions('woocommerce_order_status_pending_to_completed_notification');
    
    // 2. DISABLE ORDER STATUS CHANGE HOOKS (they trigger emails and webhooks)
    remove_all_actions('woocommerce_order_status_changed');
    
    // 3. DISABLE STOCK REDUCTION (do it later)
    remove_action('woocommerce_payment_complete', 'wc_maybe_reduce_stock_levels');
    remove_action('woocommerce_order_status_completed', 'wc_maybe_reduce_stock_levels');
    remove_action('woocommerce_order_status_processing', 'wc_maybe_reduce_stock_levels');
    
    // 4. DISABLE WEBHOOK PROCESSING
    remove_all_actions('woocommerce_new_order');
    remove_all_actions('woocommerce_order_status_pending_to_processing');
    
    // 5. DISABLE ANALYTICS TRACKING
    remove_all_actions('woocommerce_analytics_track_order');
    remove_all_actions('woocommerce_new_order');
    
    // 6. DISABLE ORDER NOTES
    add_filter('woocommerce_order_note_added', '__return_false');
    
    // 7. SCHEDULE THESE OPERATIONS FOR LATER (async)
    wp_schedule_single_event(time() + 10, 'hotsauce_process_post_order_tasks', [$order_id]);
    
    if (WP_DEBUG) {
        error_log("🚀 POST-ORDER: All delays disabled for order #{$order_id}");
    }
}

// 2. PROCESS POST-ORDER TASKS ASYNCHRONOUSLY
add_action('hotsauce_process_post_order_tasks', 'hotsauce_async_post_order_processing');
function hotsauce_async_post_order_processing($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    
    if (WP_DEBUG) {
        error_log("📋 ASYNC: Processing post-order tasks for #{$order_id}");
    }
    
    // Re-enable and process everything that was disabled
    
    // 1. Send order emails
    WC()->mailer()->emails['WC_Email_New_Order']->trigger($order_id);
    WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger($order_id);
    
    // 2. Reduce stock levels
    wc_maybe_reduce_stock_levels($order_id);
    
    // 3. Process webhooks if any
    do_action('woocommerce_new_order', $order_id);
    
    // 4. Analytics tracking
    do_action('woocommerce_analytics_track_order', $order_id);
    
    if (WP_DEBUG) {
        error_log("✅ ASYNC: Completed post-order tasks for #{$order_id}");
    }
}

// 3. AGGRESSIVE: BYPASS PAYMENT COMPLETE HOOKS FOR COD
add_action('woocommerce_checkout_order_processed', 'hotsauce_bypass_payment_complete_for_cod', 5);
function hotsauce_bypass_payment_complete_for_cod($order_id, $posted_data = null, $order = null) {
    if (!$order) {
        $order = wc_get_order($order_id);
    }
    
    if ($order && $order->get_payment_method() === 'cod') {
        // For COD, skip the entire payment complete process
        remove_action('woocommerce_payment_complete', 'wc_maybe_reduce_stock_levels');
        remove_all_actions('woocommerce_payment_complete');
        
        // Just mark as processing and skip everything else
        $order->update_status('processing', 'Order received.');
        
        if (WP_DEBUG) {
            error_log("💰 COD: Bypassed payment complete hooks for order #{$order_id}");
        }
    }
}

// 5. DISABLE SLOW WORDPRESS FEATURES DURING CHECKOUT
add_action('woocommerce_checkout_process', 'hotsauce_disable_wordpress_delays');
function hotsauce_disable_wordpress_delays() {
    // Disable automatic updates during checkout
    remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');
    
    // Disable cron during checkout
    remove_action('wp_loaded', 'wp_cron');
    
    // Disable WordPress heartbeat
    wp_deregister_script('heartbeat');
    
    // Disable comment and pingback processing
    remove_action('do_pings', 'do_all_pings');
    
    if (WP_DEBUG) {
        error_log("🚀 WP: Disabled WordPress delays during checkout");
    }
}

// 6. MONITOR THE SPECIFIC DELAY POINTS
add_action('woocommerce_checkout_order_processed', 'hotsauce_monitor_post_order_steps', 2);
function hotsauce_monitor_post_order_steps($order_id) {
    update_option('hotsauce_post_order_start', microtime(true));
    update_option('hotsauce_profiler_steps', []);
    
    // Log each major step
    add_action('woocommerce_payment_complete', function() {
        hotsauce_log_delay_step('Payment Complete');
    }, 1);
    
    add_action('woocommerce_order_status_changed', function() {
        hotsauce_log_delay_step('Status Changed');
    }, 1);
    
    add_action('woocommerce_thankyou', function() {
        hotsauce_log_delay_step('Thank You Hook');
    }, 1);
}

function hotsauce_log_delay_step($step_name) {
    $start = get_option('hotsauce_post_order_start');
    if ($start) {
        $duration = (microtime(true) - $start) * 1000;
        
        $steps = get_option('hotsauce_profiler_steps', []);
        $steps[] = [
            'step' => $step_name,
            'time' => round($duration, 2) . 'ms',
            'timestamp' => microtime(true)
        ];
        update_option('hotsauce_profiler_steps', $steps);
        
        if (WP_DEBUG) {
            error_log("⏱️ DELAY STEP: {$step_name} at {$duration}ms");
        }
    }
}

// 7. SHOW PROFILER RESULTS
/*add_action('wp_footer', 'hotsauce_show_profiler_results');
function hotsauce_show_profiler_results() {
    if (!current_user_can('administrator')) return;
    
    $steps = get_option('hotsauce_profiler_steps', []);
    
    if (!empty($steps) && (is_order_received_page() || is_checkout())) {
        ?>
        <div class="hotsauce-debug-profiler">
            <h4>⏱️ CHECKOUT PROFILER</h4>
            <?php foreach ($steps as $step): ?>
                <div><?php echo $step['step']; ?>: <?php echo $step['time']; ?></div>
            <?php endforeach; ?>
            <button onclick="this.parentElement.style.display='none'">Close</button>
        </div>
        
        <script>
        console.log('⏱️ CHECKOUT PROFILER RESULTS:');
        <?php foreach ($steps as $step): ?>
        console.log('<?php echo $step['step']; ?>: <?php echo $step['time']; ?>');
        <?php endforeach; ?>
        </script>
        <?php
        
        // Clear profiler data after showing
        delete_option('hotsauce_profiler_steps');
        delete_option('hotsauce_post_order_start');
    }
}*/

// 8. DIAGNOSTIC: SHOW POST-ORDER TIMING
/* add_action('wp_footer', 'hotsauce_post_order_diagnostic');
function hotsauce_post_order_diagnostic() {
    if (!is_order_received_page() || !current_user_can('administrator')) return;
    
    ?>
    <div class="hotsauce-debug-diagnostic">
        <h4>🎯 POST-ORDER DIAGNOSTIC</h4>
        <p>The delay happens AFTER order creation!</p>
        <p>✅ Order Creation: ~300ms</p>
        <p>❌ Post-Order Processing: ~14,500ms</p>
        <p><strong>Target identified!</strong></p>
        <p><small>Applying post-order optimizations...</small></p>
    </div>
    <?php
}*/

// 9. FIX THE hotsauce_ajax UNDEFINED ERROR
add_action('wp_enqueue_scripts', 'hotsauce_fix_ajax_undefined_final', 999);
function hotsauce_fix_ajax_undefined_final() {
    // Ensure hotsauce_ajax is always defined
    wp_localize_script('hotsauce-main', 'hotsauce_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hotsauce_nonce'),
        'is_checkout' => is_checkout()
    ]);
}

?>