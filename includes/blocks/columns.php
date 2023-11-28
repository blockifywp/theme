<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_unique;
use function count;
use function explode;
use function implode;
use function in_array;
use function str_replace;

add_filter( 'render_block_core/columns', NS . 'render_columns_block', 10, 2 );
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
function render_columns_block( string $html, array $block ): string {
	$class      = 'is-stacked-on-mobile';
	$is_stacked = $block['attrs']['stackedOnMobile'] ?? null;

	if ( $is_stacked && $block['attrs']['isStackedOnMobile'] === false ) {
		$class = 'is-not-stacked-on-mobile';
	}

	if ( $class === 'is-stacked-on-mobile' ) {
		$html = str_replace( 'wp-block-columns ', 'wp-block-columns is-stacked-on-mobile ', $html );
		$dom  = dom( $html );
		$div  = get_dom_element( 'div', $dom );

		if ( $div ) {
			$div_classes = explode( ' ', $div->getAttribute( 'class' ) );

			if ( ! in_array( $class, $div_classes ) ) {
				$div_classes[] = $class;
			}

			$div_classes = array_unique( $div_classes );

			$div->setAttribute( 'class', implode( ' ', $div_classes ) );
		}

		$html = $dom->saveHTML();
	}

	$margin = $block['attrs']['style']['spacing']['margin'] ?? null;

	if ( $margin ) {
		$dom   = dom( $html );
		$first = get_dom_element( 'div', $dom );

		if ( $first ) {
			$styles = css_string_to_array( $first->getAttribute( 'style' ) );
			$styles = add_shorthand_property( $styles, 'margin', $margin );

			$first->setAttribute( 'style', css_array_to_string( $styles ) );
		}

		$html = $dom->saveHTML();
	}

	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );

	if ( $div ) {
		$column_count = (string) count( $block['innerBlocks'] ?? 0 );

		$div->setAttribute( 'data-columns', $column_count );

		$html = $dom->saveHTML();
	}

	return $html;
}
