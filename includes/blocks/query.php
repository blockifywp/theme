<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block_core/query', NS . 'render_query_block', 10, 2 );
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
function render_query_block( string $content, array $block ): string {
	if ( $block['attrs']['style']['spacing']['blockGap'] ?? null ) {
		$dom = dom( $content );

		/* @var DOMElement $div Query block. */
		$div = $dom->getElementsByTagName( 'div' )->item( 0 );

		$style = $div->getAttribute( 'style' ) ? $div->getAttribute( 'style' ) . ';' : '';

		$div->setAttribute( 'style', $style . '--wp--style--block-gap:' . $block['attrs']['style']['spacing']['blockGap'] );

		$content = $dom->saveHTML();
	}

	return $content;
}
