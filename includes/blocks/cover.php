<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function is_null;

add_filter( 'render_block_core/cover', NS . 'render_cover_block', 10, 2 );
/**
 * Renders the cover block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_cover_block( string $html, array $block ): string {
	$padding = $block['attrs']['style']['spacing']['padding'] ?? null;
	$zIndex  = $block['attrs']['style']['zIndex']['all'] ?? null;
	$url     = $block['attrs']['url'] ?? null;
	$dom     = dom( $html );
	$div     = get_dom_element( 'div', $dom );

	if ( ! $div ) {
		return $html;
	}

	if ( ! $url ) {
		$imported = $dom->importNode( get_placeholder_image( $dom ), true );
		$svg      = dom_element( $imported );

		$classes   = [];
		$classes[] = 'wp-block-cover__image-background';

		$svg->setAttribute( 'class', implode( ' ', $classes ) );
	}

	$styles = css_string_to_array( $div->getAttribute( 'style' ) );

	foreach ( [ 'top', 'right', 'bottom', 'left' ] as $side ) {
		if ( ! isset( $padding[ $side ] ) ) {
			continue;
		}

		$styles["padding-{$side}"] = format_custom_property( (string) $padding[ $side ] );
	}

	if ( ! is_null( $zIndex ) ) {
		$styles['--z-index'] = format_custom_property( $zIndex );
	}

	$div->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}
