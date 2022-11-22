<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_contains;

add_filter( 'render_block_core/image', NS . 'render_image_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_image_block( string $html, array $block ): string {
	$id   = $block['attrs']['id'] ?? '';
	$icon = str_contains( $html, 'is-style-icon' ) || isset( $block['attrs']['className'] ) && str_contains( $block['attrs']['className'], 'is-style-icon' );
	$svg  = $block['attrs']['style']['svgString'] ?? '';

	// Placeholder.
	if ( ! $id && ! $icon && ! $svg ) {
		$html = render_image_placeholder( $html, $block );
	}

	// Icon.
	if ( $icon ) {
		$html = get_icon_html( $html, $block );
	}

	return $html;
}
