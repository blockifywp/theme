<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block', NS . 'remove_duplicate_classes', 10, 2 );
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
function remove_duplicate_classes( string $content, array $block ): string {
	if ( \str_contains( $content, 'alignfull' ) || \str_contains( $content, 'alignwide' ) ) {
		$dom = dom( $content );

		/** @var \DOMElement $first_child */
		$first_child = $dom->firstChild;

		if ( $first_child->hasAttribute( 'class' ) ) {
			$first_child->setAttribute( 'class', \implode( ' ', [
				...\array_flip( \array_flip( \explode( ' ', $first_child->getAttribute( 'class' ) ) ) ),
			] ) );
		}
	}

	return $content;
}
