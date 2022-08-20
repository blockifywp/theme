<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block', NS . 'render_featured_image_block', 10, 2 );
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
function render_featured_image_block( string $content, array $block ): string {
	if ( 'core/post-featured-image' !== $block['blockName'] ) {
		return $content;
	}

	if ( ! $content ) {
		$content = '<span class="wp-block-post-featured-image__placeholder"></span>';
	}

	return $content;
}
