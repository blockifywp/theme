<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function in_array;
use function method_exists;

add_filter( 'render_block', NS . 'render_box_shadow', 10, 2 );
/**
 * Adds box shadow support to dynamic core blocks.
 *
 * @since 0.7.1
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_box_shadow( string $content, array $block ): string {
	$box_shadow = $block['attrs']['boxShadow'] ?? null;

	if ( ! $box_shadow ) {
		return $content;
	}

	$dom   = dom( $content );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $content;
	}

	if ( ! method_exists( $first, 'getAttribute' ) ) {
		return $content;
	}

	$styles = css_string_to_array( $first->getAttribute( 'style' ) );

	if ( isset( $box_shadow['inset'] ) && $box_shadow['inset'] ) {
		$styles['--wp--custom--box-shadow--inset'] = 'inset';
	}

	if ( $box_shadow['x'] ?? null ) {
		$styles['--wp--custom--box-shadow--x'] = $box_shadow['x'] . 'px';
	}

	if ( $box_shadow['y'] ?? null ) {
		$styles['--wp--custom--box-shadow--y'] = $box_shadow['y'] . 'px';
	}

	if ( $box_shadow['blur'] ?? null ) {
		$styles['--wp--custom--box-shadow--blur'] = $box_shadow['blur'] . 'px';
	}

	if ( $box_shadow['spread'] ?? null ) {
		$styles['--wp--custom--box-shadow--spread'] = $box_shadow['spread'] . 'px';
	}

	if ( $box_shadow['color'] ?? null ) {
		$styles['--wp--custom--box-shadow--color'] = $box_shadow['color'];
	}

	if ( $styles ) {
		$first->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$classes = explode( ' ', $first->getAttribute( 'class' ) );

	if ( ( $box_shadow['useDefault'] ?? $styles ) ) {
		if ( ! in_array( 'has-box-shadow', $classes, true ) ) {
			$classes[] = 'has-box-shadow';
		}

		$first->setAttribute( 'class', implode( ' ', $classes ) );
	}

	return $dom->saveHTML();
}

