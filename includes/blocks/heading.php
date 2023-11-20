<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_filter;
use function explode;
use function get_search_query;
use function implode;
use function in_array;
use function sanitize_title_with_dashes;
use function sprintf;

add_filter( 'render_block_core/heading', NS . 'render_heading_block', 10, 3 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string   $html   Block HTML.
 * @param array    $block  Block data.
 * @param WP_Block $object Block object.
 *
 * @return string
 */
function render_heading_block( string $html, array $block, WP_Block $object ): string {
	$dom     = dom( $html );
	$level   = $block['attrs']['level'] ?? 2;
	$heading = get_dom_element( 'h' . $level, $dom );

	if ( ! $heading ) {
		return $html;
	}

	$classes = explode( ' ', $heading->getAttribute( 'class' ) );

	if ( ! in_array( 'wp-block-heading', $classes, true ) ) {
		$classes[] = 'wp-block-heading';
	}

	$styles = css_string_to_array( $heading->getAttribute( 'style' ) );

	$gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( $gap ) {
		$styles['gap'] = format_custom_property( $gap );
	}

	$styles = add_shorthand_property( $styles, 'margin', $block['attrs']['style']['spacing']['margin'] ?? [] );

	$heading->setAttribute(
		'class',
		implode( ' ', $classes )
	);

	$heading->setAttribute(
		'style',
		css_array_to_string( $styles )
	);

	$id = $heading->getAttribute( 'id' );

	if ( ! $id ) {
		$heading->setAttribute(
			'id',
			remove_non_alphanumeric(
				sanitize_title_with_dashes(
					$heading->textContent
				)
			)
		);
	}

	if ( ! $heading->getAttribute( 'style' ) ) {
		$heading->removeAttribute( 'style' );
	}

	$search_query = get_search_query();

	if ( $level === 1 && $search_query && $heading->textContent === __( 'Search Results', 'blockify' ) ) {
		$heading->textContent = sprintf(
			__( 'Search results for: ', 'blockify' ) . '%s',
			$search_query
		);
	}

	return $dom->saveHTML();
}
