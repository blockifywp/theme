<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_contains;

add_filter( 'render_block_core/query', NS . 'render_query_block', 10, 2 );
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
function render_query_block( string $html, array $block ): string {
	$block_gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( $block_gap ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( ! $div ) {
			return $html;
		}

		$styles = css_string_to_array( $div->getAttribute( 'style' ) );

		$styles['--wp--style--block-gap'] = format_custom_property( $block_gap );

		$div->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	$columns = $block['attrs']['displayLayout']['columns'] ?? null;

	if ( $columns && str_contains( $html, 'nowrap' ) ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {
			$styles              = css_string_to_array( $div->getAttribute( 'style' ) );
			$styles['--columns'] = $columns;
			$div->setAttribute( 'style', css_array_to_string( $styles ) );

			$html = $dom->saveHTML();
		}
	}

	return $html;
}
