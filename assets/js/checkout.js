/**
 * EMERGENCY CHECKOUT-ONLY JAVASCRIPT
 * Save this as: assets/js/checkout.js
 * This replaces the heavy woocommerce.js on checkout pages only
 */

jQuery(document).ready(function($) {
    'use strict';
    
    console.log('🚀 Emergency fast checkout mode');
    
    // IMMEDIATE checkout form visibility (no setTimeout!)
    $('.woocommerce-checkout, #customer_details, #order_review').css({
        'opacity': '1',
        'visibility': 'visible',
        'display': 'block'
    });
    
    // Essential phone formatting only
    $(document).on('input', 'input[type="tel"]', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length >= 10) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        }
        this.value = value;
    });
    
    // Fast error handling
    $(document.body).on('checkout_error', function() {
        $('.woocommerce-checkout').removeClass('processing');
        if ($('.woocommerce-error').length) {
            $('html, body').animate({
                scrollTop: $('.woocommerce-error').offset().top - 100
            }, 300); // Reduced from 600ms to 300ms
        }
    });
    
    // Essential field validation
    $('.woocommerce-checkout').on('blur', 'input[required]', function() {
        const $field = $(this);
        const value = $field.val().trim();
        
        if (!value) {
            $field.addClass('error');
        } else {
            $field.removeClass('error');
        }
    });
    
    // Show/hide shipping fields
    $('#ship-to-different-address-checkbox').on('change', function() {
        $('.shipping_address').toggleClass('hidden', !this.checked);
    });
    
    // NO CART DRAWER FUNCTIONALITY
    // NO CART COUNT UPDATES  
    // NO ADD TO CART AJAX
    // NO TIMEOUTS OR DELAYS
    
    console.log('✅ Fast checkout initialized');
});