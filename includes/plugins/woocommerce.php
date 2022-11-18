<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'woocommerce_enqueue_styles', NS . 'remove_woocommerce_styles' );
/**
 * Dequeue WooCommerce styles.
 *
 * @since 0.9.12
 *
 * @param array $styles WooCommerce stylesheets to enqueue.
 *
 * @return array
 */
function remove_woocommerce_styles( $styles ) {
	unset( $styles['woocommerce-general'] );

	return $styles;
}

