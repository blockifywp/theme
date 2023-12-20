<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_filter;

add_filter( 'render_block_core/code', NS . 'render_code_block', 12, 3 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string   $html   Block HTML.
 * @param array    $block  Block data.
 * @param WP_Block $object Block object.
 *
 * @return string
 */
function render_code_block( string $html, array $block, WP_Block $object ): string {
	$attrs  = $block['attrs'] ?? [];
	$margin = $attrs['style']['spacing']['margin'] ?? '';

	if ( $margin ) {
		$dom = dom( $html );
		$pre = get_dom_element( 'pre', $dom );

		if ( $pre ) {
			$pre_styles = css_string_to_array( $pre->getAttribute( 'style' ) );
			$pre_styles = add_shorthand_property( $pre_styles, 'margin', $margin );

			$pre->setAttribute( 'style', css_array_to_string( $pre_styles ) );
		}

		$html = $dom->saveHTML();
	}

	return $html;
}
