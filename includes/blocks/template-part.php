<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function get_the_ID;
use function implode;
use function method_exists;
use function trim;

add_filter( 'render_block_core/template-part', NS . 'render_template_part_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_template_part_block( string $content, array $block ): string {
	$slug    = $block['attrs']['slug'] ?? '';
	$post_id = get_the_ID();

	// Hides header and footer for pattern previews on wp.org.
	if ( $post_id && is_pattern_preview( $post_id ) && ( $slug === 'header' || $slug === 'footer' ) ) {
		return '';
	}

	$dom = dom( $content );

	/**
	 * @var $first DOMElement
	 */
	$first = $dom->firstChild;

	if ( ! method_exists( $first, 'getAttribute' ) ) {
		return $content;
	}

	$css = $first->getAttribute( 'style' );

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

	if ( ! $css ) {
		$first->removeAttribute( 'style' );
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

	$attrs = $block['attrs'];
	$color = $attrs['style']['color'] ?? [];

	if ( isset( $color['background'] ) ) {
		$styles['background'] = $color['background'];
	}

	if ( isset( $attrs['backgroundColor'] ) ) {
		$styles['background'] = 'var(--wp--preset--color--' . $attrs['backgroundColor'] . ')';
	}

	if ( isset( $color['text'] ) ) {
		$styles['color'] = $color['text'];
	}

	if ( isset( $attrs['textColor'] ) ) {
		$styles['color'] = 'var(--wp--preset--color--' . $attrs['textColor'] . ')';
	}

	$classes[] = 'wp-site-' . $first->tagName;
	$classes[] = isset( $attrs['boxShadow']['useDefault'] ) && $attrs['boxShadow']['useDefault'] ? 'has-box-shadow' : '';

	$classes = implode( ' ', [
		...explode( ' ', $first->getAttribute( 'class' ) ),
		...$classes,
	] );

	$first->setAttribute( 'class', trim( $classes ) );
	$first->setAttribute( 'style', css_array_to_string( $styles ) );

	if ( ! $first->getAttribute( 'style' ) ) {
		$first->removeAttribute( 'style' );
	}

	return $dom->saveHTML();
}

