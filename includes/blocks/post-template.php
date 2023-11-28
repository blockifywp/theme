<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function is_null;

add_filter( 'render_block_core/post-template', NS . 'render_post_template_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 1.3.2
 *
 * @param string $html  Block content.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_post_template_block( string $html, array $block ): string {
	$block_gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;
	$layout    = $block['attrs']['layout']['type'] ?? null;

	if ( ! is_null( $block_gap ) && $layout !== 'grid' ) {
		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( $first ) {
			$first_styles = css_string_to_array( $first->getAttribute( 'style' ) );

			$first_styles['gap']       = format_custom_property( $block_gap );
			$first_styles['display']   = 'flex';
			$first_styles['flex-wrap'] = 'wrap';

			$first->setAttribute( 'style', css_array_to_string( $first_styles ) );

			$html = $dom->saveHTML();
		}
	}

	return $html;
}
