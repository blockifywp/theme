<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function array_unique;
use function explode;
use function file_exists;
use function file_get_contents;
use function str_contains;
use function str_replace;

/**
 * Gets animations from stylesheet.
 *
 * @since 0.9.18
 *
 * @return array
 */
function get_animations(): array {

	$file = DIR . 'assets/css/extensions/animation.css';

	if ( ! file_exists( $file ) ) {
		return [];
	}

	$parts      = explode( '@keyframes', file_get_contents( $file ) );
	$animations = [];

	unset( $parts[0] );

	foreach ( $parts as $animation ) {
		$name = trim( explode( '{', $animation )[0] ?? '' );

		$animations[ $name ] = str_replace( $name, '', $animation );
	}

	return $animations;
}

/**
 * Returns inline styles for animations.
 *
 * @since 0.9.19
 *
 * @param string $content  Page content.
 * @param bool   $is_editor Is admin.
 *
 * @return string
 */
function get_animation_styles( string $content, bool $is_editor ): string {
	$animations = get_animations();
	$css        = '';

	foreach ( $animations as $name => $animation ) {
		if ( $is_editor || str_contains( $content, "--wp--custom--animation--name:{$name}" ) ) {
			$css .= "@keyframes $name" . trim( $animation );
		}
	}

	return $css;
}

add_filter( 'blockify_editor_data', NS . 'add_animation_names' );
/**
 * Adds animation names to editor so they are available for options.
 *
 * @since 0.9.19
 *
 * @param array $data Editor data.
 *
 * @return array
 */
function add_animation_names( array $data ): array {
	$animations = get_animations();

	$data['animations'] = array_keys( $animations );

	return $data;
}

add_filter( 'blockify_inline_js', NS . 'add_animation_js', 10, 2 );
/**
 * Conditionally add animation JS.
 *
 * @since 0.9.10
 *
 * @param string $js      The inline JS.
 * @param string $content The block content.
 *
 * @return string
 */
function add_animation_js( string $js, string $content ): string {
	if ( str_contains( $content, ' has-animation' ) ) {
		$js .= file_get_contents( DIR . 'assets/js/animation.js' );
	}

	return $js;
}

add_filter( 'render_block', NS . 'render_animation_attributes', 10, 2 );
/**
 * Adds animation attributes to block.
 *
 * @since 0.9.10
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_animation_attributes( string $html, array $block ): string {
	$animation = $block['attrs']['animation'] ?? [];

	if ( empty( $animation ) ) {
		return $html;
	}

	$infinite = ( $animation['iterationCount'] ?? null ) === '-1' || ( $animation['event'] ?? null ) === 'infinite';

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$classes = explode( ' ', $first->getAttribute( 'class' ) );
	$classes = array_unique( $classes );
	$styles  = css_string_to_array( $first->getAttribute( 'style' ) );

	unset( $styles['animation-play-state'] );

	if ( $infinite ) {
		unset( $styles['--animation-event'] );

		$styles['animation-iteration-count'] = 'infinite';

	} else {
		unset( $styles['animation-name'] );

		$styles['--animation-name'] = $animation['name'] ?? '';
	}

	$first->setAttribute( 'style', css_array_to_string( $styles ) );
	$first->setAttribute( 'class', implode( ' ', $classes ) );

	return $dom->saveHTML();
}

