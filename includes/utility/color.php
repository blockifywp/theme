<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function is_admin;
use function str_replace;
use function wp_get_global_settings;
use function wp_get_global_styles;

/**
 * Applies block color attributes.
 *
 * @param array $styles Array of styles.
 * @param array $attrs  Array of attributes.
 *
 * @since 0.9.10
 *
 * @return array
 */
function add_block_support_color( array $styles, array $attrs ): array {
	$color = $attrs['style']['color'] ?? [];

	if ( isset( $color['background'] ) ) {
		$styles['background'] = $color['background'];
	}

	if ( isset( $attrs['backgroundColor'] ) ) {
		$styles['background'] = 'var(--wp--preset--color--' . $attrs['backgroundColor'] . ')';
	}

	if ( isset( $color['gradient'] ) ) {
		$styles['background'] = $color['gradient'];
	}

	if ( isset( $attrs['gradient'] ) ) {
		$styles['background'] = 'var(--wp--preset--gradient--' . $attrs['gradient'] . ')';
	}

	if ( isset( $color['text'] ) ) {
		$styles['color'] = $color['text'];
	}

	if ( isset( $attrs['textColor'] ) ) {
		$styles['color'] = 'var(--wp--preset--color--' . $attrs['textColor'] . ')';
	}

	return $styles;
}

/**
 * Returns dark mode custom properties.
 *
 * @since 0.0.24
 *
 * @return string
 */
function get_dark_mode_custom_properties(): string {
	$global_settings = wp_get_global_settings();

	$dark_mode  = $global_settings['custom']['darkMode'] ?? [];
	$light_mode = $global_settings['custom']['lightMode'] ?? [];

	if ( ! $dark_mode && ! $light_mode ) {
		return '';
	}

	$mode = ( $light_mode && ! $dark_mode ) ? 'lightMode' : 'darkMode';

	$dark_mode_colors    = $global_settings['custom'][ $mode ]['palette'] ?? [];
	$dark_mode_gradients = $global_settings['custom'][ $mode ]['gradients'] ?? [];
	$css                 = '';

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
		return '.is-style-light{' . css_array_to_string( $styles ) . '}.is-style-dark{' . css_array_to_string( $light ) . '}';
	}

	return '.is-style-dark{' . css_array_to_string( $styles ) . '}.is-style-light{' . css_array_to_string( $light ) . '}';
}
