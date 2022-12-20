<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function implode;

add_filter( 'render_block_core/tag-cloud', NS . 'render_tag_cloud_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_tag_cloud_block( string $html, array $block ): string {
	$smallest  = $block['attrs']['smallestFontSize'] ?? '1em';
	$largest   = $block['attrs']['largestFontSize'] ?? '1em';
	$dom       = dom( $html );
	$paragraph = get_dom_element( 'p', $dom );

	$paragraph->setAttribute(
		'style',
		implode(
			';',
			[
				'font-size:max(' . $smallest . ',' . $largest . ')',
				$paragraph->getAttribute( 'style' ),
			]
		)
	);

	return $dom->saveHTML();
}

