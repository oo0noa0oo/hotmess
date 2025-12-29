<?php
/**
 * Order Received/Thank You Page Template
 * 
 * This template can be used to override WooCommerce's default thank you page
 * Save this as: woocommerce/checkout/thankyou.php in your theme directory
 */

defined('ABSPATH') || exit;
?>

<main class="checkout-main">
    <div class="checkout-container">
        <div class="checkout-content">
            <div class="woocommerce">
				<?php if ($order) : ?>
				<?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
				<h2 class="woocommerce-order-details__title">Order summery</h2>
				<div class="order-details-summary">
					<div class="order-number">
						<strong>Order Number:</strong> #<?php echo $order->get_order_number(); ?>
					</div>
					<div class="order-date">
						<strong>Order Date:</strong> <?php echo wc_format_datetime($order->get_date_created()); ?>
					</div>
					<div class="order-email">
						<strong>Email:</strong> <?php echo $order->get_billing_email(); ?>
					</div>
					<div class="order-total">
						<strong>Total:</strong> <?php echo $order->get_formatted_order_total(); ?>
					</div>
				</div>
                    <?php do_action('woocommerce_thankyou', $order->get_id()); ?>
                    <div class="order-items-section">
                        <h3>Your Hot Sauce Order</h3>
                        <?php
                        $show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
                        $show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
                        ?>
                    </div>
                    
                <?php else : ?>
                    
                    <div class="order-received-error">
                        <div class="error-message">
                            <h2>Order Not Found</h2>
                            <p>We couldn't find your order. Please check your order number and try again.</p>
                            <a href="<?php echo wc_get_page_permalink('home'); ?>" class="btn btn-primary">
                                🌶️ Continue Shopping
                            </a>
                        </div>
                    </div>
                    
                <?php endif; ?>
                
                <div class="order-actions">
                    <div class="action-buttons">
                        <a href="<?php echo wc_get_page_permalink('home'); ?>" class="btn btn-secondary">
                            🌶️ Continue Shopping
                        </a>
                        <?php if ($order && is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="btn btn-outline">
                                📋 View Order Details
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="thank-you-message">
                    <h3>What's Next?</h3>
                    <div class="next-steps">
                        <div class="step">
                            <h4>📧 Order Confirmation</h4>
                            <p>You'll receive an email confirmation shortly with your order details.</p>
                        </div>
                        <div class="step">
                            <h4>📦 Processing</h4>
                            <p>We'll carefully prepare your hot sauce order within 1-2 business days.</p>
                        </div>
                        <div class="step">
                            <h4>🚚 Shipping</h4>
                            <p>Your spicy treasures will be shipped and you'll receive tracking information.</p>
                        </div>
                    </div>
                </div>
			  </div>
            </div>
        </div>
        
    </div>
</main>

<style>
/* Claudia noted: EMBEDDED STYLES FOUND HERE - Consider consolidating all these styles to main.css/woocommerce.css
   This template contains ~300 lines of CSS that should be in external stylesheet
   Current: Styles duplicated across multiple template files (page.php, index.php, thankyou.php, front-page.php)
*/

/* Order Received Page Styles - Based on Checkout Styling */
.checkout-main {
    min-height: 70vh;
    padding: 2rem 0;
}

.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.checkout-header.order-received-header {
    text-align: center;
    padding: 2rem 0;
    border-bottom: 2px solid var(--color-border, #ddd);
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
    margin: -2rem -1.5rem 3rem -1.5rem;
    padding: 3rem 1.5rem;
}

.checkout-header.order-received-header h1 {
    font-family: var(--font-title, serif);
    font-size: clamp(2.5rem, 5vw, 4rem);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.checkout-header.order-received-header p {
    font-size: 1.25rem;
    margin: 0;
    color: rgba(255,255,255,0.9);
}

.checkout-content {
    background: var(--color-background, #fff);
}

/* Success Message */
.order-received-success {
    background: #dcfce7;
    border: 2px solid #22c55e;
    padding: 2rem;
    margin-bottom: 3rem;
    text-align: center;
}

.success-message h2 {
    font-family: var(--font-heading, serif);
    font-size: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    color: #15803d;
}

.success-message p {
    font-size: 1.1rem;
    color: #166534;
    margin: 0;
}

/* Order Details Summary */
	.woocommerce-order-details__title {
		margin: 1.5rem 0 1.5rem 0;
	}
.order-details-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
    padding: 2rem;
    background: var(--color-muted, #f9f9f9);
    border: 2px solid var(--color-border, #ddd);
}

.order-details-summary > div {
    padding: 1rem;
    background: var(--color-background, #fff);
    border: 1px solid var(--color-border, #ddd);
    text-align: center;
}

.order-details-summary strong {
    font-family: var(--font-heading, serif);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-accent, #ff4444);
    display: block;
    margin-bottom: 0.5rem;
}

/* Error Message */
.order-received-error {
    background: #fef2f2;
    border: 2px solid #ef4444;
    padding: 3rem 2rem;
    text-align: center;
    margin-bottom: 3rem;
}

.error-message h2 {
    font-family: var(--font-heading, serif);
    font-size: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    color: #dc2626;
}

.error-message p {
    font-size: 1.1rem;
    color: #991b1b;
    margin-bottom: 2rem;
}

/* Section Headings */
.order-details-section h3,
.order-items-section h3,
.customer-details-section h3,
.thank-you-message h3 {
    font-family: var(--font-heading, serif);
    font-size: 1.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 3rem 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-border, #ddd);
    color: var(--color-foreground, #333);
}

/* Order Overview */
.woocommerce-order-overview__list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    list-style: none;
    margin: 0;
    padding: 2rem;
    background: var(--color-muted, #f9f9f9);
    border: 2px solid var(--color-border, #ddd);
}

.woocommerce-order-overview__list li {
    padding: 1rem;
    background: var(--color-background, #fff);
    border: 1px solid var(--color-border, #ddd);
    text-align: center;
    font-family: var(--font-body, sans-serif);
}

.woocommerce-order-overview__list strong {
    font-family: var(--font-heading, serif);
    color: var(--color-accent, #ff4444);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: block;
    margin-top: 0.5rem;
}

/* Action Buttons */
.order-actions {
    margin: 3rem 0;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-block;
    padding: 1rem 2rem;
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-decoration: none;
    border: 2px solid;
    transition: all 0.3s ease;
    cursor: pointer;
    margin: 0.5rem;
}

.btn-primary {
    background: var(--color-accent, #ff4444);
    color: var(--color-accent-foreground, #fff);
    border-color: var(--color-accent, #ff4444);
}

.btn-secondary {
    background: var(--color-foreground, #333);
    color: var(--color-background, #fff);
    border-color: var(--color-foreground, #333);
}

.btn-outline {
    background: transparent;
    color: var(--color-foreground, #333);
    border-color: var(--color-foreground, #333);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-primary:hover {
    background: var(--color-foreground, #333);
    border-color: var(--color-foreground, #333);
}

.btn-secondary:hover {
    background: var(--color-accent, #ff4444);
    border-color: var(--color-accent, #ff4444);
}

.btn-outline:hover {
    background: var(--color-foreground, #333);
    color: var(--color-background, #fff);
}

/* Thank You Message */
.thank-you-message {
    background: var(--color-muted, #f9f9f9);
    padding: 3rem 2rem;
    border: 2px solid var(--color-border, #ddd);
    margin-top: 3rem;
    text-align: center;
}

.next-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.step {
    background: var(--color-background, #fff);
    padding: 2rem;
    border: 2px solid var(--color-border, #ddd);
}

.step h4 {
    font-family: var(--font-heading, serif);
    font-size: 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    color: var(--color-accent, #ff4444);
}

.step p {
    margin: 0;
    color: var(--color-muted-foreground, #666);
    line-height: 1.6;
}

/* WooCommerce Order Tables */
.woocommerce table.shop_table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 2px solid var(--color-border, #ddd);
    background: var(--color-background, #fff);
}

.woocommerce table.shop_table th,
.woocommerce table.shop_table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--color-border, #ddd);
    font-family: var(--font-body, sans-serif);
}

.woocommerce table.shop_table th {
    background: var(--color-muted, #f9f9f9);
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid var(--color-border, #ddd);
}

.woocommerce table.shop_table .product-name {
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.woocommerce table.shop_table .product-total {
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-align: right;
    color: var(--color-accent, #ff4444);
}

/* Customer Details */
.woocommerce-customer-details {
    margin-top: 2rem;
}

.woocommerce-customer-details address {
    background: var(--color-muted, #f9f9f9);
    padding: 1.5rem;
    border: 2px solid var(--color-border, #ddd);
    font-style: normal;
    line-height: 1.6;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .checkout-container {
        padding: 0 1rem;
    }
    
    .checkout-header.order-received-header {
        margin: -2rem -1rem 2rem -1rem;
        padding: 2rem 1rem;
    }
    
    .checkout-header.order-received-header h1 {
        font-size: 2.5rem;
    }
    
    .order-details-summary {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
    
    .woocommerce-order-overview__list {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .next-steps {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .step {
        padding: 1.5rem;
    }
    
    .thank-you-message {
        padding: 2rem 1rem;
    }
}

/* Print Styles */
@media print {
    .checkout-header.order-received-header {
        background: none !important;
        color: #333 !important;
    }
    
    .order-actions,
    .btn {
        display: none;
    }
    
    .thank-you-message {
        page-break-before: always;
    }
}
</style>

<script>
// Order Received Page JavaScript
jQuery(document).ready(function($) {
    'use strict';
    
    console.log('🌶️ Order received page loaded');
    
    // Add celebration animation on load
    $('.order-received-success').hide().fadeIn(1000);
    
    // Scroll to order details on mobile after delay
    if ($(window).width() <= 768) {
        setTimeout(function() {
            if ($('.order-details-summary').length) {
                $('html, body').animate({
                    scrollTop: $('.order-details-summary').offset().top - 100
                }, 1000);
            }
        }, 2000);
    }
    
    // Track order completion for analytics
    if (typeof gtag !== 'undefined' && $('[data-order-id]').length) {
        const orderId = $('[data-order-id]').data('order-id');
        const orderTotal = $('[data-order-total]').data('order-total');
        
        gtag('event', 'purchase', {
            'transaction_id': orderId,
            'value': orderTotal,
            'currency': 'USD'
        });
    }
});
</script>