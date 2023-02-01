<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block_core/post-author', NS . 'render_post_author_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_post_author_block( string $html, array $block ): string {
	$dom    = dom( $html );
	$div    = get_dom_element( 'div', $dom );
	$styles = [];

	if ( ! $div ) {
		return $html;
	}

	$style = $div->getAttribute( 'style' );

	if ( $block['attrs']['style']['border'] ?? null ) {
		$styles['border-width']  = $block['attrs']['style']['border']['width'] ?? null;
		$styles['border-style']  = $block['attrs']['style']['border']['style'] ?? null;
		$styles['border-color']  = $block['attrs']['style']['border']['color'] ?? null;
		$styles['border-radius'] = $block['attrs']['style']['border']['radius'] ?? null;
	}

	$div->setAttribute(
		'style',
		( $style ? $style . ';' : '' ) . css_array_to_string( $styles )
	);

	return str_replace(
		[ '<p ', '</p>' ],
		[ '<span ', '</span>' ],
		$dom->saveHTML()
	);
}

