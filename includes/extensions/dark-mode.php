<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function preg_replace;
use function str_replace;
use function strtolower;
use function wp_get_global_settings;
use function wp_get_global_styles;

add_filter( 'body_class', NS . 'add_default_mode_body_class' );
/**
 * Adds default mode body class.
 *
 * @param array $classes Array of body classes.
 *
 * @since 1.2.4
 *
 * @return array
 */
function add_default_mode_body_class( array $classes ): array {
	$global_settings = wp_get_global_settings();
	$dark_mode       = $global_settings['custom']['darkMode'] ?? [];
	$light_mode      = $global_settings['custom']['lightMode'] ?? [];
	$classes[]       = $light_mode && ! $dark_mode ? 'default-mode-dark' : 'default-mode-light';

	return $classes;
}

add_filter( 'blockify_inline_css', NS . 'get_dark_mode_custom_properties', 9 );
/**
 * Returns dark mode custom properties.
 *
 * @param string $css Inline CSS.
 *
 * @since 0.0.24
 *
 * @return string
 */
function get_dark_mode_custom_properties( string $css ): string {
	$global_settings = wp_get_global_settings();

	$dark_mode  = $global_settings['custom']['darkMode'] ?? [];
	$light_mode = $global_settings['custom']['lightMode'] ?? [];

	if ( ! $dark_mode && ! $light_mode ) {
		return $css;
	}

	$mode = ( $light_mode && ! $dark_mode ) ? 'lightMode' : 'darkMode';

	$dark_mode_colors    = $global_settings['custom'][ $mode ]['palette'] ?? [];
	$dark_mode_gradients = $global_settings['custom'][ $mode ]['gradients'] ?? [];

	if ( ! $dark_mode_colors && ! $dark_mode_gradients ) {
		return $css;
	}

	foreach ( $dark_mode_colors as $slug => $color ) {
		$slug = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $slug ) );

		$styles[ '--wp--preset--color--' . $slug ] = "var(--wp--preset--color--custom-dark-mode-$slug,$color)";
	}

	foreach ( $dark_mode_gradients as $slug => $gradient ) {
		$slug = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', $slug ) );

		$styles[ '--wp--preset--gradient--' . $slug ] = "var(--wp--preset--gradient--custom-dark-mode-$slug,$gradient)";
	}

	$global_styles          = wp_get_global_styles();
	$styles['background']   = format_custom_property( $global_styles['color']['background'] ?? '' );
	$styles['color']        = format_custom_property( $global_styles['color']['text'] ?? '' );
	$theme_color_palette    = $global_settings['color']['palette']['theme'] ?? [];
	$theme_gradient_palette = $global_settings['color']['gradients']['theme'] ?? [];

	$light = [];

	$light_background_slug = str_replace(
		[
			'var(--wp--preset--color--',
			'var(--wp--preset--gradient--',
			')',
		],
		'',
		$global_styles['color']['background'] ?? ''
	);

	$light_text_slug = str_replace(
		[
			'var(--wp--preset--color--',
			'var(--wp--preset--gradient--',
			')',
		],
		'',
		$global_styles['color']['text'] ?? ''
	);

	foreach ( $theme_color_palette as $color ) {
		if ( $light_background_slug === $color['slug'] ) {
			$light['background'] = $color['color'];
		}

		if ( $light_text_slug === $color['slug'] ) {
			$light['color'] = $color['color'];
		}
	}

	foreach ( $theme_gradient_palette as $gradient ) {
		if ( $light_background_slug === $gradient['slug'] ) {
			$light['background'] = $gradient['gradient'];
		}
	}

	if ( ! is_admin() ) {
		foreach ( $theme_color_palette as $color ) {
			$light[ '--wp--preset--color--' . $color['slug'] ] = $color['color'];
		}

		foreach ( $theme_gradient_palette as $gradient ) {
			$light[ '--wp--preset--gradient--' . $gradient['slug'] ] = $gradient['gradient'];
		}
	}

	if ( $mode === 'lightMode' ) {
		return $css . '.is-style-light{' . css_array_to_string( $styles ) . '}.is-style-dark{' . css_array_to_string( $light ) . '}';
	}

	return $css . '.is-style-dark{' . css_array_to_string( $styles ) . '}.is-style-light{' . css_array_to_string( $light ) . '}';
}
