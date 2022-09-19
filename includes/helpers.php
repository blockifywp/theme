<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function basename;
use function file_get_contents;
use function get_stylesheet_directory;
use function get_the_ID;
use function get_the_title;
use function glob;
use function get_template_directory_uri;
use function json_decode;
use function trailingslashit;
use function wp_get_global_settings;
use function wp_get_global_styles;

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
 * Checks if we're on a wp.org pattern preview.
 *
 * @since 0.2.0
 *
 * @param int $post_id Not always the current post.
 *
 * @return bool
 */
function is_pattern_preview( int $post_id = 0 ): bool {
	if ( $post_id === 0 ) {
		$post_id = get_the_ID();
	}

	return get_post_meta( $post_id, '_wp_page_template', true ) === 'page-full' && $post_id === 2 && get_the_title( $post_id ) !== 'About';
}

/**
 * Attempts to detect the active style variation.
 *
 * Requires a color palette and a background color set in theme.json.
 *
 * @since 0.2.0
 *
 * @return string
 */
function get_style_variation(): string {
	$style_variation  = 'default';
	$global_settings  = wp_get_global_settings();
	$global_styles    = wp_get_global_styles();
	$palette_settings = $global_settings['color']['palette']['theme'] ?? [];
	$style_json_files = glob( get_stylesheet_directory() . '/styles/*.json' );

	foreach ( $style_json_files as $style_json_file ) {
		$contents = file_get_contents( $style_json_file );
		$json     = json_decode( $contents, true );
		$matches  = true;

		if ( ( $json['settings']['color']['palette'] ?? [] ) !== $palette_settings ) {
			$matches = false;
		}

		$background = $json['styles']['color']['background'] ?? null;

		if ( $background && $background !== $global_styles['color']['background'] ) {
			$matches = false;
		}

		$style_variation = $matches ? basename( $style_json_file, '.json' ) : $style_variation;
	}

	return $style_variation;
}
