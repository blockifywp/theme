<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/buttons', NS . 'render_buttons_block', 10, 2 );
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
function render_buttons_block( string $html, array $block ): string {
	$margin  = $block['attrs']['style']['spacing']['margin'] ?? [];
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

	if ( $margin || $padding ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {
			$styles = css_string_to_array( $div->getAttribute( 'style' ) );
			$styles = add_shorthand_property( $styles, 'margin', $margin );
			$styles = add_shorthand_property( $styles, 'padding', $padding );

			$div->setAttribute( 'style', css_array_to_string( $styles ) );

			$html = $dom->saveHTML();
		}
	}

	return $html;
}
