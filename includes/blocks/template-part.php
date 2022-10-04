<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function method_exists;
use function trim;

add_filter( 'render_block_core/template-part', NS . 'render_template_part_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_template_part_block( string $content, array $block ): string {
	$dom    = dom( $content );
	$header = get_dom_element( 'header', $dom );

	if ( ! $header ) {
		return $content;
	}

	if ( ! method_exists( $header, 'getAttribute' ) ) {
		return $content;
	}

	$css                = $header->getAttribute( 'style' );
	$styles             = explode( ';', $css );
	$styles['position'] = $block['attrs']['position'] ?? null;
	$styles['top']      = $block['attrs']['inset']['top'] ?? null;
	$styles['right']    = $block['attrs']['inset']['right'] ?? null;
	$styles['bottom']   = $block['attrs']['inset']['bottom'] ?? null;
	$styles['left']     = $block['attrs']['inset']['left'] ?? null;
	$styles['z-index']  = $block['attrs']['zIndex'] ?? null;

	foreach ( $styles as $property => $value ) {
		$css .= $value ? "$property:$value;" : '';
	}

	if ( ! $header->getAttribute( 'class' ) ) {
		return $content;
	}

	if ( ! $css ) {
		$header->removeAttribute( 'style' );
	}

	$box_shadow = $block['attrs']['boxShadow'] ?? null;
	$classes    = [];

	if ( $box_shadow['useDefault'] ?? $box_shadow['gradient'] ?? $box_shadow['color'] ?? null ) {
		$classes[] = 'has-box-shadow';
	}

	$styles['--wp--custom--box-shadow--color'] = $box_shadow['gradient'] ?? $box_shadow['color'] ?? null;

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

	$styles['--wp--custom--box-shadow--z-index'] = $box_shadow['zIndex'] ?? null;

	$attrs     = $block['attrs'];
	$styles    = add_block_support_color( $styles, $attrs );
	$classes[] = 'wp-site-' . $header->tagName;
	$classes[] = isset( $attrs['boxShadow']['useDefault'] ) && $attrs['boxShadow']['useDefault'] ? 'has-box-shadow' : '';

	$classes = implode(
		' ',
		[
			...explode( ' ', $header->getAttribute( 'class' ) ),
			...$classes,
		]
	);

	$header->setAttribute( 'class', trim( $classes ) );
	$header->setAttribute( 'style', css_array_to_string( $styles ) );

	if ( ! $header->getAttribute( 'style' ) ) {
		$header->removeAttribute( 'style' );
	}

	return $dom->saveHTML();
}

