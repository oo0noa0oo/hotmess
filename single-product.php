<?php get_header(); ?>

<div class="single-product-container">
    <?php while (have_posts()) : the_post(); ?>
        <div class="product-hero">
            <div class="product-gallery">
                <?php do_action('woocommerce_before_single_product_summary'); ?>
            </div>
            <div class="product-info">
                <h1><?php the_title(); ?></h1>

                <?php
                // Get ACF heat level and scoville for heat intensity display
                global $product;
                $heat_level = get_field('heat_level');
                $scoville = get_field('scoville_rating');

                // Calculate heat intensity
                if ($scoville) {
                    $heat_intensity = get_heat_intensity_from_scoville($scoville);
                } else {
                    $heat_intensity = get_heat_intensity_level($heat_level);
                }
                ?>

                <?php if ($heat_level || $scoville) : ?>
                <div class="heat-intensity-section visible">
                    <label>HEAT INTENSITY</label>
                    <div class="heat-intensity-display">
                        <div class="heat-dots">
                            <?php echo display_heat_intensity($heat_intensity); ?>
                        </div>
                        <?php if ($scoville) : ?>
                            <div class="scoville-display">
                                <i class="fas fa-fire"></i> <?php echo number_format($scoville); ?> SCOVILLE
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="sticky-wrapper">
                    <div class="product-price-wrapper">
                        <?php woocommerce_template_single_price(); ?>
                    </div>

                    <div class="product-cart-wrapper">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                </div>

                <div class="product-description">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        
        <div class="product-details-tabs">
            <?php do_action('woocommerce_output_product_data_tabs'); ?>
        </div>
        
        <div class="related-products">
            <?php do_action('woocommerce_output_related_products_args'); ?>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>