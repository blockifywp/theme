<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Theme_JSON_Resolver;
use function _wp_to_kebab_case;
use function add_filter;
use function array_diff;
use function array_replace_recursive;
use function file_exists;
use function filter_input;
use function get_template_directory;
use function strlen;
use function wp_json_file_decode;
use const FILTER_SANITIZE_FULL_SPECIAL_CHARS;
use const INPUT_COOKIE;
use const INPUT_GET;

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
	$cookie         = filter_input( INPUT_COOKIE, 'blockifyDarkMode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$url_param      = filter_input( INPUT_GET, 'dark_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$stylesheet_dir = get_stylesheet_directory();
	$default_mode   = file_exists( $stylesheet_dir . '/styles/light.json' ) ? 'dark' : 'light';
	$both_classes   = [ 'is-style-light', 'is-style-dark' ];

	$classes[] = 'default-mode-' . $default_mode;

	if ( ! $cookie ) {
		$classes[] = 'is-style-' . $default_mode;
	}

	if ( $cookie === 'true' ) {
		$classes[] = 'is-style-dark';
	} else if ( $cookie === 'false' ) {
		$classes[] = 'is-style-light';
	} else if ( $cookie === 'auto' ) {
		$classes = array_diff( $classes, $both_classes );

		$classes[] = 'default-mode-auto';
	}

	if ( $url_param ) {
		$classes = array_diff( $classes, $both_classes );

		$classes[] = $url_param === 'true' ? 'is-style-dark' : 'is-style-light';
	}

	return $classes;
}

add_filter( 'blockify_inline_css', NS . 'add_dark_mode_styles' );
/**
 * Adds dark mode styles.
 *
 * @since 1.3.0
 *
 * @param string $css Inline CSS.
 *
 * @return string
 */
function add_dark_mode_styles( string $css ): string {
	$variations     = WP_Theme_JSON_Resolver::get_style_variations();
	$user_data      = WP_Theme_JSON_Resolver::get_user_data();
	$theme_data     = WP_Theme_JSON_Resolver::get_theme_data();
	$theme_title    = $theme_data->get_data()['title'] ?? '';
	$theme_settings = $theme_data->get_settings();
	$file_data      = wp_json_file_decode( get_template_directory() . '/theme.json', [ 'associative' => true ] );
	$file_colors    = get_color_values( $file_data['settings']['color']['palette'] ?? [] );
	$theme_colors   = get_color_values( $theme_settings['color']['palette']['theme'] ?? [] );
	$user_colors    = get_color_values( $user_data->get_settings()['color']['palette']['theme'] ?? [] );
	$changed_colors = array_diff( $user_colors, $theme_colors );
	$system_colors  = get_system_colors();
	$replacements   = get_replacement_colors( array_replace_recursive( $file_data['settings'], $theme_settings ) );
	$flipped        = array_flip( $replacements );

	if ( $theme_title ) {
		$variations[] = [
			'title'    => $theme_title,
			'settings' => [
				'color' => [
					'palette'   => [
						'theme' => $theme_settings['color']['palette']['theme'] ?? [],
					],
					'gradients' => [
						'theme' => $theme_settings['color']['gradients']['theme'] ?? [],
					],
				],
			],
		];
	}

	$modes = [];

	foreach ( $variations as $variation ) {
		$mode = _wp_to_kebab_case( $variation['title'] ?? '' );

		if ( ! $mode || in_array( $mode, $modes, true ) ) {
			continue;
		}

		$modes[] = $mode;

		$palette = get_color_values( $variation['settings']['color']['palette']['theme'] ?? [] );

		if ( ! $palette ) {
			continue;
		}

		$styles = [];

		foreach ( $palette as $slug => $value ) {
			if ( array_contains_any( $system_colors, [ $slug, $value ] ) ) {
				continue;
			}

			$new_value = '';

			if ( isset( $changed_colors[ $slug ] ) ) {
				$reversed  = reverse_color_shade( $slug );
				$new_value = $changed_colors[ $reversed ] ?? $user_colors[ $reversed ] ?? $file_colors[ $reversed ] ?? $value;
			}

			if ( isset( $replacements[ $slug ] ) ) {
				$new_slug  = $flipped[ reverse_color_shade( $replacements[ $slug ] ) ] ?? '';
				$new_value = $changed_colors[ $new_slug ] ?? $user_colors[ $new_slug ] ?? ( $changed_colors[ $slug ] ?? '' ? $user_colors[ $slug ] : '' ) ?? $file_colors[ $new_slug ] ?? '';

				if ( strlen( $new_value ) > 7 && ! isset( $changed_colors[ $slug ] ) ) {
					$new_value = $value;
				}
			}

			if ( $new_value ) {
				$value = $new_value;
			}

			$styles["--wp--preset--color--{$slug}"] = "var(--wp--preset--color--custom-dark-mode-{$slug},{$value});";
		}

		$gradients = get_color_values( $variation['settings']['color']['gradients']['theme'] ?? [], 'gradient' );

		foreach ( $gradients as $slug => $value ) {
			$styles["--wp--preset--gradient--{$slug}"] = "var(--wp--preset--gradient--custom-dark-mode-{$slug},{$value});";
		}

		if ( ! empty( $styles ) ) {
			$css .= ".is-style-{$mode}:not(.default-mode-{$mode}){" . css_array_to_string( $styles ) . '}';
		}
	}

	$file = get_dir() . 'assets/css/extensions/dark-mode.css';

	if ( file_exists( $file ) ) {
		$css .= file_get_contents( $file );
	}

	return $css;
}
