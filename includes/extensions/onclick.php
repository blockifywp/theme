<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function rtrim;
use function str_contains;
use function trim;

add_filter( 'render_block', NS . 'render_block_onclick_attribute', 11, 2 );
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
function render_block_onclick_attribute( string $html, array $block ): string {

	// Force casting to string in case of incorrect html.
	if ( isset( $block['attrs']['onclick'] ) ) {
		$on_click = (string) $block['attrs']['onclick'];
	} else {
		return $html;
	}

	$on_click = str_replace( '"', "'", $on_click );
	$on_click = trim( rtrim( $on_click, ';' ) );
	$on_click = reduce_whitespace( $on_click );
	$link     = null;

	// Groups and buttons.
	if ( $on_click && $html ) {
		$dom  = dom( $html );
		$div  = get_dom_element( 'div', $dom );
		$link = get_dom_element( 'a', $div );

		if ( $link ) {
			$link->setAttribute( 'onclick', $on_click );
		}

		if ( ! $link && $div ) {
			$div->setAttribute( 'onclick', $on_click );
		}

		$html = $dom->saveHTML();
	}

	// Icon.
	if ( $on_click && $html && $link === null ) {
		$dom    = dom( $html );
		$figure = get_dom_element( 'figure', $dom );
		$img    = get_dom_element( 'img', $figure );

		if ( $img && ! str_contains( $figure->getAttribute( 'class' ), 'wp-block-post-featured-image' ) ) {
			$img->setAttribute( 'onclick', $on_click );
		}

		$html = $dom->saveHTML();
	}

	return $html;
}
