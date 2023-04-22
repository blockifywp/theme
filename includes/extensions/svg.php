<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function rawurlencode;
use function str_contains;
use function str_replace;
use function trim;
use function urldecode;

add_filter( 'render_block_core/image', NS . 'render_svg_block_variation', 9, 2 );
/**
 * Render SVG block variation.
 *
 * @param string $html  Block html content.
 * @param array  $block Block data.
 *
 * @since 0.9.10
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
	$width  = $block['attrs']['width'] ?? '';
	$height = $block['attrs']['height'] ?? '';

	if ( $mask ) {
		$span    = change_tag_name( $img, 'span' );
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
			$styles['width'] = $width . 'px';

			$span->removeAttribute( 'width' );
		}

		if ( $height ) {
			$styles['height'] = $height . 'px';

			$span->removeAttribute( 'height' );
		}

		$alt = $img->getAttribute( 'alt' );

		if ( $alt ) {
			$span->setAttribute( 'aria-label', $alt );
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
		$imported->setAttribute( 'width', (string) $width );
	}

	if ( $height ) {
		$imported->setAttribute( 'height', (string) $height );
	}

	( $link ?? $figure )->appendChild( $imported );

	if ( $link ) {
		$link->appendChild( $imported );
	} else {
		$figure->appendChild( $imported );
	}

	return $dom->saveHTML();
}

add_filter( 'render_block', NS . 'render_inline_svg', 10, 2 );
/**
 * Renders inline SVGs in rich text content.
 *
 * @param string $html  Block html content.
 * @param array  $block Block data.
 *
 * @since 0.9.10
 *
 * @return string
 */
function render_inline_svg( string $html, array $block ): string {
	if ( ! str_contains( $html, 'has-inline-svg' ) ) {
		return $html;
	}

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$imgs = $dom->getElementsByTagName( 'img' );

	if ( ! $imgs->length ) {
		return $html;
	}

	foreach ( $imgs as $index => $img ) {
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

			$imported->setAttribute( $attribute->name, $attribute->value );
		}

		$imported->setAttribute( 'fill', 'currentColor' );

		$classes = explode( ' ', $img->getAttribute( 'class' ) );

		unset( $classes[ array_search( 'has-inline-svg', $classes, true ) ] );

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
