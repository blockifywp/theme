<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function file_get_contents;

add_filter( 'render_block', NS . 'render_site_logo_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.24
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_site_logo_block( string $content, array $block ): string {
	if ( 'core/site-logo' !== $block['blockName'] ) {
		return $content;
	}

	if ( ! $content ) {
		$content = file_get_contents( DIR . 'assets/svg/social/blockify.svg' );

		$dom = dom( $content );

		/** @var DOMElement $svg */
		$svg = $dom->firstChild;

		$svg->setAttribute( 'class', 'blockify-icon' );
		$svg->setAttribute( 'width', '30' );
		$svg->setAttribute( 'height', '30' );
		$svg->setAttribute( 'title', SLUG );

		$paths = $svg->getElementsByTagName( 'path' );

		if ( $paths->item( 0 ) ) {

			/** @var DOMElement $path */
			$path = $paths->item( 0 );

			$path->setAttribute( 'fill', 'var(--wp--preset--color--primary-600, currentColor)' );
		}

		$content = $dom->saveHTML();
	}

	return $content;
}

