<?php

declare( strict_types=1 );

namespace Blockify\Theme;

\add_filter( 'render_block_core/paragraph', NS . 'render_counter_block_variation', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_counter_block_variation( string $content, array $block ): string {
	$counter = $block['attrs']['style']['counter'] ?? '';

	if ( ! $counter ) {
		return $content;
	}

	$dom = dom( $content );
	$p   = get_dom_element( 'p', $dom );

	foreach ( $counter as $attribute => $value ) {
		$p->setAttribute( "data-$attribute", $value );
	}

	$content = $dom->saveHTML();

	return $content;
}


add_filter( 'blockify_inline_js', NS . 'add_counter_js', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $js
 * @param string $content
 *
 * @return string
 */
function add_counter_js( string $js, string $content ): string {
	if ( str_contains( $content, 'is-style-counter' ) ) {
		$js .= file_get_contents( DIR . 'assets/js/counter.js' );
	}

	return $js;
}

