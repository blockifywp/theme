<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block_core/group', NS . 'render_block_layout', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.20
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_block_layout( string $content, array $block ): string {
	$dom = dom( $content );

	/**
	 * @var \DOMElement $first
	 */
	$first = $dom->firstChild;

	if ( $first->tagName === 'main' ) {
		$first->setAttribute(
			'class',
			'wp-site-main ' . $first->getAttribute( 'class' )
		);
	}

	if ( $block['attrs']['minHeight'] ?? null ) {
		$first->setAttribute(
			'style',
			$first->getAttribute( 'style' ) . 'min-height:' . $block['attrs']['minHeight']
		);
	}

	return $dom->saveHTML();
}
