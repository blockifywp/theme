<?php

declare( strict_types=1 );

namespace Blockify\Theme;

add_filter( 'woocommerce_enqueue_styles', NS . 'remove_woocommerce_styles' );
/**
 * Dequeue WooCommerce styles.
 *
 * @since 0.9.12
 *
 * @param array $styles Styles.
 *
 * @return array
 */
function remove_woocommerce_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );
	// unset( $enqueue_styles['woocommerce-layout'] );
	// unset( $enqueue_styles['woocommerce-smallscreen'] );

	return $enqueue_styles;
}

