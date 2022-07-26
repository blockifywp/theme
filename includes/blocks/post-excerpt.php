<?php

declare( strict_types=1 );

namespace Blockify;

use function add_filter;
use function has_excerpt;

add_filter( 'render_block', NS . 'render_excerpt_block', 10, 2 );
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
function render_excerpt_block( string $content, array $block ): string {
	if ( 'core/post-excerpt' !== $block['blockName'] ) {
		return $content;
	}

	if ( ! has_excerpt() ) {
		$content = '';
	}

	return $content;
}

add_filter( 'excerpt_length', NS . 'excerpt_length', 99 );

function excerpt_length( int $length ): int {
	return 30;
}

add_filter( 'excerpt_more', NS . 'excerpt_more' );

function excerpt_more( string $more ): string {
	return str_replace( [ '[', ']' ], '', $more );
}
