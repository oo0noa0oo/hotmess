<?php
/**
 * Single Product Image - Stacked Version (No Gallery/Slider)
 *
 * This template displays all product images stacked vertically
 * with no JavaScript gallery or slider functionality.
 *
 * @package WooCommerce\Templates
 * @version 9.7.0 (customized)
 */

defined( 'ABSPATH' ) || exit;

global $product;

$post_thumbnail_id = $product->get_image_id();
$attachment_ids = $product->get_gallery_image_ids();

// Get ACF heat level for badge
$heat_level = get_field('heat_level');
?>

<div class="woocommerce-product-gallery woocommerce-product-gallery--stacked">
	<div class="woocommerce-product-gallery__wrapper">
		<?php
		// Display main product image with heat badge
		if ( $post_thumbnail_id ) {
			$main_image = wp_get_attachment_image(
				$post_thumbnail_id,
				'woocommerce_single',
				false,
				array(
					'class' => 'wp-post-image product-image-stacked',
					'alt'   => get_the_title()
				)
			);
			echo '<div class="woocommerce-product-gallery__image product-gallery__image--first">';
			echo $main_image;

			// Add heat badge on first image (top right corner)
			if ( $heat_level ) {
				echo '<div class="product-heat-badge heat-badge heat-' . esc_attr( strtolower( $heat_level ) ) . '">';
				echo esc_html( strtoupper( $heat_level ) );
				echo '</div>';
			}

			echo '</div>';
		} else {
			// Placeholder if no image
			echo '<div class="woocommerce-product-gallery__image">';
			echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			echo '</div>';
		}

		// Display all gallery images stacked below main image
		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$gallery_image = wp_get_attachment_image(
					$attachment_id,
					'woocommerce_single',
					false,
					array(
						'class' => 'product-image-stacked product-gallery-image',
						'alt'   => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true )
					)
				);
				echo '<div class="woocommerce-product-gallery__image">' . $gallery_image . '</div>';
			}
		}
		?>
	</div>
</div>
