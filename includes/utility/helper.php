<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function apply_filters;
use function file_exists;
use function file_get_contents;
use function get_template_directory_uri;
use function trailingslashit;

/**
 * Returns the URL for the theme or plugin.
 *
 * @since 0.0.13
 *
 * @return string
 */
function get_url(): string {
	return trailingslashit( get_template_directory_uri() );
}

/**
 * Returns array of default style variation slugs.
 *
 * @since 1.0.0
 *
 * @return array
 */
function get_default_style_variations(): array {
	return [
		'default',
		...array_map(
			static fn( string $file ): string => basename( $file, '.json' ),
			glob( DIR . 'styles/*.json' )
		),
	];
}

/**
 * Gets style variation data from json file.
 *
 * @since 1.0.0
 *
 * @param string $style Selected style variation.
 *
 * @return array
 */
function get_style_variation_json( string $style ): array {
	$dir  = apply_filters( 'blockify_styles_dir', DIR . 'styles' );
	$json = [];

	if ( file_exists( $dir . "/$style.json" ) ) {
		$json = json_decode(
			file_get_contents( $dir . "/$style.json" ),
			true
		);
	}

	return $json ?? [];
}
