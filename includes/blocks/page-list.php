<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/page-list', NS . 'render_page_list_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_page_list_block( string $html, array $block ): string {
	$block_gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( ! $block_gap ) {
		return $html;
	}

	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );

	if ( ! $ul ) {
		return $html;
	}

	$styles = css_string_to_array( $ul->getAttribute( 'style' ) );

	$styles['--wp--style--block-gap'] = format_custom_property( $block_gap );

	$ul->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}
