<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_contains;
use function str_replace;
use function urldecode;

add_filter( 'render_block_core/image', NS . 'render_svg_block_variation', 11, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_svg_block_variation( string $content, array $block ): string {
	$svg_string = $block['attrs']['style']['svgString'] ?? '';

	if ( ! $svg_string ) {
		return $content;
	}

	$dom    = dom( $content );
	$figure = get_dom_element( 'figure', $dom );
	$link   = get_dom_element( 'a', $figure );
	$img    = get_dom_element( 'img', $link ?? $figure );
	$svg    = get_dom_element( 'svg', $link ?? $figure );

	if ( $svg ) {
		return $content;
	}

	if ( $img ) {
		$img->parentNode->removeChild( $img );
	}

	$svg_dom     = dom( $svg_string );
	$svg_element = get_dom_element( 'svg', $svg_dom );

	if ( ! $svg_element ) {
		return $content;
	}

	$imported = $dom->importNode( $svg_element, true );
	$width    = $block['attrs']['width'] ?? '';
	$height   = $block['attrs']['height'] ?? '';

	if ( $width ) {
		$imported->setAttribute( 'width', (string) $width );
	}

	$height = $height === '' ? $width : $height;

	if ( $height ) {
		$imported->setAttribute( 'height', (string) $height );
	}

	( $link ?? $figure )->appendChild( $imported );

	if ( $link ) {
		$link->appendChild( $imported );
	} else {
		$figure->appendChild( $imported );
	}

	$content = $dom->saveHTML();

	return $content;
}


add_filter( 'render_block', NS . 'render_inline_svg', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_inline_svg( string $content, array $block ): string {
	if ( ! str_contains( $content, 'has-inline-svg' ) ) {
		return $content;
	}

	$dom   = dom( $content );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $content;
	}

	$imgs = $dom->getElementsByTagName( 'img' );

	if ( ! $imgs->length ) {
		return $content;
	}

	foreach ( $imgs as $index => $img ) {
		$style = css_string_to_array( $img->getAttribute( 'style' ) );
		$mask  = $style['-webkit-mask-image'] ?? '';

		if ( ! $mask ) {
			continue;
		}

		$svg_string  = str_replace( [ "url('data:image/svg+xml;utf8,", "')" ], [ '', '' ], urldecode( $mask ) );
		$svg_dom     = dom( $svg_string );
		$svg_element = get_dom_element( 'svg', $svg_dom );

		if ( ! $svg_element ) {
			return $content;
		}

		$imported = $dom->importNode( $svg_element, true );
		$imported->removeAttribute( 'height' );
		$imported->removeAttribute( 'width' );

		foreach ( $img->attributes as $attribute ) {
			if ( $attribute->name === 'style' ) {
				$style = css_string_to_array( $img->getAttribute( 'style' ) );
				unset( $style['-webkit-mask-image'] );
				$imported->setAttribute( 'style', css_array_to_string( $style ) );
				continue;
			}

			$imported->setAttribute( $attribute->name, $attribute->value );
		}

		$imported->setAttribute( 'fill', 'currentColor' );

		$classes = explode( ' ', $img->getAttribute( 'class' ) );

		unset ( $classes[ array_search( 'has-inline-svg', $classes ) ] );

		$classes[] = 'inline-svg';

		$imported->setAttribute( 'class', implode( ' ', $classes ) . ' ' . $svg_element->getAttribute('class') );


		$content = str_replace(
			$dom->saveHTML( $img ),
			$dom->saveHTML( $imported ),
			$content
		);

	}

	return $content;
}
