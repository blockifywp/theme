<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function array_replace;
use function get_stylesheet_directory;
use function get_template_directory;
use function wp_get_global_settings;
use function wp_json_file_decode;

/**
 * Gets system colors.
 *
 * @since 1.3.0
 *
 * @return array
 */
function get_system_colors(): array {
	return [
		'currentcolor',
		'currentColor',
		'inherit',
		'initial',
		'transparent',
		'unset',
	];
}

/**
 * Gets color shades.
 *
 * @since 1.3.0
 *
 * @param ?string $color Color slug.
 *
 * @return array
 */
function get_shade_scales( ?string $color = null ): array {
	$map = [
		'neutral' => [
			950 => 0,
			900 => 50,
			800 => 100,
			700 => 200,
			600 => 300,
			500 => 400,
			400 => 500,
			300 => 600,
			200 => 700,
			100 => 800,
			50  => 900,
			0   => 950,
		],
		'primary' => [
			900 => 100,
			700 => 300,
			600 => 500,
			500 => 600,
			300 => 700,
			100 => 900,
		],
		'accent'  => [
			900 => 100,
			700 => 300,
			600 => 500,
			500 => 600,
			300 => 700,
			100 => 900,
		],
		'success' => [
			600 => 100,
			500 => 500,
			100 => 600,
		],
		'warning' => [
			600 => 100,
			500 => 500,
			100 => 600,
		],
		'error'   => [
			600 => 100,
			500 => 500,
			100 => 600,
		],
	];

	return $color ? ( $map[ $color ] ?? [] ) : $map;
}

/**
 * Gets color shades.
 *
 * @since 1.3.2
 *
 * @param string $slug Color slug.
 *
 * @return string
 */
function reverse_color_shade( string $slug ): string {
	$explode = explode( '-', $slug );
	$color   = $explode[0] ?? '';
	$shade   = $explode[1] ?? '';
	$scale   = get_shade_scales( $color );
	$reverse = $scale[ (int) $shade ] ?? '';

	return $reverse ? "{$color}-{$reverse}" : '';
}

/**
 * Gets color values from a color palette.
 *
 * @since 1.3.0
 *
 * @param array  $colors Color palette.
 * @param string $type   Color or gradient. Default is color.
 *
 * @return array
 */
function get_color_values( array $colors, string $type = 'color' ): array {
	$color_values = [];

	foreach ( $colors as $color ) {
		$color = (array) $color;

		if ( ! isset( $color['slug'], $color[ $type ] ) ) {
			continue;
		}

		$color_values[ $color['slug'] ] = $color[ $type ];
	}

	return $color_values;
}

/**
 * Returns replacements for deprecated colors.
 *
 * @since 1.3.0
 *
 * @param array $settings Global settings.
 *
 * @return array
 */
function get_replacement_colors( array $settings = [] ): array {
	return array_replace(
		[
			'primary-darker'    => 'primary-900',
			'primary-dark'      => 'primary-700',
			'primary'           => 'primary-500',
			'primary-light'     => 'primary-300',
			'primary-lighter'   => 'primary-100',
			'secondary-darker'  => 'primary-900',
			'secondary-dark'    => 'primary-700',
			'secondary'         => 'primary-600',
			'secondary-light'   => 'primary-300',
			'secondary-lighter' => 'primary-100',
			'contrast'          => 'neutral-950',
			'foreground'        => 'neutral-900',
			'heading'           => 'neutral-800',
			'body'              => 'neutral-600',
			'neutral'           => 'neutral-500',
			'outline'           => 'neutral-200',
			'surface'           => 'neutral-100',
			'lighten'           => 'neutral-50',
			'background'        => 'neutral-0',
			'base'              => 'neutral-0',
			'success'           => 'success-500',
			'warning'           => 'warning-500',
			'error'             => 'error-500',
		],
		$settings['custom']['deprecatedColors'] ?? []
	);
}

/**
 * Returns key value pairs of deprecated colors with replacements.
 *
 * @since 1.3.0
 *
 * @return array
 */
function get_deprecated_colors(): array {
	$child_theme_json  = wp_json_file_decode( get_stylesheet_directory() . '/theme.json' );
	$parent_theme_json = wp_json_file_decode( get_template_directory() . '/theme.json' );
	$parent_colors     = get_color_values( $parent_theme_json->settings->color->palette ?? [] );
	$child_colors      = get_color_values( $child_theme_json->settings->color->palette ?? [] );
	$settings          = wp_get_global_settings();
	$replacements      = get_replacement_colors( $settings );
	$user_colors       = get_color_values( $settings['color']['palette']['theme'] ?? [] );
	$default_colors    = array_replace( $parent_colors, $child_colors, $user_colors );

	$has_deprecated = $settings['custom']['deprecatedColors'] ?? false;
	$new_colors     = [];
	$old_colors     = [];

	foreach ( $replacements as $old => $new ) {
		$old = _wp_to_kebab_case( $old );

		if ( isset( $user_colors[ $old ] ) ) {
			$has_deprecated = true;
		}

		if ( ! isset( $user_colors[ $new ] ) ) {
			$value = $user_colors[ $old ] ?? $default_colors[ $new ] ?? '';

			if ( $value ) {
				$new_colors[ $new ] = $value;
			}

			if ( isset( $user_colors[ $old ] ) ) {
				continue;
			}
		}

		$old = _wp_to_kebab_case( $old );

		if ( ! str_contains_any( $new, 'var', '#', 'rgb', 'hsl' ) ) {
			$new = "var(--wp--preset--color--$new)";
		}

		if ( $new ) {
			$old_colors[ $old ] = $new;
		}
	}

	return $has_deprecated ? array_replace( $new_colors, $old_colors ) : [];
}
