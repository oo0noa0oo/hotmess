<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && $product->get_image_id() ) {
	?>
	<div class="woocommerce-product-gallery__thumbnails" role="group" aria-label="<?php esc_attr_e( 'Product thumbnails', 'woocommerce' ); ?>">
		<?php
		foreach ( $attachment_ids as $attachment_id ) {
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
	<?php
}
