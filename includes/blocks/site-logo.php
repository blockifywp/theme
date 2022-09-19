<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function file_get_contents;

add_filter( 'render_block_core/site-logo', NS . 'render_site_logo_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.24
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_site_logo_block( string $content, array $block ): string {
	if ( ! $content ) {
		$content = file_get_contents( DIR . 'assets/svg/social/blockify.svg' );

		$dom = dom( $content );

		/* @var DOMElement $svg SVG element. */
		$svg = $dom->getElementsByTagName( 'svg' )->item( 0 );

		$svg->setAttribute( 'class', 'blockify-icon' );
		$svg->setAttribute( 'width', '30' );
		$svg->setAttribute( 'height', '30' );
		$svg->setAttribute( 'title', SLUG );

		$paths = $svg->getElementsByTagName( 'path' );

		if ( $paths->item( 0 ) ) {

			/* @var DOMElement $path SVG path element. */
			$path = $paths->item( 0 );

			$path->setAttribute( 'fill', 'var(--wp--preset--color--primary-600, currentColor)' );
		}

		$content = $dom->saveHTML();
	}

	return $content;
}

