<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function in_array;
use function is_array;

add_filter( 'render_block', NS . 'render_width_attributes', 10, 2 );
/**
 * Add width attributes to blocks.
 *
 * @param string $content The block content about to be appended.
 * @param array  $block   The full block, including name and attributes.
 *
 * @return string
 */
function render_width_attributes( string $content, array $block ): string {
	$width = $block['attrs']['width'] ?? [];

	if ( ! is_array( $width ) || empty( $width ) ) {
		return $content;
	}

	$dom   = dom( $content );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $content;
	}

	$classes = explode( ' ', $first->getAttribute( 'class' ) );
	$styles  = css_string_to_array( $first->getAttribute( 'style' ) );

	if ( $width['mobile'] ?? null ) {
		$styles['--wp--custom--width--mobile'] = $width['mobile'] ?? '';
	}

	if ( $width['desktop'] ?? null ) {
		$styles['--wp--custom--width--desktop'] = $width['desktop'] ?? '';
	}

	if ( ! in_array( 'has-custom-width', $classes, true ) ) {
		$classes[] = 'has-custom-width';
	}

	unset( $styles['width'] );

	$first->setAttribute( 'style', css_array_to_string( $styles ) );
	$first->setAttribute( 'class', implode( ' ', $classes ) );

	return $dom->saveHTML();
}

