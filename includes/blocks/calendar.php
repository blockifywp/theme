<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;

add_filter( 'render_block_core/calendar', NS . 'render_block_core_calendar', 10, 2 );
/**
 * Render core/calendar block.
 *
 * @param string $html  The block content being rendered.
 * @param array  $block The block being rendered.
 *
 * @return string
 */
function render_block_core_calendar( string $html, array $block ): string {
	$dom   = dom( $html );
	$div   = get_dom_element( 'div', $dom );
	$table = get_dom_element( 'table', $div );

	if ( ! $table ) {
		return $html;
	}

	$div_classes   = explode( ' ', $div->getAttribute( 'class' ) );
	$table_classes = explode( ' ', $table->getAttribute( 'class' ) );

	foreach ( $table_classes as $index => $table_class ) {
		if ( str_contains_any( $table_class, 'background', 'color' ) ) {
			$div_classes[] = $table_class;
			unset( $table_classes[ $index ] );
		}
	}

	$div->setAttribute( 'class', implode( ' ', $div_classes ) );
	$table->setAttribute( 'class', implode( ' ', $table_classes ) );
	
	return $dom->saveHTML();
}
