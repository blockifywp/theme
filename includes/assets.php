<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use const WP_CONTENT_DIR;
use function add_filter;
use function array_merge_recursive;
use function add_action;
use function add_editor_style;
use function apply_filters;
use function array_diff;
use function array_flip;
use function array_key_exists;
use function array_map;
use function basename;
use function dirname;
use function end;
use function explode;
use function file_exists;
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
use function remove_action;
use function remove_filter;
use function sprintf;
use function str_contains;
use function str_replace;
use function ucwords;
use function wp_add_inline_style;
use function wp_enqueue_script;
use function wp_register_script;
use function wp_enqueue_style;
use function wp_get_global_styles;
use function wp_get_global_settings;
use WP_Screen;

/**
 * Enqueues editor assets.
 *
 * @since 0.0.14
 *
 * @return void
 */
function enqueue_editor_assets(): void {
	wp_enqueue_style(
		'blockify-editor',
		get_url() . 'assets/css/editor.css',
		[],
		filemtime( DIR . 'assets/css/editor.css' )
	);

	$asset = require DIR . 'assets/js/editor.asset.php';
	$deps  = $asset['dependencies'];

	wp_register_script(
		'blockify-editor',
		get_url() . 'assets/js/editor.js',
		$deps,
		filemtime( DIR . 'assets/js/editor.js' ),
		true
	);

	wp_enqueue_script( 'blockify-editor' );

	wp_localize_script(
		'blockify-editor',
		'blockify',
		array_merge_recursive( [
			'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
			'nonce'              => wp_create_nonce( 'blockify' ),
			'icon'               => trim( file_get_contents( DIR . 'assets/svg/social/blockify.svg' ) ),
			'darkMode'           => get_option( 'blockify' )['darkMode'] ?? false,
			'darkModePreview'    => get_option( 'blockify' )['darkModePreview'] ?? false,
			'removeEmojiScripts' => get_option( 'blockify' )['removeEmojiScripts'] ?? false,
		], get_config() )
	);
}

add_action( 'current_screen', NS . 'maybe_load_editor_assets' );
/**
 * Conditionally changes which action hook editor assets are enqueued.
 *
 * @since 0.0.19
 *
 * @param WP_Screen $screen
 *
 * @return void
 */
function maybe_load_editor_assets( WP_Screen $screen ): void {
	$site_editor = $screen->base === 'appearance_page_gutenberg-edit-site' || $screen->base === 'site-editor';
	$hook_name   = $site_editor ? 'admin_enqueue_scripts' : 'enqueue_block_editor_assets';

	add_action( $hook_name, NS . 'enqueue_editor_assets' );
	add_action( $hook_name, NS . 'add_root_level_custom_properties' );
	add_action( $hook_name, NS . 'add_dynamic_custom_properties' );
	add_action( $hook_name, NS . 'add_dark_mode_custom_properties' );
	add_action( $hook_name, NS . 'add_conditional_styles', 11 );
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

	foreach ( $wp_styles->registered as $handle => $style ) {
		if ( ! isset( array_flip( $wp_styles->queue )[ $handle ] ) ) {
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

add_action( 'wp_enqueue_scripts', NS . 'add_dynamic_custom_properties' );
/**
 * Adds custom properties.
 *
 * @since 0.0.19
 *
 * @return void
 */
function add_dynamic_custom_properties(): void {
	$settings          = wp_get_global_settings();
	$global_styles     = wp_get_global_styles();
	$element           = is_admin() ? '.editor-styles-wrapper' : 'body';
	$content_size      = $settings['layout']['contentSize'] ?? '800px';
	$wide_size         = $settings['layout']['wideSize'] ?? '1200px';
	$border_width      = $settings['custom']['border']['width'] ?? '1px';
	$border_style      = $settings['custom']['border']['style'] ?? 'solid';
	$border_color      = $settings['custom']['border']['color'] ?? '#ddd';
	$body_background   = $global_styles['color']['background'] ?? null;
	$body_color        = $global_styles['color']['text'] ?? null;
	$button_background = $global_styles['blocks']['core/button']['color']['background'] ?? null;
	$button_text       = $global_styles['blocks']['core/button']['color']['text'] ?? null;

	$all = [
		'--wp--custom--layout--content-size' => $content_size,
		'--wp--custom--layout--wide-size'    => $wide_size,
		'--wp--custom--border'               => "$border_width $border_style $border_color",
		'--wp--custom--body--background'     => $body_background,
		'--wp--custom--body--color'          => $body_color,
		'--wp--custom--button--background'   => $button_background,
		'--wp--custom--button--color'        => $button_text,
	];

	$css = $element . '{' . css_array_to_string( $all ) . '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$css
	);
}

add_action( 'wp_enqueue_scripts', NS . 'add_dark_mode_custom_properties' );
/**
 * Adds dark mode custom properties.
 *
 * @since 0.0.24
 *
 * @return void
 */
function add_dark_mode_custom_properties(): void {

	// Check if dark mode setting is deactivated.
	$dark_mode = get_option( 'blockify', [] )['darkMode'] ?? true;

	if ( ! $dark_mode ) {
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
	$config = get_sub_config( 'darkModeColorPalette', null );

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

	$body_element = is_admin() ? '' : 'body';
	$html_element = is_admin() ? 'html ' : '';
	$new_css      = '@media(prefers-color-scheme:dark){' . $body_element . '{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}}' . $html_element . '.dark-mode{';

	foreach ( $properties as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}' . $html_element . '.light-mode{';

	foreach ( $original as $property => $value ) {
		$new_css .= "$property:$value;";
	}

	$new_css .= '}';

	wp_add_inline_style(
		is_admin() ? 'blockify-editor' : 'global-styles',
		$new_css
	);
}

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
		...glob( DIR . 'assets/css/blocks/*.css' ),
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
	];

	foreach ( $files as $file ) {
		add_editor_style( 'assets/css/' . basename( dirname( $file ) ) . DS . basename( $file ) );
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
		'admin-bar'    => is_admin_bar_showing(),
		'align-center' => true,
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

add_action( 'after_setup_theme', NS . 'remove_emoji_scripts' );
/**
 * Removes unused emoji scripts.
 *
 * @since 0.0.21
 *
 * @return void
 */
function remove_emoji_scripts(): void {

	// Defaults to true for theme previews.
	if ( ! ( get_option( 'blockify', [] )['removeEmojiScripts'] ?? true ) ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'tiny_mce_plugins', fn( array $plugins = [] ) => array_diff(
		$plugins,
		[ 'wpemoji' ]
	) );
	add_filter( 'wp_resource_hints', function ( array $urls, string $relation_type ): array {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls = array_diff( $urls, [ apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' ) ] );
		}

		return $urls;
	}, 10, 2 );
}
