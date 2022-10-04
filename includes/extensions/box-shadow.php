<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function str_replace;

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
		$dom         = dom( $content );
		$first_child = get_dom_element( '*', $dom );
		$style       = $first_child->getAttribute( 'style' );
		$styles      = explode( ';', $style );
		$z_index     = (string) ( $block['attrs']['boxShadow']['zIndex'] ?? '-1');
		$z_index     = str_replace( 'px', '', $z_index );

		$styles[] = '--wp--custom--box-shadow--z-index:' . $z_index;

		$first_child->setAttribute( 'style', implode( ';', $styles ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
