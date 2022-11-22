<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/post-featured-image', NS . 'render_featured_image_block', 10, 2 );
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
function render_featured_image_block( string $html, array $block ): string {
	if ( ! $html ) {
		$html = render_image_placeholder( $html, $block['attrs'] );
	}

	return $html;
}
