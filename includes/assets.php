<?php

declare( strict_types=1 );

namespace Blockify;

use WP_Screen;
use const WP_CONTENT_DIR;
use function add_action;
use function add_editor_style;
use function apply_filters;
use function array_flip;
use function array_map;
use function current_theme_supports;
use function end;
use function explode;
use function file_exists;
use function file_get_contents;
use function filemtime;
use function function_exists;
use function in_array;
use function is_a;
use function plugin_dir_url;
use function sprintf;
use function str_contains;
use function str_replace;
use function ucwords;
use function wp_add_inline_style;
use function wp_enqueue_style;
use function wp_get_global_styles;
use function wp_get_global_settings;

add_action( 'enqueue_block_editor_assets', NS . 'enqueue_editor_assets' );
/**
 * Enqueues editor assets.
 *
 * @since 0.0.14
 *
 * @return void
 */
function enqueue_editor_assets(): void {
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_add_inline_style( 'global-styles', get_inline_css() );

	enqueue_asset( 'editor.css' );
	enqueue_asset( 'index.js', [
		'deps' => [ 'wp-edit-site' ], // Needed for block styles.
	] );

	wp_localize_script( 'blockify-index', 'blockify', get_script_data() );
}

add_action( 'after_setup_theme', NS . 'add_editor_styles' );
/**
 * Adds editor styles.
 *
 * @since 0.0.2
 *
 * @return void
 */
function add_editor_styles(): void {
	add_editor_style( 'build/style.css' );
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_scripts_styles' );
/**
 * Enqueues front end scripts.
 *
 * @since 0.0.2
 *
 * @return void
 */
function enqueue_scripts_styles(): void {
	global $wp_styles;

	$inline = apply_filters( 'blockify_load_inline_css', true );

	wp_dequeue_style( 'wp-block-library-theme' );
	wp_add_inline_style( 'global-styles', get_inline_css() );

	if ( $inline ) {
		wp_add_inline_style(
			'global-styles',
			file_get_contents( DIR . 'build/style.css' )
		);
	} else {
		enqueue_asset( 'style.css' );
	}

	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		return;
	}

	foreach ( $wp_styles->registered as $handle => $style ) {
		if ( ! isset( array_flip( $wp_styles->queue )[ $handle ] ) ) {
			continue;
		}

		$slug = str_replace( 'wp-block-', '', $handle );
		$file = DIR . 'build/blocks/' . $slug . '/style-style.css';

		if ( file_exists( $file ) ) {
			if ( $inline ) {
				wp_add_inline_style(
					$handle,
					file_get_contents( $file )
				);
			} else {
				wp_enqueue_style(
					'blockify-core-' . $slug,
					plugin_dir_url( FILE ) . 'build/blocks/' . $slug . '/style-style.css',
					[
						$handle,
						...get_asset_deps( "core/$slug/style" ),
					],
					get_asset_version( "core/$slug/style" ),
				);
				enqueue_asset( "core/$slug/script.js" );
			}
		}

		if ( file_exists( DIR . 'build/blocks/' . $slug . '/script.js' ) ) {
			enqueue_asset( "core/$slug/script.js", [
				'deps' => [],
			] );
		}
	}
}

add_action( 'enqueue_block_editor_assets', NS . 'enqueue_google_fonts' );
add_action( 'wp_enqueue_scripts', NS . 'enqueue_google_fonts' );
/**
 * Enqueues google fonts.
 *
 * @since 0.0.2
 *
 * @return void
 * @todo  Switch to wp_enqueue_webfont function.
 *
 */
function enqueue_google_fonts(): void {
	if ( ! function_exists( 'wptt_get_webfont_url' ) ) {
		return;
	}

	if ( ! current_theme_supports( SLUG ) ) {
		return;
	}

	$global_styles     = wp_get_global_styles();
	$global_settings   = wp_get_global_settings();
	$font_family_slugs = array_map( fn( $font_family ) => $font_family['slug'], $global_settings['typography']['fontFamilies']['theme'] ?? [ null ] );
	$default_weight    = 'var(--wp--custom--font-weight--regular)';
	$google_fonts      = [];

	if ( isset( $global_styles['blocks']['core/heading']['typography']['fontFamily'] ) ) {
		$google_fonts[ $global_styles['blocks']['core/heading']['typography']['fontFamily'] ] = $global_styles['blocks']['core/heading']['typography']['fontWeight'] ?? $default_weight;
	}

	if ( isset( $global_styles['typography']['fontFamily'] ) ) {
		$google_fonts[ $global_styles['typography']['fontFamily'] ] = $global_styles['typography']['fontWeight'] ?? $default_weight;
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

		wp_enqueue_style(
			'blockify-font-' . $slug,
			wptt_get_webfont_url( sprintf(
				'https://fonts.googleapis.com/css2?family=%s:wght@%s&display=swap',
				$name,
				$font_weights[ $weight ] ?? 400
			) ),
			[ 'global-styles' ],
			filemtime( WP_CONTENT_DIR . '/fonts' )
		);
	}
}

/**
 * Returns default plugin script data.
 *
 * @since 0.0.1
 *
 * @return array
 */
function get_script_data(): array {
	return array_merge_recursive( [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'blockify' ),
	], get_config() );
}

/**
 * Adds custom properties.
 *
 * @since 0.0.2
 *
 * @param string $css
 *
 * @return string
 */
function get_inline_css( string $css = '' ): string {
	$settings        = wp_get_global_settings();
	$scrollbar_width = get_os() === 'windows' ? '12px' : '15px';
	$content_size    = $settings['layout']['contentSize'] ?? '768px';
	$wide_size       = $settings['layout']['wideSize'] ?? '1280px';
	$css             = $css . <<<CSS
	body {
		--scrollbar--width: $scrollbar_width;
		--wp--custom--layout--content-size: $content_size;
		--wp--custom--layout--wide-size: $wide_size;
	}
CSS;

	return minify_css( apply_filters( 'blockify_inline_css', $css ) );
}
