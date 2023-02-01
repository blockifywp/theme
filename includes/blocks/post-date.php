<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/post-date', NS . 'render_post_date', 10, 2 );
/**
 * Adds block supports to the core post date block.
 *
 * @since 0.0.1
 *
 * @param string $html The block content.
 * @param array  $block   The block.
 *
 * @return string
 */
function render_post_date( string $html, array $block ): string {
	$margin = $block['attrs']['style']['spacing']['margin'] ?? null;

	if ( $margin ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( ! $div ) {
			return $html;
		}

		$styles = [
			'margin-top'    => $margin['top'] ?? null,
			'margin-right'  => $margin['right'] ?? null,
			'margin-bottom' => $margin['bottom'] ?? null,
			'margin-left'   => $margin['left'] ?? null,
		];

		$div->setAttribute( 'style', css_array_to_string( $styles ) . $div->getAttribute( 'style' ) );

		$html = $dom->saveHTML();
	}

	return $html;
}
