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
