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
 * @param string $html Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_query_pagination_block( string $html, array $block ): string {
	$dom = dom( $html );
	$nav = get_dom_element( 'nav', $dom );

	if ( ! $nav ) {
		return $html;
	}

	$styles = css_string_to_array( $nav->getAttribute( 'style' ) );

	$margin = $block['attrs']['style']['spacing']['margin'] ?? null;

	if ( $margin ) {
		$styles['margin-top']    = $margin['top'] ?? null;
		$styles['margin-right']  = $margin['right'] ?? null;
		$styles['margin-bottom'] = $margin['bottom'] ?? null;
		$styles['margin-left']   = $margin['left'] ?? null;
	}

	foreach ( $styles as $key => $value ) {
		if ( ! $value ) {
			continue;
		}

		if ( str_contains( $value, 'var:' ) ) {
			$styles[ $key ] = format_custom_property( $value );
		}
	}

	$nav->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}

