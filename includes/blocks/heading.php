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
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_heading_block( string $content, array $block ): string {
	$dom = dom( $content );

	// No way of knowing tag.
	$h1 = $dom->getElementsByTagName( 'h1' )->item( 0 );
	$h2 = $dom->getElementsByTagName( 'h2' )->item( 0 );
	$h3 = $dom->getElementsByTagName( 'h3' )->item( 0 );
	$h4 = $dom->getElementsByTagName( 'h4' )->item( 0 );
	$h5 = $dom->getElementsByTagName( 'h5' )->item( 0 );
	$h6 = $dom->getElementsByTagName( 'h6' )->item( 0 );

	/* @var \DOMElement $heading Heading element. */
	$heading = $h1 ?? $h2 ?? $h3 ?? $h4 ?? $h5 ?? $h6;

	$heading->setAttribute(
		'class',
		'wp-block-heading ' . $heading->getAttribute( 'class' )
	);

	return $dom->saveHTML();
}
