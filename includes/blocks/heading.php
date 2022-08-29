<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block_core/heading', NS . 'render_heading_block', 10, 2 );
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
function render_heading_block( string $content, array $block ): string {
	$dom = dom( $content );

	/**
	 * @var $heading DOMElement
	 */
	$heading = $dom->firstChild;

	$heading->setAttribute( 'class', 'wp-block-heading ' . $heading->getAttribute( 'class' ) );

	return $dom->saveHTML();
}
