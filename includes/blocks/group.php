<?php

declare( strict_types=1 );

namespace Blockify;

use DOMElement;
use function add_filter;

add_filter( 'render_block', NS . 'render_group_block', 10, 2 );
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
function render_group_block( string $content, array $block ): string {
	if ( 'core/group' !== $block['blockName'] ) {
		return $content;
	}

	if ( isset( $block['attrs']['style']['spacing']['blockGap'] ) ) {

		$dom = dom( $content );

		/**
		 * @var $first DOMElement
		 */
		$first = $dom->childNodes && isset( $dom->childNodes[0] ) ? $dom->childNodes[0] : false;

		if ( $first ) {
			$style = $first->getAttribute( 'style' );
			$style .= ';--wp--style--block-gap:' . $block['attrs']['style']['spacing']['blockGap'];
			$first->setAttribute( 'style', $style );

			$content = $dom->saveHTML();
		}
	}

	return $content;
}
