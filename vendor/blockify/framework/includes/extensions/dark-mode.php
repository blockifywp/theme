<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function str_replace;
use function wp_get_global_settings;
use function wp_get_global_styles;

/**
 * Returns dark mode custom properties.
 *
 * @since 0.0.24
 *
 * @return string
 */
function get_dark_mode_custom_properties(): string {
	$global_settings     = wp_get_global_settings();
	$dark_mode_colors    = $global_settings['custom']['darkMode']['palette'] ?? [];
	$dark_mode_gradients = $global_settings['custom']['darkMode']['gradients'] ?? [];
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

	$global_styles = wp_get_global_styles();

	$styles['background']   = format_custom_property( $global_styles['color']['background'] ?? null );
	$styles['color']        = format_custom_property( $global_styles['color']['text'] ?? null );
	$theme_color_palette    = $global_settings['color']['palette']['theme'] ?? [];
	$theme_gradient_palette = $global_settings['color']['gradients']['theme'] ?? [];

	$options          = get_option( SLUG );
	$color_options    = $options['darkModeColors'] ?? [];
	$gradient_options = $options['darkModeGradients'] ?? [];

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

	foreach ( $theme_color_palette as $color ) {
		$light[ '--wp--preset--color--' . $color['slug'] ] = $color['color'];
	}

	foreach ( $theme_gradient_palette as $gradient ) {
		$light[ '--wp--preset--gradient--' . $gradient['slug'] ] = $gradient['gradient'];
	}

	return $css . '.is-style-dark{' . css_array_to_string( $styles ) . '}.is-style-light{' . css_array_to_string( $light ) . '}';
}

add_filter( 'body_class', NS . 'add_dark_mode_body_class' );
/**
 * Sets default body class.
 *
 * @since 0.9.10
 *
 * @param array $classes Body classes.
 *
 * @return array
 */
function add_dark_mode_body_class( array $classes ): array {
	$dark_mode = ( ( $_COOKIE['blockifyDarkMode'] ?? '' ) === 'true' );

	if ( $dark_mode ) {
		$classes[] = 'is-style-dark';
	}

	return $classes;
}

add_filter( 'blockify_inline_js', NS . 'add_dark_mode_inline_js' );
/**
 * Adds dark mode inline JS.
 *
 * @since 0.9.10
 *
 * @param string $js JS.
 *
 * @return string
 */
function add_dark_mode_inline_js( string $js ): string {
	$options = get_option( SLUG );
	$cookie  = $_COOKIE['blockifyDarkMode'] ?? '';

	if ( $cookie === 'false' ) {
		return $js;
	}

	if ( $options['autoDarkMode'] ?? null ) {
		$js = <<<JS
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.body.classList.add('is-style-dark');
}
JS;
	}

	return $js;
}
