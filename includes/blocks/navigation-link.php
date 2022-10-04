<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function str_replace;

add_filter( 'render_block_core/navigation-link', NS . 'render_navigation_link_block', 10, 2 );
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
function render_navigation_link_block( string $content, array $block ): string {



	return $content;
}
