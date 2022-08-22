<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block', NS . 'render_post_terms_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.1
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_post_terms_block( string $content, array $block ): string {
	if ( 'core/post-terms' === $block['blockName'] && isset( $block['attrs']['align'] ) ) {
		$content = str_replace(
			[
				'wp-block-post-terms',
				'rel="tag"',
			],
			[
				'wp-block-post-terms flex justify-' . $block['attrs']['align'],
				'class="wp-block-post-terms__link" rel="tag"',
			],
			$content
		);
	}

	return $content;
}

