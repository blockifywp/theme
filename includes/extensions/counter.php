<?php

declare( strict_types=1 );

namespace Blockify\Theme;

\add_filter( 'render_block_core/paragraph', NS . 'render_counter_block_variation', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 0.9.10
 *
 * @param string $content Block html content.
 * @param array  $block   Block data.
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

	if ( ! $p ) {
		return $content;
	}

	foreach ( $counter as $attribute => $value ) {
		$p->setAttribute( "data-$attribute", (string) $value );
	}

	$p->textContent = trim( $p->textContent );

	$content = $dom->saveHTML();

	return $content;
}


add_filter( 'blockify_inline_js', NS . 'add_counter_js', 10, 2 );
/**
 * Conditionally add counter JS.
 *
 * @since 0.9.10
 *
 * @param string $js      Inline js.
 * @param string $content Block html content.
 *
 * @return string
 */
function add_counter_js( string $js, string $content ): string {
	if ( str_contains( $content, 'is-style-counter' ) ) {
		$js .= file_get_contents( DIR . 'assets/js/counter.js' );
	}

	return $js;
}

