<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMDocument;
use DOMElement;
use function add_action;
use function add_filter;
use function defined;
use function explode;
use function get_the_block_template_html;
use function get_the_content;
use function implode;
use function in_array;
use function is_array;
use function libxml_clear_errors;
use function libxml_use_internal_errors;
use function mb_convert_encoding;
use function str_contains;
use function str_replace;
use function wp_add_inline_style;
use function wp_get_global_settings;
use function wp_list_pluck;

add_filter( 'render_block', NS . 'convert_spacing_to_classes', 10, 2 );
/**
 * Converts inline spacing styles to utility classes.
 *
 * @since 1.0.0
 *
 * @param string $content Block HTML content.
 * @param array  $block   Block attributes.
 *
 * @return string
 */
function convert_spacing_to_classes( string $content, array $block ): string {
	if ( ! $content ) {
		return $content;
	}

	$sizes = wp_get_global_settings()['spacing']['spacingSizes']['theme'] ?? [];

	if ( ! $sizes ) {
		return $content;
	}

	$slugs   = wp_list_pluck( $sizes, 'slug' );
	$spacing = $block['attrs']['style']['spacing'] ?? null;

	if ( ! $spacing ) {
		return $content;
	}

	$dom     = dom( $content );
	$element = get_dom_element( '*', $dom );
	$classes = explode( ' ', $element->getAttribute( 'class' ) );
	$prefix  = 'var:preset|spacing|';

	foreach ( $spacing as $type => $sides ) {
		$type = str_replace( 'blockGap', 'gap', $type );

		if ( ! is_array( $sides ) ) {
			$size = str_replace( $prefix, '', (string) $sides );

			if ( ! in_array( $size, $slugs, true ) ) {
				continue;
			}

			$class     = "has-{$type}-{$size}";
			$classes[] = $class;

			continue;
		}

		foreach ( $sides as $side => $size ) {
			$size = str_replace( $prefix, '', $size );

			if ( ! in_array( $size, $slugs, true ) ) {
				continue;
			}

			if ( $type === 'gap' ) {
				$side = $side === 'top' ? 'vertical' : 'horizontal';
			}

			$class = "has-{$type}-{$side}-{$size}";

			$classes[] = $class;
		}
	}

	$styles = explode(
		';',
		$element->getAttribute( 'style' )
	);

	foreach ( $styles as $index => $style ) {
		if ( ! $style || str_contains( $style, 'preset--spacing' ) ) {
			unset( $styles[ $index ] );
		}
	}

	$element->setAttribute( 'style', implode( ';', $styles ) );
	$element->setAttribute( 'class', implode( ' ', $classes ) );

	return $dom->saveHTML();
}

add_action( 'wp_enqueue_scripts', NS . 'add_spacing_utility_classes', 11 );
/**
 * Conditionally adds spacing scale utility classes if used on a page.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_spacing_utility_classes(): void {
	$sizes = wp_get_global_settings()['spacing']['spacingSizes']['theme'] ?? [];

	if ( ! $sizes ) {
		return;
	}

	$styles = [];
	$css    = '';

	foreach ( $sizes as $size ) {
		$slug = $size['slug'] ?? '';

		if ( ! $slug ) {
			continue;
		}

		$styles[ "padding-{$slug}" ]        = 'padding';
		$styles[ "padding-top-{$slug}" ]    = 'padding-top';
		$styles[ "padding-right-{$slug}" ]  = 'padding-right';
		$styles[ "padding-bottom-{$slug}" ] = 'padding-bottom';
		$styles[ "padding-left-{$slug}" ]   = 'padding-left';
		$styles[ "margin-{$slug}" ]         = 'margin';
		$styles[ "margin-top-{$slug}" ]     = 'margin-top';
		$styles[ "margin-right-{$slug}" ]   = 'margin-right';
		$styles[ "margin-bottom-{$slug}" ]  = 'margin-bottom';
		$styles[ "margin-left-{$slug}" ]    = 'margin-left';
		$styles[ "gap-{$slug}" ]            = 'gap';
		$styles[ "gap-vertical-{$slug}" ]   = 'row-gap';
		$styles[ "gap-horizontal-{$slug}" ] = 'column-gap';
	}

	$html = get_the_content() . get_the_block_template_html();

	foreach ( $styles as $class => $property ) {
		$slug = str_replace( $property . '-', '', $class );

		if ( str_contains( $html, $class ) ) {
			$css .= ".has-{$class}{{$property}:var(--wp--preset--spacing--{$slug})!important}";
		}
	}

	wp_add_inline_style( 'global-styles', $css );
}
