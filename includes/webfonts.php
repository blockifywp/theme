<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const WP_CONTENT_DIR;
use function add_action;
use function add_editor_style;
use function array_map;
use function basename;
use function end;
use function explode;
use function filemtime;
use function function_exists;
use function in_array;
use function is_admin;
use function sprintf;
use function str_contains;
use function str_replace;
use function ucwords;
use function wp_enqueue_style;
use function wp_get_global_styles;
use function wp_get_global_settings;

add_action( 'admin_init', NS . 'enqueue_google_fonts' );
add_action( 'wp_enqueue_scripts', NS . 'enqueue_google_fonts' );
/**
 * Enqueues google fonts.
 *
 * @since 0.0.2
 *
 * @return void
 */
function enqueue_google_fonts(): void {
	if ( ! function_exists( 'wptt_get_webfont_url' ) ) {
		return;
	}

	$global_styles     = wp_get_global_styles();
	$global_settings   = wp_get_global_settings();
	$font_family_slugs = array_map(
		fn( $font_family ) => $font_family['slug'],
		$global_settings['typography']['fontFamilies']['theme'] ?? [ null ]
	);
	$default_weight    = 'var(--wp--custom--font-weight--regular)';
	$google_fonts      = [];
	$heading_family    = $global_styles['blocks']['core/heading']['typography']['fontFamily'] ?? null;

	if ( $heading_family ) {
		$google_fonts[ $heading_family ] = $global_styles['blocks']['core/heading']['typography']['fontWeight'] ?? $default_weight;
	}

	$body_family = $global_styles['typography']['fontFamily'] ?? null;

	if ( $body_family ) {
		$google_fonts[ $body_family ] = $global_styles['typography']['fontWeight'] ?? $default_weight;
	}

	foreach ( $google_fonts as $google_font => $font_weight ) {
		if ( str_contains( $google_font, 'var(--' ) ) {
			$explode_font = explode( '--', str_replace( ')', '', $google_font ) );
		} else {
			$explode_font = explode( '|', $google_font );
		}

		if ( str_contains( $font_weight, 'var(--' ) ) {
			$explode_weight = explode( '--', str_replace( ')', '', $font_weight ) );
		} else {
			$explode_weight = explode( '|', $font_weight );
		}

		$slug   = end( $explode_font );
		$weight = end( $explode_weight );

		if ( in_array( $slug, [ 'sans-serif', 'serif', 'monospace' ] ) ) {
			return;
		}

		if ( ! in_array( $slug, $font_family_slugs ) ) {
			return;
		}

		$font_weights = [
			'thin'        => 100,
			'extra-light' => 200,
			'light'       => 300,
			'regular'     => 400,
			'medium'      => 500,
			'semi-bold'   => 600,
			'bold'        => 700,
			'extra-bold'  => 800,
			'black'       => 900,
		];

		$name = str_replace( ' ', '+', ucwords( str_replace( [ '-', '' ], ' ', $slug ) ) );

		$url = wptt_get_webfont_url( sprintf(
			'https://fonts.googleapis.com/css2?family=%s:wght@%s&display=swap',
			$name,
			$font_weights[ $weight ] ?? 400
		) );

		if ( ! is_admin() ) {
			wp_enqueue_style(
				'blockify-font-' . $slug,
				$url,
				[ 'global-styles' ],
				filemtime( WP_CONTENT_DIR . '/fonts' )
			);
		} else {
			add_editor_style( '../../fonts/' . basename( $url ) );
		}
	}
}
