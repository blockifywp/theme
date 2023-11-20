<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/details', NS . 'render_details_block', 10, 2 );
/**
 * Renders the details block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_details_block( string $html, array $block ): string {

	$dom     = dom( $html );
	$details = get_dom_element( 'details', $dom );

	if ( ! $details ) {
		return $html;
	}

	$summary = get_dom_element( 'summary', $details );
	$icon    = create_element( 'span', $dom );
	$icon->setAttribute( 'class', 'accordion-toggle' );

	$summary->appendChild( $icon );

	return $dom->saveHTML();

}
