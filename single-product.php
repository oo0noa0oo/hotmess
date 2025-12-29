<?php get_header(); ?>

<div class="single-product-container">
    <?php while (have_posts()) : the_post(); ?>
        <div class="product-hero">
            <div class="product-gallery">
                <?php do_action('woocommerce_before_single_product_summary'); ?>
            </div>
            <div class="product-info">
                <h1 class="product-title"><?php the_title(); ?></h1>
                
                <?php 
                $heat_level = get_field('heat_level');
                $scoville = get_field('scoville_rating');
                ?>
                
                <div class="product-meta">
                    <?php if ($heat_level) : ?>
                        <span class="heat-badge heat-<?php echo strtolower($heat_level); ?>">
                            <?php echo $heat_level; ?> HEAT
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($scoville) : ?>
                        <span class="scoville-rating">
                            <?php echo number_format($scoville); ?> SHU
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <?php the_content(); ?>
                </div>
                
                <?php do_action('woocommerce_single_product_summary'); ?>
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