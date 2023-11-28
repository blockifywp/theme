<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function rtrim;
use function str_contains;
use function strval;
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
	$js = strval( $block['attrs']['onclick'] ?? '' );

	if ( ! $js ) {
		return $html;
	}

	$on_click = format_inline_js( $js );
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

/**
 * Formats inline JS.
 *
 * @since 1.2.9
 *
 * @param string $js JS.
 *
 * @return string
 */
function format_inline_js( string $js ): string {
	$js = str_replace( '"', "'", $js );
	$js = trim( rtrim( $js, ';' ) );
	$js = reduce_whitespace( $js );
	$js = remove_line_breaks( $js );

	return $js;
}
