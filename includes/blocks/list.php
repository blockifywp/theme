<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block_core/list', NS . 'render_list_block', 10, 2 );
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
function render_list_block( string $content, array $block ): string {
	$block_gap       = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	$justify_content = $block['attrs']['layout']['justifyContent'] ?? '';

	$dom = dom( $content );

	/**
	 * @var \DOMElement $ul
	 */
	$ul    = $dom->firstChild;
	$style = $ul->getAttribute( 'style' );

	if ( $block_gap ) {
		$style .= ';--wp--style--block-gap:' . $block_gap . ';';
	}

	if ( $justify_content ) {
		$style .= ';display:flex;flex-wrap:wrap;justify-content:' . $justify_content . ';';
	}

	$ul->setAttribute( 'style', $style );

	return $dom->saveHTML();
}
