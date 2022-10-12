<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function array_flip;
use function array_merge;
use function basename;
use function file_exists;
use function file_get_contents;
use function glob;
use function is_a;
use function is_admin;
use function is_admin_bar_showing;
use function is_array;
use function str_replace;
use function wp_add_inline_style;
use function wp_dequeue_style;
use function wp_get_global_settings;
use function wp_get_global_styles;

add_action( 'blockify_editor_scripts', NS . 'add_dynamic_custom_properties' );
add_action( 'wp_enqueue_scripts', NS . 'add_dynamic_custom_properties' );
/**
 * Adds custom properties.
 *
 * @since 0.0.19
 *
 * @return void
 */
function add_dynamic_custom_properties(): void {
	$settings       = wp_get_global_settings();
	$global_styles  = wp_get_global_styles();
	$element        = is_admin() ? 'body,.editor-styles-wrapper' : 'body';
	$border_width   = $settings['custom']['border']['width'] ?? '1px';
	$border_style   = $settings['custom']['border']['style'] ?? 'solid';
	$border_color   = $settings['custom']['border']['color'] ?? '#ddd';
	$bodyBackground = $global_styles['color']['background'] ?? null;
	$body_color     = $global_styles['color']['text'] ?? null;
	$box_shadow     = $settings['custom']['boxShadow'] ?? [];

	// Button.
	$button_block         = $global_styles['blocks']['core/button'] ?? [];
	$button_element       = $global_styles['elements']['button'] ?? [];
	$button_text          = $button_element['color']['text'] ?? $button_block['color']['text'] ?? null;
	$button_background    = $button_element['color']['background'] ?? $button_block['color']['background'] ?? null;
	$button_border_radius = $button_element['border']['radius'] ?? $button_block['border']['radius'] ?? null;
	$button_border_width  = $button_element['border']['width'] ?? $button_block['border']['width'] ?? null;
	$button_font_size     = $button_element['typography']['fontSize'] ?? $button_block['typography']['fontSize'] ?? null;
	$button_font_weight   = $button_element['typography']['fontWeight'] ?? $button_block['typography']['fontWeight'] ?? null;
	$button_line_height   = $button_element['typography']['lineHeight'] ?? $button_block['typography']['lineHeight'] ?? null;
	$button_padding       = $button_element['spacing']['padding'] ?? $button_block['spacing']['padding'] ?? null;

	$all = [
		'--wp--custom--border'                 => "$border_width $border_style $border_color",
		'--wp--custom--body--background'       => $bodyBackground,
		'--wp--custom--body--color'            => $body_color,

		// .wp-element-button workaround. Also used by input and enables gradient.
		'--wp--custom--button--background'     => $button_background,
		'--wp--custom--button--color'          => $button_text,
		'--wp--custom--button--padding-top'    => $button_padding['top'] ?? null,
		'--wp--custom--button--padding-right'  => $button_padding['right'] ?? null,
		'--wp--custom--button--padding-bottom' => $button_padding['bottom'] ?? null,
		'--wp--custom--button--padding-left'   => $button_padding['left'] ?? null,
		'--wp--custom--button--border-radius'  => $button_border_radius,
		'--wp--custom--button--border-width'   => $button_border_width,
		'--wp--custom--button--font-size'      => $button_font_size,
		'--wp--custom--button--font-weight'    => $button_font_weight,
		'--wp--custom--button--line-height'    => $button_line_height,
	];

	if ( $box_shadow ) {
		$all = array_merge(
			$all,
			[
				'--wp--custom--box-shadow--inset'  => $box_shadow['inset'] ?? ' ',
				'--wp--custom--box-shadow--x'      => $box_shadow['x'] ?? null,
				'--wp--custom--box-shadow--y'      => $box_shadow['y'] ?? null,
				'--wp--custom--box-shadow--blur'   => $box_shadow['blur'] ?? null,
				'--wp--custom--box-shadow--spread' => $box_shadow['spread'] ?? null,
				'--wp--custom--box-shadow--color'  => $box_shadow['color'] ?? null,
				'--wp--custom--box-shadow--radius' => $box_shadow['radius'] ?? null,
			]
		);
	}

	$css = $element . '{' . css_array_to_string( $all ) . '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$css
	);
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_block_styles' );
/**
 * Enqueues front end scripts.
 *
 * @since 0.0.2
 *
 * @return void
 */
function enqueue_block_styles(): void {
	global $wp_styles;

	wp_dequeue_style( 'wp-block-library-theme' );

	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		return;
	}

	$handles = array_flip( $wp_styles->queue );

	foreach ( $wp_styles->registered as $handle => $style ) {
		if ( ! isset( $handles[ $handle ] ) ) {
			continue;
		}

		$slug = str_replace( 'wp-block-', '', $handle );
		$file = DIR . 'assets/css/blocks/' . $slug . '.css';

		if ( file_exists( $file ) ) {
			wp_add_inline_style(
				$handle,
				file_get_contents( $file )
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', NS . 'add_conditional_styles' );
/**
 * Adds split styles.
 *
 * @since 0.0.27
 *
 * @return void
 */
function add_conditional_styles(): void {
	$styles = '';

	$stylesheets = [
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/formats/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
		...glob( DIR . 'assets/css/utility/*.css' ),
		...glob( DIR . 'assets/css/plugins/*.css' ),
	];

	$conditions = [
		'admin-bar' => is_admin_bar_showing(),
		'wp-org'    => is_pattern_preview(),
	];

	foreach ( $stylesheets as $stylesheet ) {
		if ( $conditions[ basename( $stylesheet, '.css' ) ] ?? true ) {
			$styles .= trim( file_get_contents( $stylesheet ) );
		}
	}

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$styles
	);
}
