<?php

declare( strict_types=1 );

namespace Blockify;

use DOMElement;
use function add_filter;

add_filter( 'render_block', NS . 'render_list_block', 10, 2 );
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
	if ( 'core/list' !== $block['blockName'] ) {
		return $content;
	}

	if ( isset( $block['attrs']['style']['spacing']['blockGap'] ) ) {
		$dom = dom( $content );

		/**
		 * @var $ul DOMElement
		 */
		$ul = $dom->firstChild;

		$ul->setAttribute(
			'style',
			$ul->getAttribute( 'style' ) . ';--wp--style--block-gap:' . $block['attrs']['style']['spacing']['blockGap'] . ';'
		);

		$content = $dom->saveHTML();
	}

	return $content;
}
