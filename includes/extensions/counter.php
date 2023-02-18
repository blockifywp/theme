<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function file_get_contents;
use function str_contains;
use function trim;

add_filter( 'render_block_core/paragraph', NS . 'render_counter_block_variation', 10, 2 );
/**
 * Render counter block markup.
 *
 * @since 0.9.10
 *
 * @param string $html  Block html content.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_counter_block_variation( string $html, array $block ): string {
	$counter = $block['attrs']['style']['counter'] ?? '';

	if ( ! $counter ) {
		return $html;
	}

	$dom = dom( $html );
	$p   = get_dom_element( 'p', $dom );

	if ( ! $p ) {
		return $html;
	}

	foreach ( $counter as $attribute => $value ) {
		$p->setAttribute( "data-$attribute", (string) $value );
	}

	$p->textContent = trim( $p->textContent );

	return $dom->saveHTML();
}

add_filter( 'blockify_inline_js', NS . 'add_counter_js', 10, 2 );
/**
 * Conditionally add counter JS.
 *
 * @since 0.9.10
 *
 * @param string $js   Inline js.
 * @param string $html Block html content.
 *
 * @return string
 */
function add_counter_js( string $js, string $html ): string {
	if ( str_contains( $html, 'is-style-counter' ) ) {
		$js .= file_get_contents( get_dir() . 'assets/js/counter.js' );
	}

	return $js;
}

