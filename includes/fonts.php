<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function apply_filters;
use function array_merge_recursive;
use function basename;
use function get_stylesheet_directory;
use function get_template_directory;
use function glob;
use function in_array;
use function is_admin;
use function str_replace;
use WP_Theme_JSON_Data_Gutenberg;

add_filter( 'theme_json_theme', NS . 'filter_theme_json' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param WP_Theme_JSON_Data_Gutenberg $theme_json
 *
 * @return WP_Theme_JSON_Data_Gutenberg
 */
function filter_theme_json( WP_Theme_JSON_Data_Gutenberg $theme_json ): WP_Theme_JSON_Data_Gutenberg {

	$default = $theme_json->get_data();

	$layout_unit = is_admin() ? '%' : 'vw';

	$custom = [
		'settings' => [
			'layout'     => [
				// TODO: String replace actual value.
				'contentSize' => "min(calc(100{$layout_unit} - 40px), 800px)",
				'wideSize'    => "min(calc(100{$layout_unit} - 40px), 1200px)",
			],
			'typography' => [
				'fontFamilies' => get_font_families(),
			],
		],
	];

	$theme_json->update_with(
		array_merge_recursive(
			$default,
			$custom
		)
	);

	return $theme_json;
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function get_font_families(): array {
	$font_families = get_system_fonts();
	$font_slugs    = [];
	$font_files    = [
		...glob( get_stylesheet_directory() . '/assets/fonts/*.woff2' ),
		...glob( get_template_directory() . '/assets/fonts/*.woff2' ),
	];

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
						"file:./assets/fonts/$slug.woff2",
					],
				],
			],
		];
	}

	return apply_filters( 'blockify_font_families', $font_families );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_system_fonts(): array {
	return [
		[
			'fontFamily' => 'var(--wp--custom--font-stack--sans-serif, -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif)',
			'name'       => 'Sans Serif',
			'slug'       => 'sans-serif',
		],
		[
			'fontFamily' => 'var(--wp--custom--font-stack--serif, serif)',
			'name'       => 'Serif',
			'slug'       => 'serif',
		],
		[
			'fontFamily' => 'var(--wp--custom--font-stack--monospace, monospace)',
			'name'       => 'Monospace',
			'slug'       => 'monospace',
		],
	];
}
