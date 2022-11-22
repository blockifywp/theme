<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block_core/columns', NS . 'render_columns_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_columns_block( string $html, array $block ): string {
	$class      = 'is-stacked-on-mobile';
	$is_stacked = $block['attrs']['stackedOnMobile'] ?? null;

	if ( $is_stacked && $block['attrs']['isStackedOnMobile'] === false ) {
		$class = 'is-not-stacked-on-mobile';
	}

	if ( $class === 'is-stacked-on-mobile' ) {
		$html = str_replace( 'wp-block-columns', 'wp-block-columns is-stacked-on-mobile', $html );
	}

	return $html;
}
