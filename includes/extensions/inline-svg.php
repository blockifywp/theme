<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function content_url;
use function dirname;
use function file_exists;
use function file_get_contents;
use function in_array;
use function str_contains;
use function str_replace;

add_filter( 'render_block', NS . 'render_inline_svg', 10, 2 );
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
function render_inline_svg( string $content, array $block ): string {
	$supported = [ 'core/image', 'core/site-logo' ];

	// SVG.
	if ( ! in_array( $block['blockName'], $supported, true ) || ! str_contains( $content, '.svg' ) ) {
		return $content;
	}

	$dom     = dom( $content );
	$element = $block['blockName'] === 'core/image' ? 'figure' : 'div';
	$figure  = get_dom_element( $element, $dom );
	$link    = get_dom_element( 'a', $figure );
	$img     = get_dom_element( 'img', $figure );

	if ( ! $figure || ! $img ) {
		return $content;
	}

	$src = str_replace(
		content_url(),
		dirname( dirname( DIR ) ),
		$img->getAttribute( 'src' )
	);

	if ( ! file_exists( $src ) ) {
		return $content;
	}

	$svg_dom     = dom( file_get_contents( $src ) );
	$svg_element = get_dom_element( 'svg', $svg_dom );
	$svg_node    = $dom->importNode( $svg_element, true );
	$parent      = $link ?? $figure;

	$parent->appendChild( $svg_node );
	$svg = get_dom_element( 'svg', $parent );

	$svg->setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );

	$width   = $img->getAttribute( 'width' ) ? $img->getAttribute( 'width' ) : '';
	$height  = $img->getAttribute( 'height' ) ? $img->getAttribute( 'height' ) : '';
	$fill    = $img->getAttribute( 'fill' ) ? $img->getAttribute( 'fill' ) : '';
	$viewBox = $svg_element->getAttribute( 'viewBox' ) ? $svg_element->getAttribute( 'viewBox' ) : '';

	if ( $width ) {
		$svg->setAttribute( 'width', $width );
	}

	if ( $height ) {
		$svg->setAttribute( 'height', $height );
	}

	if ( $fill ) {
		$svg->setAttribute( 'fill', $fill );
	}

	if ( $viewBox ) {
		$svg->setAttribute( 'viewBox', $viewBox );
	}

	foreach ( $svg_element->childNodes as $child ) {
		$imported = $dom->importNode( $child, true );
		$svg->appendChild( $imported );
	}

	$img->parentNode->removeChild( $img );

	return $dom->saveHTML();
}

