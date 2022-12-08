<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function apply_filters;
use function array_merge;
use function basename;
use function get_option;
use function in_array;
use function wp_get_global_settings;
use function wp_list_pluck;

add_filter( 'wp_theme_json_data_theme', NS . 'add_fonts' );
/**
 * Add all fonts to the editor.
 *
 * @param mixed $theme_json Theme JSON.
 *
 * @return mixed
 */
function add_fonts( $theme_json ) {
	$data = $theme_json->get_data();

	$data['settings']['typography']['fontFamilies']['theme'] = array_merge(
		get_system_fonts(),
		$data['settings']['typography']['fontFamilies']['theme'] ?? [],
		get_selected_fonts(),
	);

	$theme_json->update_with( $data );

	return $theme_json;
}

/**
 * Returns array of user selected font families.
 *
 * @since 0.4.0
 *
 * @return array
 */
function get_selected_fonts(): array {
	$path           = get_asset_path();
	$font_families  = get_option( SLUG )['googleFonts'] ?? [];
	$selected_fonts = [];

	foreach ( $font_families as $font_family ) {
		$slug = $font_family['value'] ?? '';
		$name = $font_family['label'] ?? '';

		if ( ! $slug || ! $name ) {
			continue;
		}

		if ( in_array( $slug, [ 'sans-serif', 'serif', 'monospace', 'inherit', 'initial' ], true ) ) {
			continue;
		}

		$selected_fonts[] = [
			'fontFamily' => $name,
			'name'       => $name,
			'slug'       => $slug,
			'fontFace'   => [
				[
					'fontFamily'  => $name,
					'fontStyle'   => 'normal',
					'fontStretch' => 'normal',
					'fontDisplay' => 'swap',
					'fontWeight'  => '100 900',
					'src'         => [
						"file:./{$path}assets/fonts/$slug.woff2",
					],
				],
			],
		];
	}

	return $selected_fonts;
}

/**
 * Returns an array of all available local fonts.
 *
 * @since 0.4.0
 *
 * @return array
 */
function get_all_fonts(): array {
	$font_families = get_system_fonts();
	$font_slugs    = wp_list_pluck( $font_families, 'slug' );
	$font_files    = glob( DIR . 'assets/fonts/*.woff2' );
	$path          = get_asset_path();

	foreach ( $font_files as $font_file ) {
		$slug = basename( $font_file, '.woff2' );

		if ( in_array( $slug, $font_slugs, true ) ) {
			continue;
		}

		$font_slugs[] = $slug;
		$name         = ucwords( str_replace( '-', ' ', $slug ) );

		$font_families[] = [
			'fontFamily' => $name,
			'name'       => $name,
			'slug'       => $slug,
			'fontFace'   => [
				[
					'fontFamily'  => $name,
					'fontStyle'   => 'normal',
					'fontStretch' => 'normal',
					'fontDisplay' => 'swap',
					'fontWeight'  => '100 900',
					'src'         => [
						"file:./{$path}assets/fonts/$slug.woff2",
					],
				],
			],
		];
	}

	return apply_filters( 'blockify_font_families', $font_families );
}

add_filter( 'blockify_editor_data', NS . 'add_font_family_data', 11 );
/**
 * Add default block supports.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function add_font_family_data( array $config ): array {
	$selected_fonts = wp_get_global_settings()['typography']['fontFamilies']['theme'] ?? [];

	$config['fontFamilies']  = wp_list_pluck( get_all_fonts(), 'slug' );
	$config['selectedFonts'] = wp_list_pluck( $selected_fonts, 'slug' );

	return $config;
}

/**
 * Returns an array of system fonts for custom properties.
 *
 * @since 0.4.0
 *
 * @return array
 */
function get_system_fonts(): array {
	return [
		[
			'name'       => 'Sans Serif',
			'slug'       => 'sans-serif',
			'fontFamily' => '-apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif',
		],
		[
			'name'       => 'Serif',
			'slug'       => 'serif',
			'fontFamily' => 'Iowan Old Style, Apple Garamond, Baskerville, Times New Roman, Droid Serif, Times, Source Serif Pro, serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol',
		],
		[
			'name'       => 'Monospace',
			'slug'       => 'monospace',
			'fontFamily' => 'Menlo, Consolas, Monaco, Liberation Mono, Lucida Console, monospace',
		],
	];
}
