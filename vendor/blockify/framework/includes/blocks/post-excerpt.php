<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function get_option;
use function str_replace;

add_filter( 'excerpt_length', NS . 'set_excerpt_length', 99 );
/**
 * Filters the excerpt length for posts.
 *
 * @since 0.2.0
 *
 * @return int
 */
function set_excerpt_length(): int {
	return (int) ( get_option( SLUG . '_settings' )['excerpt_length'] ?? 30 );
}

add_filter( 'excerpt_more', NS . 'remove_brackets_from_excerpt' );
/**
 * Removes brackets from excerpt more string.
 *
 * @since 0.0.1
 *
 * @param string $more Read more text.
 *
 * @return string
 */
function remove_brackets_from_excerpt( string $more ): string {
	return str_replace( [ '[', ']' ], '', $more );
}
