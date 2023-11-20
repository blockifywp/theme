<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_replace;

add_filter( 'render_block_core/shortcode', NS . 'remove_empty_paragraphs_from_shortcode', 1, 2 );
/**
 * Fix shortcode block empty paragraph tags.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function remove_empty_paragraphs_from_shortcode( string $html, array $block ): string {
	return str_replace( [ '<p>', '</p>' ], '', $html );
}

add_filter( 'render_block_core/shortcode', NS . 'render_block_shortcode', 11, 2 );
/**
 * Render the block shortcode.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_block_shortcode( string $html, array $block ): string {
	return do_shortcode( $html );
}
