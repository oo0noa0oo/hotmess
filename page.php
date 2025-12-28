<?php
/**
 * Template Name: Page with Checkout Styling
 * 
 * The template for displaying pages with checkout page styling
 * Save this as: page-styled.php in your theme root directory
 */

get_header(); ?>

<main class="checkout-main">
    <div class="checkout-container">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <div class="checkout-header">
                <h1><?php the_title(); ?></h1>
                <?php if (get_the_excerpt()) : ?>
                    <p><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="checkout-content">
                <div class="woocommerce">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                        
                        <div class="checkout-main">
                            <?php
                            // Display page content
                            the_content();
                            
                            // If comments are open or there's at least one comment, load the comment template
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                            ?>
                        </div>
                        
                    </article>
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </div>
</main>

<style>
.checkout-main {
    min-height: 70vh;
    padding: 2rem 0;
}

.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.checkout-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
    border-bottom: 2px solid var(--color-border, #ddd);
}

.checkout-header h1 {
    font-family: var(--font-title, serif);
    font-size: clamp(2.5rem, 5vw, 4rem);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-foreground, #333);
}

.checkout-header p {
    font-size: 1.25rem;
    color: var(--color-muted-foreground, #666);
    margin: 0;
}
.woocommerce-message[role="alert"] {
	display: none;
	}
.checkout-content {
    background: var(--color-background, #fff);
}

/* Claudia commented out: Unused empty-cart styles - Not used in these templates
/* Empty Cart Styles */
.checkout-empty-cart {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    text-align: center;
}

.empty-cart-content h1 {
    font-family: var(--font-title, serif);
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--color-foreground, #333);
}

.empty-cart-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    color: var(--color-muted-foreground, #666);
}
*/

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
}

.btn-primary {
    background: var(--color-accent, #ff4444);
    color: var(--color-accent-foreground, #fff);
    border-color: var(--color-accent, #ff4444);
}

.btn-primary:hover {
    background: var(--color-foreground, #333);
    border-color: var(--color-foreground, #333);
    color: var(--color-background, #fff);
    transform: translateY(-2px);
}

/* WooCommerce Checkout Styling */
.woocommerce-checkout {
    font-family: var(--font-body, sans-serif);
}

.woocommerce-checkout .col2-set {
    display: grid;
    gap: 3rem;
    margin-bottom: 3rem;
}

@media (min-width: 768px) {
    .woocommerce-checkout .col2-set {
        grid-template-columns: 1fr 1fr;
    }
}

.woocommerce-checkout .col-1,
.woocommerce-checkout .col-2 {
    background: var(--color-muted, #f9f9f9);
    padding: 2rem;
    border: 2px solid var(--color-border, #ddd);
	margin: 0 0 1.5rem 0;
}

.woocommerce-checkout h3 {
    font-family: var(--font-heading, serif);
    font-size: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--color-border, #ddd);
}

.woocommerce-checkout .form-row {
    margin-bottom: 1.5rem;
}

.woocommerce-checkout .form-row label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--color-foreground, #333);
    font-family: var(--font-heading, serif);
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
}

.woocommerce-checkout .form-row input,
.woocommerce-checkout .form-row select,
.woocommerce-checkout .form-row textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--color-border, #ddd);
    border-radius: 0;
    font-family: var(--font-body, sans-serif);
    font-size: 1rem;
    background: var(--color-background, #fff);
    transition: border-color 0.3s ease;
}

.woocommerce-checkout .form-row input:focus,
.woocommerce-checkout .form-row select:focus,
.woocommerce-checkout .form-row textarea:focus {
    outline: none;
    border-color: var(--color-accent, #ff4444);
    box-shadow: 0 0 0 3px rgba(255, 68, 68, 0.1);
}

/* Order Review */
#order_review_heading {
    font-family: var(--font-heading, serif);
    font-size: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2rem;
    text-align: center;
}

.woocommerce-checkout-review-order {
    background: var(--color-muted, #f9f9f9);
    padding: 2rem;
    border: 2px solid var(--color-border, #ddd);
    margin-bottom: 2rem;
}

.woocommerce-checkout-review-order-table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: collapse;
}

.woocommerce-checkout-review-order-table th,
.woocommerce-checkout-review-order-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--color-border, #ddd);
    font-family: var(--font-body, sans-serif);
}

.woocommerce-checkout-review-order-table .product-name, tr.cart-subtotal th, tr.woocommerce-shipping-totals th {
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.woocommerce-checkout-review-order-table .product-total, .cart-subtotal td:nth-child(2), .woocommerce-shipping-totals td:nth-child(2), tr.order-total td:nth-child(2){
    font-family: var(--font-heading, serif);
    font-weight: 600;
    text-align: right;
}

.order-total {
    font-size: 1.25rem;
    font-weight: bold;
}
.woocommerce-shipping-methods li {list-style: none;}
.order-total th,
.order-total td {
    border-top: 2px solid var(--color-border, #ddd);
    border-bottom: none !important;
    font-family: var(--font-heading, serif);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: var(--color-background, #fff);
}

/* Payment Section */
#payment {
    background: var(--color-background, #fff);
    border: 2px solid var(--color-border, #ddd);
    padding: 2rem;
    margin-top: 2rem;
}

#payment .payment_methods {
    list-style: none;
    margin: 0;
    padding: 1.5rem;
}

#payment .payment_method {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid var(--color-border, #ddd);
    background: var(--color-muted, #f9f9f9);
}

#payment .payment_method input[type="radio"] {
    margin-right: 0.5rem;
}

#payment .payment_method label {
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font-heading, serif);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Place Order Button */
#place_order {
    width: 100%;
    padding: 1.5rem 3rem !important;
    font-size: 1.25rem !important;
    font-family: var(--font-heading, serif) !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    margin-top: 2rem;
    background: var(--color-accent, #ff4444) !important;
    border: 2px solid var(--color-accent, #ff4444) !important;
    color: var(--color-accent-foreground, #fff) !important;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 0;
}

#place_order:hover {
    background: var(--color-foreground, #333) !important;
    border-color: var(--color-foreground, #333) !important;
    transform: translateY(-2px);
}

#place_order:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Terms and Conditions */
.woocommerce-terms-and-conditions-wrapper {
    border: 2px solid var(--color-border, #ddd);
    padding: 1.5rem;
    background: var(--color-muted, #f9f9f9);
    margin: 2rem 0;
}

.woocommerce-terms-and-conditions {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid var(--color-border, #ddd);
    padding: 1rem;
    background: var(--color-background, #fff);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.woocommerce-privacy-policy-text {
    font-size: 0.9rem;
    color: var(--color-muted-foreground, #666);
    margin-top: 1rem;
}

/* Error Messages */
.woocommerce .woocommerce-error,
.woocommerce .woocommerce-message,
.woocommerce .woocommerce-info {
    margin-bottom: 2rem;
    padding: 1rem;
    border: 2px solid var(--color-border, #ddd);
    font-family: var(--font-body, sans-serif);
}

.woocommerce .woocommerce-error {
    background: #fef2f2;
    border-color: #ef4444;
    color: #dc2626;
}

.woocommerce .woocommerce-message {
    background: #dcfce7;
    border-color: #22c55e;
    color: #15803d;
}

.woocommerce .woocommerce-info {
    background: #dbeafe;
    border-color: #3b82f6;
    color: #1d4ed8;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .checkout-container {
        padding: 0 1rem;
    }
    
    .checkout-header {
        padding: 1rem 0;
        margin-bottom: 2rem;
    }
    
    .checkout-header h1 {
        font-size: 2.5rem;
    }
    
    .woocommerce-checkout .col2-set {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .woocommerce-checkout .col-1,
    .woocommerce-checkout .col-2 {
        padding: 1rem;
    }
    
    .woocommerce-checkout-review-order,
    #payment {
        padding: 1rem;
    }
    
    #place_order {
        padding: 1.25rem 2rem !important;
        font-size: 1.1rem !important;
    }
}

/* Loading States */
.woocommerce-checkout.processing {
    opacity: 0.6;
    pointer-events: none;
}

.woocommerce-checkout.processing::after {
    content: "Processing...";
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: var(--color-background, #fff);
    padding: 2rem 3rem;
    border: 2px solid var(--color-border, #ddd);
    font-family: var(--font-heading, serif);
    font-size: 1.25rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    z-index: 9999;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
</style>
<?php get_footer(); ?>