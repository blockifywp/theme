<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const WP_CONTENT_DIR;
use function add_action;
use function add_editor_style;
use function array_flip;
use function array_key_exists;
use function array_map;
use function basename;
use function dirname;
use function end;
use function explode;
use function file_get_contents;
use function filemtime;
use function function_exists;
use function get_option;
use function glob;
use function in_array;
use function is_a;
use function is_admin;
use function is_admin_bar_showing;
use function is_string;
use function sprintf;
use function str_contains;
use function str_replace;
use function ucwords;
use function wp_add_inline_style;
use function wp_dequeue_style;
use function wp_enqueue_style;
use function wp_get_global_styles;
use function wp_get_global_settings;

add_action( 'after_setup_theme', NS . 'add_editor_styles' );
/**
 * Always load all styles in editor.
 *
 * @since 0.0.2
 *
 * @return void
 */
function add_editor_styles(): void {
	$files = [
		// Load all block CSS when in editor.
		...glob( DIR . 'assets/css/blocks/*.css' ),
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
	];

	foreach ( $files as $file ) {
		add_editor_style( 'assets/css/' . basename( dirname( $file ) ) . DS . basename( $file ) );
	}
}

add_action( 'blockify_editor_scripts', NS . 'enqueue_editor_only_styles' );
/**
 * Enqueues editor assets.
 *
 * @since 0.3.3
 *
 * @return void
 */
function enqueue_editor_only_styles(): void {
	wp_enqueue_style(
		'blockify-editor',
		get_url() . 'assets/css/editor.css',
		[],
		filemtime( DIR . 'assets/css/editor.css' )
	);
}

add_action( 'blockify_editor_scripts', NS . 'add_root_level_custom_properties' );
add_action( 'wp_enqueue_scripts', NS . 'add_root_level_custom_properties' );
/**
 * Adds top level CSS custom properties to front and back end.
 *
 * @since 0.2.0
 *
 * @return void
 */
function add_root_level_custom_properties(): void {
	$css = <<<CSS
:root {
	--wp--custom--font-stack--sans-serif: -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;
	--wp--custom--font-stack--serif: Iowan Old Style, Apple Garamond, Baskerville, Times New Roman, Droid Serif, Times, Source Serif Pro, serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
	--wp--custom--font-stack--monospace: Menlo, Consolas, Monaco, Liberation Mono, Lucida Console, monospace;
}
CSS;

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$css
	);
}

add_action( 'blockify_editor_scripts', NS . 'add_dynamic_custom_properties' );
add_action( 'wp_enqueue_scripts', NS . 'add_dynamic_custom_properties' );
/**
 * Adds custom properties.
 *
 * @since 0.0.19
 *
 * @return void
 */
function add_dynamic_custom_properties(): void {
	$settings             = wp_get_global_settings();
	$global_styles        = wp_get_global_styles();
	$element              = is_admin() ? '.editor-styles-wrapper' : 'body';
	$content_size         = $settings['layout']['contentSize'] ?? '800px';
	$wide_size            = $settings['layout']['wideSize'] ?? '1200px';
	$layout_unit          = is_admin() ? '%' : 'vw';
	$border_width         = $settings['custom']['border']['width'] ?? '1px';
	$border_style         = $settings['custom']['border']['style'] ?? 'solid';
	$border_color         = $settings['custom']['border']['color'] ?? '#ddd';
	$body_background      = $global_styles['color']['background'] ?? null;
	$body_color           = $global_styles['color']['text'] ?? null;
	$button               = $global_styles['blocks']['core/button'] ?? [];
	$button_text          = $button['color']['text'] ?? null;
	$button_background    = $button['color']['background'] ?? null;
	$button_border_radius = $button['border']['radius'] ?? null;
	$button_border_width  = $button['border']['width'] ?? null;
	$button_font_size     = $button['typography']['fontSize'] ?? null;
	$button_font_weight   = $button['typography']['fontWeight'] ?? null;
	$button_line_height   = $button['typography']['lineHeight'] ?? null;
	$button_padding       = $button['spacing']['padding'] ?? null;
	$block_gap            = $global_styles['spacing']['blockGap'] ?? null;

	$all = [
		// var(--wp--style--block-gap) doesn't work here.
		'--wp--custom--layout--content-size'   => "min(calc(100{$layout_unit} - 40px),{$content_size})",
		'--wp--custom--layout--wide-size'      => "min(calc(100{$layout_unit} - 40px),{$wide_size})",
		'--wp--custom--border'                 => "$border_width $border_style $border_color",
		'--wp--custom--body--background'       => $body_background,
		'--wp--custom--body--color'            => $body_color,

		// Gutenberg .wp-element-button issue workaround. Also used by input.
		'--wp--custom--button--background'     => $button_background,
		'--wp--custom--button--color'          => $button_text,
		'--wp--custom--button--padding-top'    => $button_padding['top'] ?? null,
		'--wp--custom--button--padding-right'  => $button_padding['right'] ?? null,
		'--wp--custom--button--padding-bottom' => $button_padding['bottom'] ?? null,
		'--wp--custom--button--padding-left'   => $button_padding['left'] ?? null,
		'--wp--custom--button--border-radius'  => $button_border_radius,
		'--wp--custom--button--border-width'   => $button_border_width,
		'--wp--custom--button--font-size'      => $button_font_size,
		'--wp--custom--button--font-weight'    => $button_font_weight,
		'--wp--custom--button--line-height'    => $button_line_height,
	];

	$css = $element . '{' . css_array_to_string( $all ) . '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$css
	);
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_block_styles' );
/**
 * Enqueues front end scripts.
 *
 * @since 0.0.2
 *
 * @return void
 */
function enqueue_block_styles(): void {
	global $wp_styles;

	wp_dequeue_style( 'wp-block-library-theme' );

	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		return;
	}

	$handles = array_flip( $wp_styles->queue ) ?? [];

	foreach ( $wp_styles->registered as $handle => $style ) {
		if ( ! isset( $handles[ $handle ] ) ) {
			continue;
		}

		$slug = str_replace( 'wp-block-', '', $handle );
		$file = DIR . 'assets/css/blocks/' . $slug . '.css';

		if ( file_exists( $file ) ) {
			wp_add_inline_style(
				$handle,
				file_get_contents( $file )
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', NS . 'add_conditional_styles' );
/**
 * Adds split styles.
 *
 * @since 0.0.27
 *
 * @return void
 */
function add_conditional_styles(): void {
	$styles = '';

	$stylesheets = [
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
	];

	$conditions = [
		'admin-bar' => is_admin_bar_showing(),
		'wp-org'    => is_pattern_preview(),
	];

	foreach ( $stylesheets as $stylesheet ) {
		if ( $conditions[ basename( $stylesheet, '.css' ) ] ?? true ) {
			$styles .= trim( file_get_contents( $stylesheet ) );
		}
	}

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$styles
	);
}

add_action( 'blockify_editor_scripts', NS . 'add_dark_mode_custom_properties' );
add_action( 'wp_enqueue_scripts', NS . 'add_dark_mode_custom_properties' );
/**
 * Adds dark mode custom properties.
 *
 * @since 0.0.24
 *
 * @return void
 */
function add_dark_mode_custom_properties(): void {
	$options = get_option( SLUG );
	$enabled = true;

	if ( isset( $options['darkMode'] ) ) {
		$enabled = $options['darkMode'] === 'true';
	}

	if ( ! $enabled && ! is_admin() ) {
		return;
	}

	$global_settings = wp_get_global_settings();

	if ( ! isset( $global_settings['color']['palette']['theme'] ) ) {
		return;
	}

	$colors = [];

	foreach ( $global_settings['color']['palette']['theme'] as $color ) {
		$colors[ $color['slug'] ] = $color['color'];
	}

	$original   = [];
	$properties = [];

	// Allows colors to be filtered with PHP, or removed in child theme json.
	$config = get_config( 'darkModeColorPalette' ) ?? null;

	if ( ! $config ) {
		return;
	}

	foreach ( $colors as $slug => $color ) {
		if ( ! isset( $config[ $slug ] ) || ! is_string( $config[ $slug ] ) ) {
			continue;
		}

		if ( ! array_key_exists( $config[ $slug ], $colors ) ) {
			continue;
		}

		$original[ '--wp--preset--color--' . $slug ]   = $color;
		$properties[ '--wp--preset--color--' . $slug ] = $colors[ $config[ $slug ] ];
	}

	$body_element = is_admin() ? 'body .has-dark-mode' : 'body';
	$new_css      = '@media(prefers-color-scheme:dark){' . $body_element . '{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}}';

	$new_css = '.dark-mode{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}.light-mode{';

	foreach ( $original as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$new_css
	);
}

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
