<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

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
