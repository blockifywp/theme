<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function implode;

add_filter( 'render_block', NS . 'render_box_shadow', 10, 2 );
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
function render_box_shadow( string $content, array $block ): string {
	if ( $block['attrs']['boxShadow']['zIndex'] ?? null ) {
		$dom = dom( $content );

		/**
		 * Fixes box shadow z index issue.
		 *
		 * @todo Fix px value added by editor.
		 *
		 * @var \DOMElement $first_child
		 */
		$first_child = $dom->firstChild;
		$style       = $first_child->getAttribute( 'style' );
		$styles      = explode( ';', $style );

		$styles[] = '--wp--custom--box-shadow--z-index:' . $block['attrs']['boxShadow']['zIndex'];

		$first_child->setAttribute( 'style', implode( ';', $styles ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
