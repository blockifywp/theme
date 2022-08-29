<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function date;
use function str_replace;

add_filter( 'render_block_core/paragraph', NS . 'render_paragraph_block', 10, 2 );
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
function render_paragraph_block( string $content, array $block ): string {
	$shortcodes = [
		'[year]' => date( 'Y' ),
	];

	foreach ( $shortcodes as $shortcode => $value ) {
		$content = str_replace( $shortcode, $value, $content );
	}

	return $content;
}
