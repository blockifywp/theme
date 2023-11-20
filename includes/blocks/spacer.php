<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/spacer', NS . 'render_spacer_block', 11, 2 );
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
function render_spacer_block( string $html, array $block ): string {
	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );

	if ( ! $div ) {
		return $html;
	}

	$div_styles = css_string_to_array( $div->getAttribute( 'style' ) );

	$margin = $block['attrs']['style']['spacing']['margin'] ?? '';

	if ( $margin ) {
		$div_styles = add_shorthand_property( $div_styles, 'margin', $margin );
	}

	$width            = $block['attrs']['width'] ?? '';
	$responsive_width = $block['attrs']['style']['width']['all'] ?? '';

	if ( $width && $responsive_width ) {
		unset ( $div_styles['width'] );
	}

	$div->setAttribute( 'style', css_array_to_string( $div_styles ) );

	return $dom->saveHTML();
}
