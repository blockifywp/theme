<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function array_unshift;
use function explode;
use function in_array;

add_filter( 'render_block_core/list', NS . 'render_list_block', 11, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_list_block( string $html, array $block ): string {
	$block_gap       = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	$justify_content = $block['attrs']['layout']['justifyContent'] ?? '';

	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );

	if ( ! $ul ) {
		return $html;
	}

	$styles = css_string_to_array( $ul->getAttribute( 'style' ) );

	if ( $block_gap === '0' || $block_gap ) {
		$styles['gap'] = format_custom_property( $block_gap );
	}

	if ( $justify_content ) {
		$styles['display']         = 'flex';
		$styles['flex-wrap']       = 'wrap';
		$styles['justify-content'] = $justify_content;
	}

	$ul->setAttribute( 'style', css_array_to_string( $styles ) );

	$classes = explode( ' ', $ul->getAttribute( 'class' ) );

	array_unshift( $classes, 'wp-block-list' );

	$ul->setAttribute( 'class', implode( ' ', $classes ) );

	$html = $dom->saveHTML();

	if ( in_array( 'is-style-accordion', $classes, true ) ) {
		$html = render_list_block_accordion( $html, $block );
	}

	return $html;
}
