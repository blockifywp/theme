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

	$dom                     = new DOMDocument();
	$libxml_previous_state   = libxml_use_internal_errors( true );
	$dom->preserveWhiteSpace = true;

	if ( defined( 'LIBXML_HTML_NOIMPLIED' ) && defined( 'LIBXML_HTML_NODEFDTD' ) ) {
		$options = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD;
	} elseif ( defined( 'LIBXML_HTML_NOIMPLIED' ) ) {
		$options = LIBXML_HTML_NOIMPLIED;
	} elseif ( defined( 'LIBXML_HTML_NODEFDTD' ) ) {
		$options = LIBXML_HTML_NODEFDTD;
	} else {
		$options = 0;
	}

	$dom->loadHTML(
		mb_convert_encoding(
			$content,
			'HTML-ENTITIES',
			'UTF-8' ),
		$options
	);

	$dom->formatOutput = true;

	libxml_clear_errors();
	libxml_use_internal_errors( $libxml_previous_state );

	/* @var $element DOMElement */
	$element = $dom->firstChild;

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

		$styles ["has-padding-{$slug}"]        = "padding:var(--wp--preset--spacing--{$slug})";
		$styles ["has-padding-top-{$slug}"]    = "padding-top:var(--wp--preset--spacing--{$slug})";
		$styles ["has-padding-right-{$slug}"]  = "padding-right:var(--wp--preset--spacing--{$slug})";
		$styles ["has-padding-bottom-{$slug}"] = "padding-bottom:var(--wp--preset--spacing--{$slug})";
		$styles ["has-padding-left-{$slug}"]   = "padding-left:var(--wp--preset--spacing--{$slug})";
		$styles ["has-margin-{$slug}"]         = "margin:var(--wp--preset--spacing--{$slug})";
		$styles ["has-margin-top-{$slug}"]     = "margin-top:var(--wp--preset--spacing--{$slug})";
		$styles ["has-margin-right-{$slug}"]   = "margin-right:var(--wp--preset--spacing--{$slug})";
		$styles ["has-margin-bottom-{$slug}"]  = "margin-bottom:var(--wp--preset--spacing--{$slug})";
		$styles ["has-margin-left-{$slug}"]    = "margin-left:var(--wp--preset--spacing--{$slug})";
		$styles ["has-gap-{$slug}"]            = "gap:var(--wp--preset--spacing--{$slug})";
		$styles ["has-gap-vertical-{$slug}"]   = "row-gap:var(--wp--preset--spacing--{$slug})";
		$styles ["has-gap-horizontal-{$slug}"] = "column-gap:var(--wp--preset--spacing--{$slug})";
	}

	$html = get_the_content() . get_the_block_template_html();

	foreach ( $styles as $class => $rule ) {
		if ( str_contains( $html, $class ) ) {
			$css .= ".{$class}{{$rule}!important}";
		}
	}

	wp_add_inline_style( 'global-styles', $css );
}
