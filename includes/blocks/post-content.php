<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function method_exists;

add_filter( 'render_block_core/post-content', NS . 'render_post_content_block', 10, 2 );
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
function render_post_content_block( string $html, array $block ): string {
	$margin  = $block['attrs']['style']['spacing']['margin'] ?? [];
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

	if ( ! empty( $margin ) || ! empty( $padding ) ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( ! $div || ! method_exists( $div, 'getAttribute' ) ) {
			return $html;
		}

		$styles   = [];
		$original = $div->getAttribute( 'style' );

		if ( $original ) {
			foreach ( explode( ';', $original ) as $rule ) {
				$styles[] = $rule;
			}
		}

		foreach ( $margin as $key => $value ) {
			$styles[] = "margin-$key:" . format_custom_property( $value );
		}

		foreach ( $padding as $key => $value ) {
			$styles[] = "padding-$key:" . format_custom_property( $value );
		}

		$div->setAttribute( 'style', implode( ';', $styles ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

