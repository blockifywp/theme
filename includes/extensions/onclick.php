<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function rtrim;
use function trim;

add_filter( 'render_block', NS . 'render_block_onclick_attribute', 11, 2 );
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
function render_block_onclick_attribute( string $content, array $block ): string {
	$on_click = $block['attrs']['onclick'] ?? '';
	$on_click = str_replace( '"', "'", $on_click );
	$on_click = trim( rtrim( $on_click, ';' ) );
	$on_click = reduce_whitespace( $on_click );
	$link     = null;

	// Groups and buttons.
	if ( $on_click && $content ) {
		$dom  = dom( $content );
		$div  = get_dom_element( 'div', $dom );
		$link = get_dom_element( 'a', $div );

		if ( $link ) {
			$link->setAttribute( 'onclick', $on_click );
		}

		if ( ! $link && $div ) {
			$div->setAttribute( 'onclick', $on_click );
		}

		$content = $dom->saveHTML();
	}

	// Icon.
	if ( $on_click && $content && $link === null ) {
		$dom    = dom( $content );
		$figure = get_dom_element( 'figure', $dom );
		$span   = get_dom_element( 'img', $figure );

		if ( $span ) {
			$span->setAttribute( 'onclick', $on_click );
		}

		$content = $dom->saveHTML();
	}

	return $content;
}
