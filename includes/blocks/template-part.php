<?php

declare( strict_types=1 );

namespace Blockify;

use function add_filter;
use function str_replace;

add_filter( 'render_block', NS . 'render_template_part_block', 10, 2 );
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
function render_template_part_block( string $content, array $block ): string {
	if ( 'core/template-part' !== $block['blockName'] ) {
		return $content;
	}

	return $content;
}
