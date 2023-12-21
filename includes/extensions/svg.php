<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_diff;
use function content_url;
use function dirname;
use function esc_attr;
use function explode;
use function file_exists;
use function file_get_contents;
use function get_template_directory;
use function implode;
use function in_array;
use function method_exists;
use function rawurlencode;
use function str_contains;
use function str_replace;
use function trim;
use function urldecode;

add_filter( 'render_block_core/image', NS . 'render_svg_block_variation', 9, 2 );
/**
 * Render SVG block variation.
 *
 * @since 0.9.10
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block html content.
 *
 * @return string
 */
function render_svg_block_variation( string $html, array $block ): string {
	$svg_string = $block['attrs']['style']['svgString'] ?? '';

	if ( ! $svg_string ) {
		return $html;
	}

	if ( ! str_contains( $html, 'is-style-svg' ) ) {
		return $html;
	}

	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );
	$link   = get_dom_element( 'a', $figure );
	$img    = get_dom_element( 'img', $link ?? $figure );
	$svg    = get_dom_element( 'svg', $link ?? $figure );

	$mask   = (bool) ( $block['attrs']['style']['maskSvg'] ?? false );
	$width  = esc_attr( $block['attrs']['width'] ?? '' );
	$height = esc_attr( $block['attrs']['height'] ?? '' );

	if ( $mask ) {
		$span    = change_tag_name( 'span', $img );
		$styles  = css_string_to_array( $span->getAttribute( 'style' ) );
		$encoded = rawurlencode(
			str_replace(
				'"',
				"'",
				trim( $svg_string )
			)
		);

		$styles['-webkit-mask-image'] = 'url("data:image/svg+xml;utf8,' . $encoded . '")';

		if ( $width ) {
			$unit = str_contains_any( $width, 'px', 'em', 'rem', 'vh', 'vw', '%' ) ? '' : 'px';

			$styles['width'] = $width . $unit;

			$span->removeAttribute( 'width' );
		}

		if ( $height ) {
			$unit = str_contains_any( $height, 'px', 'em', 'rem', 'vh', 'vw', '%' ) ? '' : 'px';

			$styles['height'] = $height . $unit;

			$span->removeAttribute( 'height' );
		}

		$alt = $img->getAttribute( 'alt' );

		if ( $alt ) {
			$span->setAttribute( 'aria-label', esc_attr( $alt ) );
			$span->removeAttribute( 'alt' );
		}

		$classes = explode( ' ', $span->getAttribute( 'class' ) );

		$classes[] = 'wp-block-image__svg';

		$span->setAttribute( 'class', implode( ' ', $classes ) );
		$span->setAttribute( 'role', 'img' );
		$span->removeAttribute( 'style' );
		$span->setAttribute( 'style', css_array_to_string( $styles ) );
		$span->removeAttribute( 'src' );

		return $dom->saveHTML();
	}

	if ( $svg ) {
		return $html;
	}

	if ( $img ) {
		$img->parentNode->removeChild( $img );
	}

	$svg_dom     = dom( $svg_string );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $html;
	}

	$imported = $dom->importNode( $svg_element, true );
	$imported = dom_element( $imported );

	if ( $width ) {
		$imported->setAttribute( 'width', $width );
	}

	if ( $height ) {
		$imported->setAttribute( 'height', $height );
	}

	( $link ?? $figure )->appendChild( $imported );

	if ( $link ) {
		$link->appendChild( $imported );
	} else {
		$figure->appendChild( $imported );
	}

	return $dom->saveHTML();
}

add_filter( 'render_block', NS . 'render_inline_svg_mask', 10, 2 );
/**
 * Renders inline SVGs in rich text content.
 *
 * @since 0.9.10
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block html content.
 *
 * @return string
 */
function render_inline_svg_mask( string $html, array $block ): string {
	if ( ! str_contains( $html, 'has-inline-svg' ) ) {
		return $html;
	}

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$images = $dom->getElementsByTagName( 'img' );

	if ( ! $images->length ) {
		return $html;
	}

	foreach ( $images as $index => $img ) {
		$style = css_string_to_array( $img->getAttribute( 'style' ) );
		$mask  = $style['-webkit-mask-image'] ?? '';

		if ( ! $mask ) {
			continue;
		}

		$svg_string  = str_replace( [ "url('data:image/svg+xml;utf8,", "')" ], [ '', '' ], $mask );
		$svg_string  = urldecode( $svg_string );
		$svg_dom     = dom( $svg_string );
		$svg_element = get_dom_element( 'svg', $svg_dom );

		if ( ! $svg_element ) {
			return $html;
		}

		$imported = $dom->importNode( $svg_element, true );
		$imported = dom_element( $imported );
		$imported->removeAttribute( 'height' );
		$imported->removeAttribute( 'width' );

		foreach ( $img->attributes as $attribute ) {
			if ( $attribute->name === 'style' ) {
				$style = css_string_to_array( $img->getAttribute( 'style' ) );
				unset( $style['-webkit-mask-image'] );
				$imported->setAttribute( 'style', css_array_to_string( $style ) );
				continue;
			}

			$imported->setAttribute(
				esc_attr( $attribute->name ),
				esc_attr( $attribute->value )
			);
		}

		$imported->setAttribute( 'fill', 'currentColor' );

		$classes = explode( ' ', $img->getAttribute( 'class' ) );
		$classes = array_diff( $classes, [ 'has-inline-svg' ] );

		$classes[] = 'inline-svg';

		$imported->setAttribute( 'class', implode( ' ', $classes ) . ' ' . $svg_element->getAttribute( 'class' ) );

		$html = str_replace(
			$dom->saveHTML( $img ),
			$dom->saveHTML( $imported ),
			$html
		);
	}

	return $html;
}

add_filter( 'render_block', NS . 'render_inline_svg', 10, 2 );
/**
 * Converts image asset to inline SVG.
 *
 * @since 1.5.0
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_inline_svg( string $html, array $block ): string {
	$blocks = [
		'core/image',
		'core/site-logo',
		'core/post-featured-image',
	];

	$name = $block['blockName'] ?? '';

	if ( ! in_array( $name, $blocks, true ) ) {
		return $html;
	}

	if ( ! str_contains( $html, '.svg' ) ) {
		return $html;
	}

	$attrs  = $block['attrs'] ?? [];
	$dom    = dom( $html );
	$div    = get_dom_element( 'div', $dom );
	$figure = get_dom_element( 'figure', $dom );
	$first  = $div ?? $figure ?? null;
	$link   = get_dom_element( 'a', $first );
	$img    = get_dom_element( 'img', $link ?? $first );

	if ( ! $img ) {
		return $html;
	}

	$file = str_replace(
		content_url(),
		dirname( get_template_directory(), 2 ),
		$img->getAttribute( 'src' )
	);

	if ( ! file_exists( $file ) ) {
		return $html;
	}

	$svg = $dom->importNode( dom( file_get_contents( $file ) )->documentElement, true );

	if ( ! method_exists( $svg, 'setAttribute' ) ) {
		return $html;
	}

	$width  = $attrs['width'] ?? $img->getAttribute( 'width' );
	$height = $attrs['height'] ?? $img->getAttribute( 'height' );
	$alt    = $attrs['alt'] ?? $img->getAttribute( 'alt' );

	if ( $width ) {
		$svg->setAttribute( 'width', str_replace( 'px', '', (string) $width ) );
	}

	if ( $height ) {
		$svg->setAttribute( 'height', str_replace( 'px', '', (string) $height ) );
	}

	if ( $alt ) {
		$svg->setAttribute( 'aria-label', $alt );
	}

	$svg->setAttribute( 'class', $img->getAttribute( 'class' ) );

	( $link ?? $first )->removeChild( $img );
	( $link ?? $first )->appendChild( $svg );

	$first_classes = explode( ' ', $first->getAttribute( 'class' ) );

	$first_classes[] = 'has-inlined-svg';

	$first->setAttribute( 'class', implode( ' ', $first_classes ) );

	return $dom->saveHTML();
}
