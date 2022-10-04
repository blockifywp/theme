<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/image', NS . 'render_image_block', 10, 2 );
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
function render_image_block( string $content, array $block ): string {
	$id    = $block['attrs']['id'] ?? '';
	$class = $block['attrs']['className'] ?? '';
	$icon  = \str_contains( $class, 'is-style-icon' );

	// Placeholder.
	if ( ! $id && ! $icon ) {
		$content = get_image_placeholder( $content, $block['attrs'] );
	}

	return $content;
}
