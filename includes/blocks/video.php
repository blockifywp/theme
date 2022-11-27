<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_filter;
use function add_theme_support;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_enqueue_style;

add_action( 'after_setup_theme', NS . 'theme_supports' );
/**
 * Handles theme supports.
 *
 * @since 0.0.2
 *
 * @return void
 */
function theme_supports(): void {
	add_theme_support( 'responsive-embeds' );
}

add_filter( 'render_block_core/video', NS . 'render_video_block', 11, 2 );
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
function render_video_block( string $html, array $block ): string {

	$dom = dom( $html );

	$figure = get_dom_element( 'figure', $dom );

	if ( ! $figure ) {
		return $html;
	}

	$styles     = css_string_to_array( $figure->getAttribute( 'style' ) );
	$background = $styles['background'] ?? $styles['background-color'] ?? '';

	if ( $background ) {
		$styles['--wp--custom--video--background'] = $background;

		unset( $styles['background'], $styles['background-color'] );
	}

	$figure->setAttribute( 'style', css_array_to_string( $styles ) );

	$html = $dom->saveHTML();

	add_action( 'wp_enqueue_scripts', NS . 'video_scripts_styles' );

	return $html;
}

/**
 * Enqueue media element scripts and styles.
 *
 * @since 0.0.2
 *
 * @return void
 */
function video_scripts_styles(): void {
	$js = <<<JS
		const videoBlocks = document.getElementsByTagName( 'video' );

		[ ...videoBlocks ].forEach( function( videoBlock ) {
			new MediaElementPlayer( videoBlock, {
				videoWidth: '100%',
				videoHeight: '100%',
				enableAutosize: true
			} );

			videoBlock.style.width = '100%';
			videoBlock.style.height = '100%';
		} );
	JS;

	wp_enqueue_script( 'wp-mediaelement' );
	wp_enqueue_style( 'wp-mediaelement' );
	wp_add_inline_script( 'wp-mediaelement', $js );
}
