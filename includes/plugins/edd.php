<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;

add_filter( 'edd_get_option_disable_styles', fn() => true );

add_filter( 'render_block_edd/receipt', NS . 'render_receipt_block', 10, 2 );
/**
 * Render the receipt block.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_receipt_block( string $html, array $block ): string {
	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );

	if ( $div ) {
		$classes   = explode( ' ', $div->getAttribute( 'class' ) );
		$classes[] = 'is-style-surface';

		$div->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

add_filter( 'edd_checkout_button_purchase', NS . 'purchase_button_class' );
/**
 * Add wp-element-button class to the checkout page purchase button.
 *
 * @since 1.0.1
 *
 * @param string $input The button HTML.
 *
 * @return string
 */
function purchase_button_class( string $input ) : string {
	return str_replace(
		'class="edd-submit',
		'class="edd-submit wp-element-button',
		$input
	);
}

add_filter( 'edd_purchase_link_args', NS . 'add_class_to_edd_purchase_link', 11 );
/**
 * Add wp-element-button class to the EDD purchase link.
 *
 * @since 1.0.1
 *
 * @param array $args The arguments.
 *
 * @return array
 */
function add_class_to_edd_purchase_link( array $args ): array {
	$args['class'] = 'wp-element-button';

	return $args;
}
