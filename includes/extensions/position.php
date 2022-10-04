<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block', NS . 'render_block_position', 10, 2 );
/**
 * Renders image icon styles on front end.
 *
 * @since 0.2.0
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_block_position( string $content, array $block ): string {
	$position = $block['attrs']['position'] ?? null;
	$inset    = $block['attrs']['inset'] ?? null;
	$zIndex   = $block['attrs']['zIndex'] ?? null;
	$overflow = $block['attrs']['overflow'] ?? null;

	$has_attributes = $position || $inset || $zIndex || $overflow;

	if ( ! $has_attributes ) {
		return $content;
	}

	$dom   = dom( $content );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $content;
	}

	$styles = css_string_to_array( $first->getAttribute( 'style' ) );

	if ( $position ) {
		$styles['position'] = $position;
	}

	if ( $inset ) {
		$styles['top']    = $inset['top'] ?? null;
		$styles['right']  = $inset['right'] ?? null;
		$styles['bottom'] = $inset['bottom'] ?? null;
		$styles['left']   = $inset['left'] ?? null;
	}

	if ( $zIndex ) {
		$styles['z-index'] = $zIndex;
	}

	if ( $overflow ) {
		$styles['overflow-x'] = $overflow['x'] ?? null;
		$styles['overflow-y'] = $overflow['y'] ?? null;
	}

	$first->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}
