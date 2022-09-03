<?php

declare( strict_types=1 );

namespace Blockify\Theme;

\add_filter( 'render_block_core/tag-cloud', NS . 'render_tag_cloud_block', 10, 2 );
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
function render_tag_cloud_block( string $content, array $block ): string {
	$smallest = $block['attrs']['smallestFontSize'] ?? '1em';
	$largest  = $block['attrs']['largestFontSize'] ?? '1em';
	$dom      = dom( $content );

	/* @var \DOMElement $first */
	$first = $dom->firstChild;

	$first->setAttribute( 'style', \implode( ';', [
		'font-size:max(' . $smallest . ',' . $largest . ')',
		$first->getAttribute( 'style' ),
	] ) );


	return $dom->saveHTML();
}

