<?php
/**
 * Archive Product Template
 * 
 * Hot Sauce Co minimal shop page - redirects to homepage since products are featured there
 * This template provides a fallback for users who access the shop URL directly
 */

get_header(); ?>
<main>
<section class="shop-redirect-hero">
	<div class="shop-hero-content-wrapper">
		<div class="shop-hero-content">
			<h1 class="products-title">the hotmess shop</h1>
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
                $product_image = wp_get_attachment_image_src($product->get_image_id(), 'medium');
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
</main>
<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );


get_footer();