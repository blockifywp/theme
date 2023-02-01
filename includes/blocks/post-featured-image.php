<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function in_array;

add_filter( 'render_block_core/post-featured-image', NS . 'render_featured_image_block', 10, 2 );
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
function render_featured_image_block( string $html, array $block ): string {
	if ( ! $html ) {
		$html = render_image_placeholder( $html, $block['attrs'] );
	}

	$shadow = $block['attrs']['style']['boxShadow'] ?? null;

	if ( $shadow ) {
		$dom    = dom( $html );
		$figure = get_dom_element( 'figure', $dom );
		$img    = get_dom_element( 'img', $figure );

		if ( ! $img ) {
			return $html;
		}

		$styles = css_string_to_array( $img->getAttribute( 'style' ) );

		foreach ( $shadow as $property => $value ) {
			if ( ! $value ) {
				continue;
			}

			$px = in_array( $property, [ 'x', 'y', 'blur', 'spread' ], true ) ? 'px' : '';

			$styles[ '--wp--custom--box-shadow--' . $property ] = $value . $px;
		}

		if ( ! isset( $styles['--wp--custom--box-shadow--inset'] ) ) {
			$styles['--wp--custom--box-shadow--inset'] = '';
		}

		$img->setAttribute( 'style', css_array_to_string( $styles ) );

		$classes   = explode( ' ', $img->getAttribute( 'class' ) );
		$classes[] = 'has-box-shadow';

		$img->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	return $html;
}
