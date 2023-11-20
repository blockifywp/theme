<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function apply_filters;
use function array_merge;
use function is_readable;

add_filter( 'wp_theme_json_data_theme', NS . 'add_system_fonts' );
/**
 * Add system fonts.
 *
 * @param mixed $theme_json Theme JSON.
 *
 * @return mixed
 */
function add_system_fonts( $theme_json ) {
	$data        = $theme_json->get_data();
	$theme_fonts = $data['settings']['typography']['fontFamilies']['theme'] ?? [];

	static $added = false;

	if ( ! $theme_fonts && ! $added ) {
		$added = true;

		$framework_theme_json_file = get_dir() . 'theme.json';

		if ( ! is_readable( $framework_theme_json_file ) ) {
			return $theme_json;
		}

		$file_contents             = file_get_contents( $framework_theme_json_file );
		$framework_theme_json_file = json_decode( $file_contents, true );
		$framework_fonts           = $framework_theme_json_file['settings']['typography']['fontFamilies'] ?? [];

		if ( ! $framework_fonts ) {
			return $theme_json;
		}

		$data['settings']['typography']['fontFamilies']['theme'] = array_merge(
			get_system_fonts(),
			$framework_fonts,
		);
	}

	$data['settings']['typography']['fontFamilies']['theme'] = array_merge(
		get_system_fonts(),
		$data['settings']['typography']['fontFamilies']['theme'] ?? [],
	);

	$theme_json->update_with( $data );

	return $theme_json;
}

/**
 * Return system font stacks.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_system_fonts(): array {
	$fonts = [
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

	return apply_filters( 'blockify_system_fonts', $fonts );
}
