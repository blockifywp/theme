<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/group', NS . 'render_block_layout', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.20
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_block_layout( string $content, array $block ): string {
	$dom   = dom( $content );
	$div   = get_dom_element( 'div', $dom );
	$align = $block['attrs']['align'] ?? null;

	if ( ! $align ) {
		$first     = get_dom_element( '*', $dom );
		$classes   = \explode( ' ', $first->getAttribute( 'class' ) );
		$classes[] = 'items-justified-left';
		$first->setAttribute( 'class', \implode( ' ', $classes ) );
	}

	if ( $div && $div->tagName === 'main' ) {
		$div->setAttribute(
			'class',
			'wp-site-main ' . $div->getAttribute( 'class' )
		);
	}

	if ( $block['attrs']['minHeight'] ?? null ) {
		$div->setAttribute(
			'style',
			$div->getAttribute( 'style' ) . 'min-height:' . $block['attrs']['minHeight']
		);
	}

	return $dom->saveHTML();
}
