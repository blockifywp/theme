<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/query', NS . 'render_query_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_query_block( string $content, array $block ): string {
	$block_gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	if ( $block_gap ) {
		$dom    = dom( $content );
		$div    = get_dom_element( 'div', $dom );
		$styles = css_string_to_array( $div->getAttribute( 'style' ) );

		$styles['--wp--style--block-gap'] = format_custom_property( $block_gap );

		$div->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
