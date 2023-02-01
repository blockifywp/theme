<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function str_contains;
use function str_replace;
use function wp_add_inline_style;
use function wp_get_global_settings;
use function wp_strip_all_tags;

add_action( 'wp_enqueue_scripts', NS . 'add_conic_gradient' );
/**
 * Converts custom linear or radial gradient into conic gradient.
 *
 * @since 0.9.10
 *
 * @return void
 */
function add_conic_gradient(): void {
	$settings  = wp_get_global_settings();
	$gradients = $settings['color']['gradients']['custom'] ?? [];
	$css       = [];

	foreach ( $gradients as $gradient ) {
		$slug = $gradient['slug'] ?? '';

		if ( ! str_contains( $slug, 'custom-conic-' ) ) {
			continue;
		}

		$value = str_replace(
			'linear-gradient(',
			'conic-gradient(from ',
			$gradient['gradient']
		);

		$css[ '--wp--preset--gradient--' . $slug ] = $value;
	}

	$css = 'body{' . css_array_to_string( $css ) . '}';

	wp_add_inline_style( 'global-styles', wp_strip_all_tags( $css ) );
}
