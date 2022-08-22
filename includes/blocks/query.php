<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function get_option;
use function get_post;
use function is_home;
use function str_contains;
use function str_replace;
use function strip_tags;

add_filter( 'render_block', NS . 'render_query_block', 10, 2 );
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
function render_query_block( string $content, array $block ): string {
	if ( $block['blockName'] !== 'core/query' ) {
		return $content;
	}

	if ( isset( $block['attrs']['style']['spacing']['blockGap'] ) ) {

		$dom = dom( $content );

		/** @var DOMElement $div */
		$div = $dom->firstChild;

		$style = $div->getAttribute( 'style' ) ? $div->getAttribute( 'style' ) . ';' : '';

		$div->setAttribute( 'style', $style . '--wp--style--block-gap:' . $block['attrs']['style']['spacing']['blockGap'] );

		$content = $dom->saveHTML();
	}

	return $content;
}
