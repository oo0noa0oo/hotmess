<?php 
/**
 * Front Page Template
 * This should ONLY show on the homepage, not other pages
 */

// IMPORTANT: Only show this template on the actual front page
if (!is_front_page()) {
    // If this isn't the front page, load the appropriate template
    if (is_page()) {
        // For pages, try to load page template
        $page_template = get_page_template_slug();
        if ($page_template) {
            get_template_part('page-templates/' . str_replace('.php', '', $page_template));
        } else {
            // Load default page template
            include(get_template_directory() . '/page.php');
        }
    } else {
        // For other content types, load index
        include(get_template_directory() . '/index.php');
    }
    return;
}

// If we get here, this IS the front page, so show the homepage content
get_header(); ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-grid">
            <div class="hero-content">
                <?php 
                // Get ACF fields - choose one approach:
                
                // Option 1: From the homepage itself
                $hero_badge = get_field('hero_badge') ?: 'PREMIUM HOT SAUCE';
                $hero_title = get_field('hero_title') ?: 'Ignite Your <span class="highlight">Taste</span>';
                $hero_description = get_field('hero_description') ?: 'EXPERIENCE THE PERFECT FUSION OF HEAT AND FLAVOR WITH OUR CAREFULLY CRAFTED HOT SAUCE COLLECTION. EACH BOTTLE DELIVERS AN UNFORGETTABLE TASTE ADVENTURE.';
                $hero_image = get_field('hero_image');
                $hero_stats = get_field('hero_stats');
                ?>
                
                <div class="hero-badge">
                    <span><?php echo $hero_badge; ?></span>
                </div>
                <div class="hero-title">
                    <h1><?php echo $hero_title; ?></h1>
                </div>
                <p class="hero-description">
                    <?php echo $hero_description; ?>
                </p>
                <div class="hero-buttons">
                    <a href="#products" class="btn btn-primary">SHOP COLLECTION</a>
                    <a href="#about" class="btn btn-outline">OUR STORY</a>
                </div>
                
                <!-- Hero Stats Section -->
                <div class="hero-stats">
                    <?php 
                    if ($hero_stats && is_array($hero_stats) && !empty($hero_stats)) : 
                        foreach ($hero_stats as $stat) : ?>
                            <div class="hero-stat">
                                <div class="hero-stat-number"><?php echo $stat['number']; ?></div>
                                <div class="hero-stat-label"><?php echo $stat['label']; ?></div>
                            </div>
                        <?php endforeach; 
                    else : ?>
                        <!-- Default stats if ACF not configured -->
                        <div class="hero-stat">
                            <div class="hero-stat-number">2<span class="accent">+</span></div>
                            <div class="hero-stat-label">SIGNATURE<br>SAUCES</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number"><span class="accent">100</span>K</div>
                            <div class="hero-stat-label">SCOVILLE<br>RANGE</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-number">100<span class="accent">%</span></div>
                            <div class="hero-stat-label">NATURAL<br>INGREDIENTS</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-container">
                    <?php if ($hero_image) : ?>
                        <img src="<?php echo $hero_image['url']; ?>" alt="<?php echo $hero_image['alt']; ?>" />
                    <?php else : ?>
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODgiIGhlaWdodD0iODgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgc3Ryb2tlPSIjMDAwIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBvcGFjaXR5PSIuMyIgZmlsbD0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIzLjciPjxyZWN0IHg9IjE2IiB5PSIxNiIgd2lkdGg9IjU2IiBoZWlnaHQ9IjU2IiByeD0iNiIvPjxwYXRoIGQ9Im0xNiA1OCAxNi0xOCAzMiAzMiIvPjxjaXJjbGUgY3g9IjUzIiBjeT0iMzUiIHI9IjciLz48L3N2Zz4KCg==" alt="Premium hot sauce bottles" />
                    <?php endif; ?>
                    <div class="hero-badge-new"><span>NEW</span></div>
                    <div class="hero-badge-craft"><span>HAND<br>CRAFTED</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="products-header">
            <?php 
            // Get products section fields
            $products_badge = get_field('products_badge') ?: 'OUR COLLECTION';
            $products_title = get_field('products_title') ?: 'HOT SAUCE<br /><span class="highlight">ARSENAL</span>';
            $products_description = get_field('products_description') ?: 'CRAFTED WITH PASSION AND THE FINEST INGREDIENTS, EACH SAUCE DELIVERS A UNIQUE FLAVOR EXPERIENCE THAT WILL ELEVATE YOUR MEALS TO NEW HEIGHTS.';
            $products_limit = get_field('products_limit') ?: 6;
            ?>
            <div class="products-badge">
                <span><?php echo $products_badge; ?></span>
            </div>
            <h2 class="products-title">
                <?php echo $products_title; ?>
            </h2>
            <p class="products-description">
                <?php echo $products_description; ?>
            </p>
        </div>
        
        <div class="products-grid">
            <?php
            // Display WooCommerce products
            $products = wc_get_products([
                'limit' => $products_limit,
                'status' => 'publish'
            ]);
            
            foreach ($products as $product) :
                $product_id = $product->get_id();
                $product_name = $product->get_name();
                $product_image = wp_get_attachment_image_src($product->get_image_id(), 'large');
                $product_url = get_permalink($product_id);
                
                // Get ACF fields for additional product info
                $heat_level = get_field('heat_level', $product_id);
                $scoville = get_field('scoville_rating', $product_id);
                
                // Get product variations
                if ($product->is_type('variable')) {
                    $variations = $product->get_available_variations();
                    $variation_attributes = $product->get_variation_attributes();
                } else {
                    $variations = false;
                }
            ?>
                <div class="product-card" data-scoville="<?php echo esc_attr($scoville); ?>">
                    <div class="product-image">
                        <?php if ($product_image) : ?>
                            <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php echo esc_attr($product_name); ?>" />
                        <?php else : ?>
                            <div class="product-placeholder">??</div>
                        <?php endif; ?>
                        
                        <div class="product-price">
                            <?php if ($product->is_type('variable')) : ?>
                                FROM <?php echo wc_price($product->get_variation_price('min')); ?>
                            <?php else : ?>
                                <?php echo $product->get_price_html(); ?>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($heat_level) : ?>
                            <div class="product-heat heat-badge heat-<?php echo esc_attr($heat_level); ?>">
                                <?php echo esc_html(strtoupper($heat_level)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-content">
                        <h3 class="product-title"><?php echo esc_html($product_name); ?></h3>
                        <p class="product-description"><?php echo wp_trim_words($product->get_short_description(), 25); ?></p>

                        <?php if ($variations && count($variations) > 0) : ?>
                            <!-- Variable Product with Dropdown -->
                            <form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $product_id; ?>">
                                <div class="product-variations">
                                    <label for="heat-variation-<?php echo $product_id; ?>">HEAT VARIATION</label>
                                    <select id="heat-variation-<?php echo $product_id; ?>" name="attribute_heat-level" data-attribute_name="attribute_heat-level">
                                        <option value="">Choose heat level...</option>
                                        <?php foreach ($variations as $variation) : 
                                            $variation_obj = wc_get_product($variation['variation_id']);
                                            $variation_heat = $variation_obj->get_attribute('heat-level');
                                            $variation_scoville = get_field('scoville_rating', $variation['variation_id']);
                                            $stock_status = $variation_obj->is_in_stock() ? 'in-stock' : 'out-of-stock';

                                            // Calculate heat intensity based on Scoville rating
                                            if ($variation_scoville) {
                                                $heat_intensity = get_heat_intensity_from_scoville($variation_scoville);
                                            } else {
                                                $heat_intensity = get_heat_intensity_level($variation_heat);
                                            }
                                        ?>
                                            <option value="<?php echo esc_attr($variation_heat); ?>" 
                                                    data-variation-id="<?php echo $variation['variation_id']; ?>"
                                                    data-price="<?php echo $variation_obj->get_price(); ?>"
                                                    data-stock="<?php echo $stock_status; ?>"
                                                    data-scoville="<?php echo $variation_scoville; ?>"
                                                    data-heat-intensity="<?php echo $heat_intensity; ?>"
                                                    <?php echo !$variation_obj->is_in_stock() ? 'disabled' : ''; ?>>
                                                <?php 
                                                echo strtoupper($variation_heat);
                                                if ($variation_scoville) {
                                                    echo ' - ' . number_format($variation_scoville) . ' SHU';
                                                }
                                                if (!$variation_obj->is_in_stock()) {
                                                    echo ' (OUT OF STOCK)';
                                                }
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Heat Intensity Display -->
                                <div class="heat-intensity-section hidden" id="heat-intensity-<?php echo $product_id; ?>">
                                    <label>HEAT INTENSITY</label>
                                    <div class="heat-intensity-display">
                                        <div class="heat-dots" id="heat-dots-<?php echo $product_id; ?>">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                        <div class="scoville-display" id="scoville-<?php echo $product_id; ?>">
                                            <!-- Scoville rating will be displayed here by JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="variation_id" class="variation_id" value="">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                                <button type="submit" class="btn btn-primary add-to-cart-btn" disabled>
                                    <i class="fas fa-plus"></i> ADD TO CART
                                </button>
                            </form>
                        <?php else : ?>
                            <!-- Simple Product -->
                            <form class="cart" method="post" enctype='multipart/form-data'>
                                <?php 
                                $simple_scoville = get_field('scoville_rating', $product_id);
                                $simple_heat_level = get_field('heat_level', $product_id);

                                if ($simple_scoville) {
                                    $simple_heat_intensity = get_heat_intensity_from_scoville($simple_scoville);
                                } else {
                                    $simple_heat_intensity = get_heat_intensity_level($simple_heat_level);
                                }
                                ?>

                                <!-- Heat Intensity Display for Simple Products -->
                                <div class="heat-intensity-section visible">
                                    <label>HEAT INTENSITY</label>
                                    <div class="heat-intensity-display">
                                        <div class="heat-dots">
                                            <?php echo display_heat_intensity($simple_heat_intensity); ?> 
                                        </div>
                                        <?php if ($simple_scoville) : ?>
                                            <div class="scoville-display">
                                                <i class="fas fa-fire"></i> <?php echo number_format($simple_scoville); ?> SCOVILLE
                                            </div>
                                        <?php endif; ?>
                                    </div> 
                                </div>

                                <input type="hidden" name="add-to-cart" value="<?php echo $product_id; ?>">

                                <button type="submit" class="btn btn-primary add-to-cart-btn">
                                    <i class="fas fa-plus"></i> ADD TO CART
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-grid">
            <div class="about-content">
                <?php 
                // Get about section fields
                $about_badge = get_field('about_badge') ?: 'OUR STORY';
                $about_title = get_field('about_title') ?: 'Born from<br /><span class="accent">Passion</span>';
                $about_content = get_field('about_content');
                $about_image = get_field('about_image');
                $about_established_year = get_field('about_established_year') ?: '2024';
                ?>
                
                <div class="about-badge">
                    <span><?php echo $about_badge; ?></span>
                </div>
                <h2 class="about-title"><?php echo $about_title; ?></h2>
                <div class="about-text">
                    <?php if ($about_content) : ?>
                        <?php echo $about_content; ?>
                    <?php else : ?>
                        <!-- Default about content -->
                        <p>BORN FROM A PASSION FOR BOLD FLAVORS AND QUALITY INGREDIENTS, OUR HOT SAUCE BRAND REPRESENTS THE PERFECT FUSION OF HEAT AND TASTE.</p>
                        <p>WE SOURCE THE FINEST PEPPERS AND USE TRADITIONAL METHODS COMBINED WITH MODERN TECHNIQUES TO CREATE SAUCES THAT DON'T JUST BRING THE HEAT ó THEY BRING THE FLAVOR.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="about-image">
                <div class="about-image-container">
                    <?php if ($about_image) : ?>
                        <img src="<?php echo $about_image['url']; ?>" alt="<?php echo $about_image['alt']; ?>" />
                    <?php else : ?>
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=500&fit=crop" alt="Hot sauce making process" />
                    <?php endif; ?>
                </div>
                <div class="about-badge-est">
                    <div class="year">Est.</div>
                    <div class="year"><?php echo $about_established_year; ?></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="contact-content">
            <?php 
            // Get contact section fields
            $contact_badge = get_field('contact_badge') ?: 'GET IN TOUCH';
            $contact_title = get_field('contact_title') ?: 'Ready to<br /><span class="accent">Ignite</span>?';
            $contact_description = get_field('contact_description') ?: 'HAVE QUESTIONS ABOUT OUR PRODUCTS OR WANT TO LEARN MORE? WE\'D LOVE TO HEAR FROM YOU.';
            $contact_email = get_field('contact_email') ?: 'hello@hotsauce.co';
            $contact_phone = get_field('contact_phone') ?: '+1 (555) HEAT-NOW';
            ?>
            
            <div class="contact-badge">
                <span><?php echo $contact_badge; ?></span>
            </div>
            <h2 class="contact-title">
                <?php echo $contact_title; ?>
            </h2>
            <p class="contact-description">
                <?php echo $contact_description; ?>
            </p>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:<?php echo antispambot($contact_email); ?>" class="contact-link">
                        <?php echo antispambot($contact_email); ?>
                    </a>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $contact_phone); ?>" class="contact-link">
                        <?php echo $contact_phone; ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Product variation handling with heat intensity - UPDATED FOR AJAX
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize AJAX Add to Cart functionality
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            
            // AJAX Add to Cart for all forms
            $(document).on('submit', 'form.cart', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $button = $form.find('.add-to-cart-btn');
                
                // Don't proceed if button is disabled
                if ($button.prop('disabled')) {
                    return false;
                }
                
                // Get form data
                const formData = new FormData($form[0]);
                
                // For variable products, check if variation is selected
                if ($form.hasClass('variations_form')) {
                    const variationId = $form.find('.variation_id').val();
                    if (!variationId) {
                        alert('Please select a variation before adding to cart.');
                        return false;
                    }
                }
                
                // Add AJAX action
                formData.append('action', 'woocommerce_add_to_cart');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $button.addClass('loading').prop('disabled', true);
                        $button.html('<i class="fas fa-spinner fa-spin"></i> ADDING...');
                    },
                    success: function(response) {
                        // Show success feedback
                        $button.html('<i class="fas fa-check"></i> ADDED!');
                        $button.addClass('added-to-cart');
                        
                        // Update cart count if element exists
                        if ($('.cart-count').length) {
                            const currentCount = parseInt($('.cart-count').text()) || 0;
                            const newCount = currentCount + 1;
                            $('.cart-count').text(newCount).show();
                        }
                        
                        // Reset button after 2 seconds
                        setTimeout(function() {
                            $button.html('<i class="fas fa-plus"></i> ADD TO CART');
                            $button.removeClass('added-to-cart loading').prop('disabled', false);
                        }, 2000);
                        
                        // Trigger WooCommerce added to cart event
                        $('body').trigger('added_to_cart');
                        
                        console.log('Product added to cart successfully!');
                    },
                    error: function(xhr, status, error) {
                        console.error('Add to cart error:', error);
                        alert('Error adding product to cart. Please try again.');
                        
                        $button.html('<i class="fas fa-plus"></i> ADD TO CART');
                        $button.removeClass('loading').prop('disabled', false);
                    }
                });
                
                return false;
            });
        });
    }
    
    // Handle variation dropdowns (existing code)
    document.querySelectorAll('.variations_form select').forEach(function(select) {
        select.addEventListener('change', function() {
            const form = this.closest('.variations_form');
            const productId = form.dataset.product_id;
            const addToCartBtn = form.querySelector('.add-to-cart-btn');
            const heatIntensitySection = document.getElementById('heat-intensity-' + productId);
            const heatDotsContainer = document.getElementById('heat-dots-' + productId);
            const scovilleDisplay = document.getElementById('scoville-' + productId);
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Enable add to cart button
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('disabled');
                
                // Set variation ID
                form.querySelector('.variation_id').value = selectedOption.dataset.variationId;
                
                // Update price if needed
                const price = selectedOption.dataset.price;
                if (price) {
                    const priceDisplay = form.closest('.product-card').querySelector('.product-price');
                    priceDisplay.innerHTML = '<?php echo get_woocommerce_currency_symbol(); ?>' + parseFloat(price).toFixed(2);
                }
                
                // Show heat intensity section
                if (heatIntensitySection) {
                    heatIntensitySection.style.display = 'block';
                }
                
                // Generate heat dots
                if (heatDotsContainer) {
                    const heatIntensity = parseInt(selectedOption.dataset.heatIntensity) || 1;
                    const maxDots = 4;
                    let dotsHTML = '';
                    
                    for (let i = 1; i <= maxDots; i++) {
                        const dotClass = i <= heatIntensity ? 'heat-dot filled' : 'heat-dot empty';
                        dotsHTML += '<span class="' + dotClass + '"></span>';
                    }
                    
                    heatDotsContainer.innerHTML = dotsHTML;
                }
                
                // Show scoville rating
                const scoville = selectedOption.dataset.scoville;
                if (scoville && scovilleDisplay && scoville !== 'undefined' && scoville !== '') {
                    const formattedScoville = parseInt(scoville).toLocaleString();
                    scovilleDisplay.innerHTML = '<i class="fas fa-fire"></i> ' + formattedScoville + ' SCOVILLE';
                    scovilleDisplay.style.display = 'block';
                } else if (scovilleDisplay) {
                    scovilleDisplay.style.display = 'none';
                }
                
                // Check stock status
                if (selectedOption.dataset.stock === 'out-of-stock') {
                    addToCartBtn.disabled = true;
                    addToCartBtn.innerHTML = '<i class="fas fa-times"></i> OUT OF STOCK';
                } else {
                    addToCartBtn.innerHTML = '<i class="fas fa-plus"></i> ADD TO CART';
                }
            } else {
                // Disable add to cart button
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('disabled');
                addToCartBtn.innerHTML = '<i class="fas fa-plus"></i> ADD TO CART';
                
                // Hide heat intensity section
                if (heatIntensitySection) {
                    heatIntensitySection.style.display = 'none';
                }
                
                // Reset variation ID
                form.querySelector('.variation_id').value = '';
            }
        });
    });
});
</script>

<style>
/* Claudia noted: EMBEDDED STYLES FOUND HERE - Consider consolidating these styles to main.css
   This template contains ~100+ lines of CSS that should be in external stylesheet
   Potential duplicates with main.css: .scoville-display, .product-variations, .add-to-cart-btn, etc.
*/

/* Scoville Display Styles */
.scoville-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: var(--font-heading);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-accent);
    margin-top: 0.5rem;
}

.scoville-display i {
    color: var(--color-accent);
    font-size: 1rem;
}

/* Additional styles for new product layout */
.product-variations {
    margin-bottom: 1rem;
}

.product-variations label {
    display: block;
    font-family: var(--font-heading);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
    color: var(--color-foreground);
}

.product-variations select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--color-border);
    background: var(--color-background);
    font-family: var(--font-body);
    font-size: 0.9rem;
    color: var(--color-foreground);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23000' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.product-variations select:focus {
    outline: 2px solid var(--color-accent);
    outline-offset: 2px;
}

.add-to-cart-btn {
    width: 100% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
}

.add-to-cart-btn:disabled,
.add-to-cart-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: var(--color-muted) !important;
    color: var(--color-muted-foreground) !important;
    border-color: var(--color-muted) !important;
}

.product-placeholder {
    width: 100%;
    height: 20rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    background: var(--color-muted);
    border: 2px solid var(--color-border);
}

/* Hero stats should always be visible */
.hero-stats {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 2rem !important;
    padding-top: 2rem !important;
    border-top: 2px solid var(--color-border) !important;
    margin-top: 2rem !important;
}
</style>

<?php get_footer(); ?>