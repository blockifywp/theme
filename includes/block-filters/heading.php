<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function method_exists;

add_filter( 'render_block_core/heading', NS . 'render_heading_block', 10, 2 );
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
function render_heading_block( string $content, array $block ): string {
	$dom = dom( $content );

	// No way of knowing tag.
	$heading = get_dom_element( '*', $dom );

	if ( ! $heading ) {
		return $content;
	}

	$class = $heading->getAttribute( 'class' );

	$heading->setAttribute(
		'class',
		'wp-block-heading' . ( $class ? ' ' . \trim( $class ) : '' )
	);

	return $dom->saveHTML();
}
