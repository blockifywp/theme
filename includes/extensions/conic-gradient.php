<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function str_replace;
use function wp_add_inline_style;
use function wp_get_global_settings;

add_action( 'wp_enqueue_scripts', NS . 'add_conic_gradient' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_conic_gradient(): void {
	$settings  = wp_get_global_settings();
	$gradients = $settings['color']['gradients']['theme'] ?? [];
	$conic     = null;

	foreach ( $gradients as $gradient ) {
		if ( ( $gradient['slug'] ?? '' ) === 'conic' ) {
			$conic = str_replace(
				'linear-gradient(',
				'conic-gradient(from ',
				$gradient['gradient']
			);
		}
	}

	wp_add_inline_style(
		'global-styles',
		'body{' . css_array_to_string(
			[
				'--wp--preset--gradient--conic' => $conic . ' !important',
			]
		) . '}'
	);
}

