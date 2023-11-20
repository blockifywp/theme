<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/group', NS . 'render_grid_block_variation', 10, 2 );
/**
 * Render grid block variation.
 *
 * @since 0.4.0
 *
 * @param string $content Block content.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_grid_block_variation( string $content, array $block ): string {
	$orientation = $block['attrs']['layout']['orientation'] ?? '';

	if ( $orientation !== 'grid' ) {
		return $content;
	}

	$vertical_alignment = $block['attrs']['layout']['verticalAlignment'] ?? '';

	if ( ! $vertical_alignment ) {
		$dom                   = dom( $content );
		$div                   = get_dom_element( 'div', $dom );
		$styles                = css_string_to_array( $div->getAttribute( 'style' ) );
		$styles['align-items'] = 'stretch';

		$div->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	return $content;
}
