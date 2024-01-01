<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function str_contains;

add_filter( 'render_block_core/query-pagination', NS . 'render_query_pagination_block', 10, 2 );
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
function render_query_pagination_block( string $html, array $block ): string {
	$dom = dom( $html );
	$nav = get_dom_element( 'nav', $dom );

	if ( ! $nav ) {
		return $html;
	}

	$styles  = css_string_to_array( $nav->getAttribute( 'style' ) );
	$margin  = $block['attrs']['style']['spacing']['margin'] ?? null;
	$padding = $block['attrs']['style']['spacing']['padding'] ?? null;
	$styles  = add_shorthand_property( $styles, 'margin', $margin );
	$styles  = add_shorthand_property( $styles, 'padding', $padding );

	foreach ( $styles as $key => $value ) {
		if ( ! $value ) {
			continue;
		}

		// TODO: Which properties need formatting?
		if ( str_contains( $value, 'var:' ) ) {
			$styles[ $key ] = format_custom_property( $value );
		}
	}

	$border_radius = $block['attrs']['style']['border']['radius'] ?? null;

	if ( $border_radius ) {
		$styles['border-radius'] = $border_radius;
	}

	$nav->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}

