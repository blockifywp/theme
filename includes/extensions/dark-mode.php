<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function array_key_exists;
use function is_admin;
use function is_string;
use function wp_add_inline_style;
use function wp_get_global_settings;

add_action( 'blockify_editor_scripts', NS . 'add_dark_mode_custom_properties', 11 );
add_action( 'wp_enqueue_scripts', NS . 'add_dark_mode_custom_properties', 11 );
/**
 * Adds dark mode custom properties.
 *
 * @since 0.0.24
 *
 * @return void
 */
function add_dark_mode_custom_properties(): void {
	$global_settings = wp_get_global_settings();

	if ( ! isset( $global_settings['color']['palette']['theme'] ) ) {
		return;
	}

	$colors = [];

	foreach ( $global_settings['color']['palette']['theme'] as $color ) {
		$colors[ $color['slug'] ] = $color['color'];
	}

	$original   = [];
	$properties = [];

	// Allows colors to be filtered with PHP, or removed in child theme json.
	$config = [
		'neutral-900' => 'neutral-100',
		'neutral-800' => 'neutral-200',
		'neutral-700' => 'neutral-200',
		'neutral-600' => 'neutral-300',
		'neutral-500' => 'neutral-300',
		'neutral-400' => 'neutral-400',
		'neutral-300' => 'neutral-500',
		'neutral-200' => 'neutral-500',
		'neutral-100' => 'neutral-600',
		'neutral-50'  => 'neutral-800',
		'neutral-25'  => 'neutral-800',
		'white'       => 'neutral-900',
	];

	foreach ( $colors as $slug => $color ) {
		if ( ! isset( $config[ $slug ] ) || ! is_string( $config[ $slug ] ) ) {
			continue;
		}

		if ( ! array_key_exists( $config[ $slug ], $colors ) ) {
			continue;
		}

		$original[ '--wp--preset--color--' . $slug ]   = $color;
		$properties[ '--wp--preset--color--' . $slug ] = $colors[ $config[ $slug ] ];
	}

	$body_element = is_admin() ? 'body .has-dark-mode' : 'body';
	$new_css      = '@media(prefers-color-scheme:dark){' . $body_element . '{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}}';

	$new_css = '.is-dark-mode{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}.is-light-mode{';

	foreach ( $original as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$new_css
	);
}

