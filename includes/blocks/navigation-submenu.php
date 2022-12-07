<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function implode;

add_filter( 'render_block_core/navigation-submenu', NS . 'render_navigation_submenu_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @todo  Doesn't work.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_navigation_submenu_block( string $html, array $block ): string {
	$dom     = dom( $html );
	$attrs   = $block['attrs'] ?? [];
	$style   = $attrs['style'] ?? [];
	$spacing = $style['spacing'] ?? [];
	$padding = $spacing['padding'] ?? [];
	$margin  = $spacing['margin'] ?? [];
	$color   = $style['color'] ?? [];
	$styles  = [];

	if ( isset( $color['background'] ) ) {
		$styles['--wp--custom--submenu--background'] = $color['background'];
	}

	if ( isset( $attrs['backgroundColor'] ) ) {
		$styles['--wp--custom--submenu--background'] = 'var(--wp--preset--color--' . $attrs['backgroundColor'] . ')';
	}

	if ( isset( $color['text'] ) ) {
		$styles['--wp--custom--submenu--color'] = $color['text'];
	}

	if ( isset( $attrs['textColor'] ) ) {
		$styles['--wp--custom--submenu--color'] = 'var(--wp--preset--color--' . $attrs['textColor'] . ')';
	}

	$padding = implode(
		' ',
		[
			$padding['top'] ?? 0,
			$padding['right'] ?? 0,
			$padding['bottom'] ?? 0,
			$padding['left'] ?? 0,
		]
	);

	if ( $padding !== '0 0 0 0' ) {
		$styles['--wp--custom--submenu--padding'] = format_custom_property( $padding );
	}

	$margin = implode(
		' ',
		[
			$margin['top'] ?? 0,
			$margin['right'] ?? 0,
			$margin['bottom'] ?? 0,
			$margin['left'] ?? 0,
		]
	);

	if ( $margin !== '0 0 0 0' ) {
		$styles['--wp--custom--submenu--margin'] = format_custom_property( $margin );
	}

	$block_gap = $spacing['blockGap'] ?? null;

	if ( $block_gap ) {
		$styles['--wp--custom--submenu--gap'] = format_custom_property( $block_gap );
	}

	$submenu = get_dom_element( 'ul', $dom );

	if ( ! $submenu ) {
		return $html;
	}

	$submenu_style = $submenu->getAttribute( 'style' );
	$css           = $submenu_style ? $submenu_style . ';' : '';

	foreach ( $styles as $property => $value ) {
		$css .= $value ? "$property:$value;" : '';
	}

	$submenu->setAttribute( 'style', $css );

	return $dom->saveHTML();
}
