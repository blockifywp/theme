<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;

add_filter( 'render_block_core/post-featured-image', NS . 'render_featured_image_block', 10, 2 );
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
function render_featured_image_block( string $content, array $block ): string {

	if ( ! $content ) {
		$content = get_image_placeholder( $content, $block['attrs'] );
	}

	return $content;
}
