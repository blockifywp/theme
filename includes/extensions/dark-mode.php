<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function is_admin;
use function wp_add_inline_style;
use function wp_get_global_settings;
use function wp_get_global_styles;

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
	$global_settings     = wp_get_global_settings();
	$dark_mode_colors    = $global_settings['custom']['darkMode']['palette'] ?? [];
	$dark_mode_gradients = $global_settings['custom']['darkMode']['gradients'] ?? [];

	if ( ! $dark_mode_colors && ! $dark_mode_gradients ) {
		return;
	}

	foreach ( $dark_mode_colors as $slug => $color ) {
		$styles[ '--wp--preset--color--' . $slug ] = "var(--wp--preset--color--custom-dark-mode-$slug,$color)";
	}

	foreach ( $dark_mode_gradients as $slug => $gradient ) {
		$styles[ '--wp--preset--gradient--' . $slug ] = "var(--wp--preset--gradient--custom-dark-mode-$slug,$gradient)";
	}

	$global_styles = wp_get_global_styles();

	$styles['background'] = format_custom_property( $global_styles['color']['background'] ?? null );
	$styles['color']      = format_custom_property( $global_styles['color']['text'] ?? null );

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		'.is-style-dark{' . css_array_to_string( $styles ) . '}'
	);
}
