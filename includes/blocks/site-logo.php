<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function content_url;
use function dirname;
use function file_exists;
use function file_get_contents;
use function get_template_directory;
use function method_exists;
use function str_contains;
use function str_replace;

add_filter( 'render_block_core/site-logo', NS . 'render_site_logo_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_site_logo_block( string $html, array $block ): string {
	if ( str_contains( $html, '.svg' ) ) {
		$dom  = dom( $html );
		$div  = get_dom_element( 'div', $dom );
		$link = get_dom_element( 'a', $div );
		$img  = get_dom_element( 'img', $link ?? $div );

		if ( ! $img ) {
			return $html;
		}

		$file = str_replace(
			content_url(),
			dirname( dirname( get_template_directory() ) ),
			$img->getAttribute( 'src' )
		);

		if ( ! file_exists( $file ) ) {
			return $html;
		}

		$svg = $dom->importNode( dom( file_get_contents( $file ) )->documentElement, true );

		if ( ! method_exists( $svg, 'setAttribute' ) ) {
			return $html;
		}

		$svg->setAttribute( 'width', $img->getAttribute( 'width' ) );
		$svg->setAttribute( 'height', $img->getAttribute( 'height' ) );
		$svg->setAttribute( 'aria-label', $img->getAttribute( 'alt' ) );
		$svg->setAttribute( 'class', $img->getAttribute( 'class' ) );

		( $link ?? $div )->removeChild( $img );
		( $link ?? $div )->appendChild( $svg );

		$html = $dom->saveHTML();
	}

	return $html;
}
