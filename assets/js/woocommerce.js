/**
 * Hot Sauce Co - WooCommerce Integration JavaScript
 * Updated version with item count in cart drawer title
 */

(function($) {
    'use strict';

    const HotSauceWooCommerce = {
        
        init: function() {
            console.log('🌶️ Initializing Hot Sauce WooCommerce...');

            // Initialize core features
            this.initCartDrawer();
            this.initAddToCart();
            this.initCartUpdates();
            this.initCheckoutEnhancements();
            this.initCustomQuantityButtons();
            this.updateCartCount();

            // Debug info
            this.debugInfo();
        },

        /**
         * Debug information
         */
        debugInfo: function() {
            if (typeof wc_add_to_cart_params !== 'undefined') {
                console.log('✅ WooCommerce add to cart params loaded');
            } else {
                console.warn('⚠️ WooCommerce add to cart params missing');
            }
            
            if (typeof hotsauce_ajax !== 'undefined') {
                console.log('✅ Hot Sauce AJAX params loaded');
            } else {
                console.warn('⚠️ Hot Sauce AJAX params missing');
            }
        },

        /**
         * Initialize cart drawer functionality
         */
        initCartDrawer: function() {
            // Create cart drawer if it doesn't exist
            if (!$('.cart-drawer').length) {
                this.createCartDrawer();
            }

            // Cart button click
            $(document).on('click', '.cart-btn, .cart-icon', function(e) {
                e.preventDefault();
                HotSauceWooCommerce.openCartDrawer();
            });

            // Close cart drawer
            $(document).on('click', '.cart-close, .cart-backdrop', function(e) {
                e.preventDefault();
                HotSauceWooCommerce.closeCartDrawer();
            });
        },

        /**
         * Create cart drawer HTML structure
         */
        createCartDrawer: function() {
            const cart_url = (typeof hotsauce_ajax !== 'undefined') ? hotsauce_ajax.cart_url : '/cart';
            const checkout_url = (typeof hotsauce_ajax !== 'undefined') ? hotsauce_ajax.checkout_url : '/checkout';
            
            const drawerHTML = `
                <div class="cart-drawer" id="cartDrawer">
                    <div class="cart-backdrop" id="cartBackdrop"></div>
                    <div class="cart-content">
                        <div class="cart-header">
                            <h3 id="cartTitle">Shopping Cart</h3>
                            <button class="cart-close" id="cartClose">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="cart-items" id="cartItems">
                            <div class="cart-loading">Loading...</div>
                        </div>
                        <div class="cart-footer">
                            <div class="cart-total">
                                <span>Total: <span id="cartTotal">$0.00</span></span>
                            </div>
                            <a href="${cart_url}" class="btn btn-primary cart-view-cart">VIEW CART</a>
                            <a href="${checkout_url}" class="btn btn-primary cart-checkout">CHECKOUT</a>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(drawerHTML);
        },

        /**
         * Update cart drawer title with item count
         */
        updateCartTitle: function(itemCount) {
            const $title = $('#cartTitle');
            if ($title.length) {
                if (itemCount > 0) {
                    const itemText = itemCount === 1 ? 'item' : 'items';
                    $title.text(`Shopping Cart - ${itemCount} ${itemText}`);
                } else {
                    $title.text('Shopping Cart');
                }
            }
        },

        /**
         * Open cart drawer
         */
        openCartDrawer: function() {
            $('.cart-drawer').addClass('active');
            $('body').addClass('cart-open');
            
            // Load contents and update title after drawer is visible
            this.loadCartContents();
        },

        /**
         * Close cart drawer
         */
        closeCartDrawer: function() {
            $('.cart-drawer').removeClass('active');
            $('body').removeClass('cart-open');
        },

        /**
         * Load cart contents via AJAX
         */
        loadCartContents: function() {
            if (typeof hotsauce_ajax === 'undefined') {
                $('#cartItems').html('<div class="cart-error">Configuration error</div>');
                this.updateCartTitle(0);
                return;
            }

            const self = this;

            $.ajax({
                url: hotsauce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_cart_contents',
                    nonce: hotsauce_ajax.nonce
                },
                beforeSend: function() {
                    $('#cartItems').html('<div class="cart-loading">Loading...</div>');
                    // Set loading title
                    $('#cartTitle').text('Shopping Cart - Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        $('#cartItems').html(response.data.contents);
                        $('#cartTotal').html(response.data.total);

                        // Get item count - try multiple sources
                        let itemCount = 0;
                        if (response.data.count !== undefined) {
                            itemCount = response.data.count;
                        } else if (response.data.item_count !== undefined) {
                            itemCount = response.data.item_count;
                        } else {
                            // Fallback: count items in the DOM
                            itemCount = $('#cartItems .cart-item, #cartItems .mini_cart_item').length;
                        }

                        console.log('Cart item count:', itemCount);
                        self.updateCartTitle(itemCount);

                        // Add quantity buttons to cart items
                        self.addCartQuantityButtons();
                    } else {
                        $('#cartItems').html('<div class="cart-empty">Your cart is empty</div>');
                        self.updateCartTitle(0);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Cart load error:', error);
                    $('#cartItems').html('<div class="cart-error">Error loading cart</div>');
                    self.updateCartTitle(0);
                }
            });
        },

        /**
         * Initialize add to cart functionality
         */
        initAddToCart: function() {
            // Handle WooCommerce add to cart events
            $('body').on('added_to_cart', function(event, fragments, cart_hash, button) {
                console.log('✅ Product added to cart');
                HotSauceWooCommerce.updateCartCount();
                HotSauceWooCommerce.showAddToCartFeedback(button);

                // Auto-open cart drawer if enabled
                if (typeof hotsauce_settings !== 'undefined' && hotsauce_settings.auto_open_cart) {
                    setTimeout(function() {
                        HotSauceWooCommerce.openCartDrawer();
                    }, 500);
                }
            });

            // AJAX add to cart for shop page products
            $(document).on('click', '.ajax_add_to_cart:not(.loading)', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const product_id = $button.data('product_id');
                const quantity = $button.data('quantity') || 1;
                
                if (!product_id) return false;
                
                // Check if WooCommerce AJAX is available
                if (typeof wc_add_to_cart_params === 'undefined') {
                    console.warn('WooCommerce AJAX not available, falling back to page redirect');
                    window.location.href = $button.attr('href');
                    return false;
                }

                $.ajax({
                    url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity
                    },
                    beforeSend: function() {
                        $button.addClass('loading').prop('disabled', true);
                        $button.text('ADDING...');
                    },
                    success: function(response) {
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }

                        // Success feedback
                        $button.text('ADDED!').addClass('added-to-cart');

                        // Update cart fragments
                        if (response.fragments) {
                            HotSauceWooCommerce.updateCartFragments(response.fragments);
                        }

                        // Update cart count
                        HotSauceWooCommerce.updateCartCount();

                        // Reset button after delay
                        setTimeout(function() {
                            $button.text('ADD TO CART');
                            $button.removeClass('added-to-cart loading').prop('disabled', false);
                        }, 2000);

                        // Trigger WooCommerce event
                        $('body').trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                    },
                    error: function(xhr, status, error) {
                        console.error('Add to cart error:', error);
                        
                        // Reset button
                        $button.text('ADD TO CART');
                        $button.removeClass('loading').prop('disabled', false);
                        
                        // Show error message
                        alert('Error adding to cart. Please try again.');
                    }
                });

                return false;
            });
        },

        /**
         * Show add to cart feedback
         */
        showAddToCartFeedback: function(button) {
            if (!button || !button.length) return;
            
            const originalText = button.text();
            const originalClass = button.attr('class');
            
            button.text('ADDED!');
            button.addClass('added-to-cart');
            
            setTimeout(function() {
                button.text(originalText);
                button.attr('class', originalClass);
            }, 2000);
        },

        /**
         * Update cart count
         */
        updateCartCount: function() {
            if (typeof hotsauce_ajax === 'undefined') return;

            $.ajax({
                url: hotsauce_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_cart_count',
                    nonce: hotsauce_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        const count = response.data.count;
                        $('.cart-count').text(count);
                        $('.cart-count').toggle(count > 0);
                        
                        // Update cart drawer title if drawer is open
                        if ($('.cart-drawer').hasClass('active')) {
                            HotSauceWooCommerce.updateCartTitle(count);
                        }
                    }
                },
                error: function(xhr, status, error) {
					console.error('Cart count update error:', {
						status: xhr.status,
						statusText: xhr.statusText,
						responseText: xhr.responseText,
						error: error
					});

					// Fallback: set count to 0 for empty cart scenarios
					$('.cart-count').text('0').hide();
					
					// Update cart drawer title
					if ($('.cart-drawer').hasClass('active')) {
					    HotSauceWooCommerce.updateCartTitle(0);
					}
				}
            });
        },

        /**
         * Update cart fragments
         */
        updateCartFragments: function(fragments) {
            if (fragments) {
                $.each(fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
            }
        },

        /**
         * Initialize cart updates (remove items, change quantities)
         */
        initCartUpdates: function() {
            // Handle cart item removal from drawer
            $(document).on('click', '.mini-cart-remove', function(e) {
                e.preventDefault();
                
                if (typeof hotsauce_ajax === 'undefined') return;
                
                const $link = $(this);
                const cartItemKey = $link.data('cart-item-key');
                
                $.ajax({
                    url: hotsauce_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'remove_cart_item',
                        cart_item_key: cartItemKey,
                        nonce: hotsauce_ajax.nonce
                    },
                    beforeSend: function() {
                        $link.closest('.cart-item').addClass('removing');
                    },
                    success: function(response) {
                        if (response.success) {
                            HotSauceWooCommerce.updateCartCount();
                            HotSauceWooCommerce.loadCartContents();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Remove cart item error:', error);
                    }
                });
            });

            // Handle quantity changes in mini cart
            $(document).on('change', '.mini-cart-quantity', function() {
                if (typeof hotsauce_ajax === 'undefined') return;
                
                const $input = $(this);
                const cartItemKey = $input.data('cart-item-key');
                const quantity = $input.val();
                
                $.ajax({
                    url: hotsauce_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'update_cart_item_quantity',
                        cart_item_key: cartItemKey,
                        quantity: quantity,
                        nonce: hotsauce_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            HotSauceWooCommerce.updateCartCount();
                            HotSauceWooCommerce.loadCartContents();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update cart quantity error:', error);
                    }
                });
            });
        },

        /**
         * Initialize checkout enhancements
         */
        initCheckoutEnhancements: function() {
            // Only run on checkout page
            if (!$('body').hasClass('woocommerce-checkout')) return;
            
            console.log('🛒 Initializing checkout enhancements');
            
            // Auto-format phone numbers
            $(document).on('input', 'input[type="tel"], input[name*="phone"]', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length >= 6) {
                    value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                }
                this.value = value;
            });

            // Enhanced field validation
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
            
            // Ensure checkout form is visible
            setTimeout(function() {
                $('.woocommerce-checkout, #customer_details, #order_review').css({
                    'opacity': '1',
                    'visibility': 'visible',
                    'display': 'block'
                });
            }, 100);
        },

        /**
         * Initialize custom quantity +/- buttons
         */
        initCustomQuantityButtons: function() {
            console.log('🔢 Initializing custom quantity buttons');

            // Add buttons to single product pages
            if ($('body').hasClass('single-product')) {
                this.addQuantityButtons('.single-product .quantity');
            }

            // Add buttons to cart page
            if ($('body').hasClass('woocommerce-cart')) {
                this.addQuantityButtons('.woocommerce-cart .quantity');
            }

            // Add buttons to cart drawer (will be called when cart loads)
            this.addCartDrawerQuantityButtons();
        },

        /**
         * Add quantity buttons to specific selector
         */
        addQuantityButtons: function(selector) {
            $(selector).each(function() {
                const $quantity = $(this);
                const $input = $quantity.find('input[type="number"]');

                // Skip if buttons already added or no input found
                if ($quantity.find('.qty-btn').length || !$input.length) return;

                // Create + and - buttons
                const $plusBtn = $('<button type="button" class="qty-btn plus">+</button>');
                const $minusBtn = $('<button type="button" class="qty-btn minus">−</button>');

                // Append buttons to quantity container
                $quantity.append($plusBtn);
                $quantity.append($minusBtn);

                // Handle + button click
                $plusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 1;
                    const max = parseInt($input.attr('max')) || 999;

                    if (currentVal < max) {
                        $input.val(currentVal + 1).trigger('change');
                    }
                });

                // Handle - button click
                $minusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 1;
                    const min = parseInt($input.attr('min')) || 0;

                    if (currentVal > min) {
                        $input.val(currentVal - 1).trigger('change');
                    }
                });
            });
        },

        /**
         * Add quantity buttons to cart drawer items
         */
        addCartDrawerQuantityButtons: function() {
            // This is kept for compatibility but functionality moved to addCartQuantityButtons
            this.addCartQuantityButtons();
        },

        /**
         * Add quantity buttons to cart drawer items (called after cart loads)
         */
        addCartQuantityButtons: function() {
            console.log('🔢 Adding cart quantity buttons');

            $('.mini-cart-quantity').each(function() {
                const $input = $(this);

                // Skip if already wrapped
                if ($input.parent().hasClass('quantity-wrapper')) return;

                // Wrap input in quantity-wrapper
                $input.wrap('<div class="quantity-wrapper"></div>');
                const $wrapper = $input.parent();

                // Skip if buttons already added
                if ($wrapper.find('.qty-btn').length) return;

                // Create + and - buttons
                const $plusBtn = $('<button type="button" class="qty-btn plus">+</button>');
                const $minusBtn = $('<button type="button" class="qty-btn minus">−</button>');

                // Append buttons to wrapper
                $wrapper.append($plusBtn);
                $wrapper.append($minusBtn);

                // Handle + button click
                $plusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 0;
                    const max = parseInt($input.attr('max')) || 999;

                    if (currentVal < max) {
                        $input.val(currentVal + 1).trigger('change');
                    }
                });

                // Handle - button click
                $minusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentVal = parseInt($input.val()) || 0;
                    const min = parseInt($input.attr('min')) || 0;

                    if (currentVal > min) {
                        $input.val(currentVal - 1).trigger('change');
                    }
                });
            });
        },

        /**
         * Utility function to show notifications
         */
        showNotification: function(message, type = 'success') {
            const notificationHTML = `
                <div class="hotsauce-notification ${type}">
                    <span>${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            `;

            $('body').append(notificationHTML);

            const $notification = $('.hotsauce-notification').last();

            setTimeout(function() {
                $notification.addClass('show');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(function() {
                $notification.remove();
            }, 5000);

            // Manual close
            $notification.find('.notification-close').on('click', function() {
                $notification.remove();
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        HotSauceWooCommerce.init();
    });

    // Handle AJAX cart updates from WooCommerce
    $(document.body).on('updated_cart_totals updated_wc_div', function() {
        HotSauceWooCommerce.updateCartCount();
    });

    // Handle checkout form updates
    $(document.body).on('update_checkout', function() {
        console.log('🛒 Checkout updated');
    });
    
    // Handle checkout errors
    $(document.body).on('checkout_error', function() {
        $('.woocommerce-checkout').removeClass('processing');
        $('html, body').animate({
            scrollTop: $('.woocommerce-error').offset().top - 100
        }, 600);
    });

    // Global access
    window.HotSauceWooCommerce = HotSauceWooCommerce;

})(jQuery);