<?php

declare( strict_types=1 );

namespace Blockify;

use DOMElement;
use function add_filter;
use function content_url;
use function file_get_contents;
use function str_contains;
use function wp_get_attachment_image_src;
use const WP_CONTENT_DIR;

//add_filter( 'render_block', NS . 'render_image_block', 10, 2 );
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
function render_image_block( string $content, array $block ): string {
	if ( 'core/image' !== $block['blockName'] ) {
		return $content;
	}

	if ( str_contains( $content, '.svg' ) ) {
		if ( ! isset( $block['attrs']['id'] ) ) {
			return $content;
		}

		$path = str_replace(
			content_url(),
			WP_CONTENT_DIR,
			wp_get_attachment_image_src( $block['attrs']['id'] )[0] ?? ''
		);

		if ( ! \file_exists( $path ) ) {
			return $content;
		}

		$svg    = dom( file_get_contents( $path ) );
		$dom    = dom( $content );
		$figure = $dom->firstChild;

		/**
		 * @var $img DOMElement
		 */
		$img = $dom->getElementsByTagName( 'img' )->item( 0 );

		$svg->documentElement->setAttribute( 'height', $img->getAttribute( 'height' ) );
		$svg->documentElement->setAttribute( 'width', $img->getAttribute( 'width' ) );
		$dom->importNode( $svg->documentElement, true );

		$import = $dom->importNode( $svg->documentElement, true );

		$figure->appendChild( $import );
		$figure->removeChild( $img );

		$content = $dom->saveHTML();
	}

	return $content;
}
