<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block', NS . 'remove_duplicate_classes', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * Can't use DOMDocument because it breaks closing tags.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function remove_duplicate_classes( string $content, array $block ): string {
	return str_replace(
		[ 'alignfull alignfull', 'alignwide alignwide' ],
		[ 'alignfull', 'alignwide' ],
		$content
	);
}
