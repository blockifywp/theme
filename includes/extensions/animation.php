<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_search;
use function explode;
use function file_get_contents;
use function implode;
use function in_array;
use function str_contains;
use function str_replace;

add_filter( 'render_block', NS . 'render_block_animation', 10, 2 );
/**
 * Add animation class to block
 *
 * @param string $content The block content about to be appended.
 * @param array  $block   The full block, including name and attributes.
 *
 * @return string
 */
function render_block_animation( string $content, array $block ): string {
	$animation = $block['attrs']['style']['animation'] ?? [];

	if ( ! $animation ) {
		return $content;
	}

	$event      = $animation['event'] ?? '';
	$iterations = $animation['iterationCount'] ?? '';
	$infinite   = $event === 'infinite' || in_array( $iterations, [ 'infinite', '-1' ], true );

	if ( ! $infinite && $event !== 'none' ) {
		$dom   = dom( $content );
		$first = get_dom_element( '*', $dom );

		$classes = explode( ' ', $first->getAttribute( 'class' ) );

		unset( $classes[ array_search( 'has-animation', $classes ) ] );
		$classes[] = 'will-animate';

		if ( $event === 'enter-infinite' ) {
			$classes[] = 'has-enter-infinite';
		}

		$first->setAttribute( 'class', implode( ' ', $classes ) );
		$styles = css_string_to_array( $first->getAttribute( 'style' ) );
		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$content = $dom->saveHTML();
	}

	$content = str_replace( ';animation-play-state:paused', '', $content );

	return $content;
}

add_filter( 'blockify_inline_js', NS . 'add_animation_js', 10, 2 );
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
function add_animation_js( string $js, string $content ): string {
	if ( str_contains( $content, ' will-animate' ) ) {
		$js .= file_get_contents( DIR . 'assets/js/animation.js' );
	}

	return $js;
}

