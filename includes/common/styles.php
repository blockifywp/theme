<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_editor_style;
use function add_filter;
use function apply_filters;
use function array_flip;
use function array_merge;
use function file_exists;
use function file_get_contents;
use function filemtime;
use function get_stylesheet_directory;
use function get_template;
use function get_template_directory;
use function glob;
use function is_a;
use function is_admin;
use function is_admin_bar_showing;
use function is_archive;
use function is_user_logged_in;
use function str_contains;
use function str_replace;
use function trim;
use function wp_add_inline_style;
use function wp_dequeue_style;
use function wp_enqueue_style;
use function wp_get_global_settings;
use function wp_get_global_styles;
use function wp_get_theme;
use function wp_json_file_decode;
use function wp_register_style;

// Remove admin bar inline CSS.
add_theme_support( 'admin-bar', [ 'callback' => '__return_false' ] );

/**
 * Returns filtered inline styles.
 *
 * @since 0.9.22
 *
 * @param string $content Page content.
 * @param bool   $all     Is Editor.
 *
 * @return string
 */
function get_inline_styles( string $content, bool $all ): string {

	/**
	 * Filters the inline CSS.
	 *
	 * @since 0.9.22
	 *
	 * @param string $css     Inline CSS.
	 * @param string $content Page content.
	 * @param bool   $all     Is Editor. (Load all CSS).
	 */
	$css = apply_filters(
		'blockify_inline_css',
		'',
		$content,
		$all
	);

	return remove_line_breaks( $css );
}

add_action( 'wp_enqueue_scripts', NS . 'enqueue_styles', 99 );
/**
 * Enqueues styles.
 *
 * @since 0.4.0
 *
 * @return void
 */
function enqueue_styles(): void {
	$handle = get_template();

	wp_dequeue_style( 'wp-block-library-theme' );

	wp_register_style(
		$handle,
		'',
		[],
		wp_get_theme()->get( 'Version' )
	);

	wp_add_inline_style(
		$handle,
		get_inline_styles(
			(string) ( $GLOBALS['template_html'] ?? '' ),
			false
		)
	);

	wp_enqueue_style( $handle );
}

/**
 * Gets dynamic custom properties.
 *
 * @since 0.9.19
 *
 * @return array
 */
function get_dynamic_custom_properties(): array {
	$global_settings     = wp_get_global_settings();
	$global_styles       = wp_get_global_styles();
	$custom              = $global_settings['custom'] ?? [];
	$transition_property = $custom['transition']['property'] ?? 'all';
	$transition_duration = $custom['transition']['duration'] ?? '0.3s';
	$transition_timing   = $custom['transition']['timingFunction'] ?? 'ease-in';
	$body_background     = $global_styles['color']['background'] ?? null;
	$body_color          = $global_styles['color']['text'] ?? null;
	$body_font_family    = $global_styles['typography']['fontFamily'] ?? null;
	$body_font_size      = $global_styles['typography']['fontSize'] ?? null;
	$body_font_weight    = $global_styles['typography']['fontWeight'] ?? null;
	$box_shadow          = $custom['boxShadow'] ?? [];
	$list_gap            = $global_styles['blocks']['core/list']['spacing']['blockGap'] ?? null;

	// Button.
	$button_block         = $global_styles['blocks']['core/button'] ?? [];
	$button_element       = $global_styles['elements']['button'] ?? [];
	$button_text          = $button_element['color']['text'] ?? $button_block['color']['text'] ?? null;
	$button_background    = $button_element['color']['background'] ?? $button_block['color']['background'] ?? null;
	$button_border_radius = $button_element['border']['radius'] ?? $button_block['border']['radius'] ?? null;
	$button_border_width  = $button_element['border']['width'] ?? $button_block['border']['width'] ?? null;
	$button_font_size     = $button_element['typography']['fontSize'] ?? $button_block['typography']['fontSize'] ?? null;
	$button_font_weight   = $button_element['typography']['fontWeight'] ?? $button_block['typography']['fontWeight'] ?? null;
	$button_line_height   = $button_element['typography']['lineHeight'] ?? $button_block['typography']['lineHeight'] ?? null;
	$button_padding       = $button_element['spacing']['padding'] ?? $button_block['spacing']['padding'] ?? null;

	// Also used by b, strong elements and legend element.
	$heading_font_family    = $global_styles['elements']['heading']['typography']['fontFamily'] ?? null;
	$heading_font_weight    = $global_styles['elements']['heading']['typography']['fontWeight'] ?? null;
	$heading_line_height    = $global_styles['elements']['heading']['typography']['lineHeight'] ?? null;
	$heading_letter_spacing = $global_styles['elements']['heading']['typography']['letterSpacing'] ?? null;
	$heading_color          = $global_styles['elements']['heading']['color']['text'] ?? null;

	// Also used by placeholder image.
	$image_border_radius = $global_styles['blocks']['core/image']['border']['radius'] ?? null;

	// Search gap.
	$search_gap = $global_styles['blocks']['core/search']['spacing']['blockGap'] ?? null;

	$link_hover_color = $global_styles['elements']['link'][':hover']['color']['text'] ?? null;

	$calendar_background = $global_styles['blocks']['core/calendar']['color']['background'] ?? null;

	$styles = [
		'--scroll'                              => '0',
		'--breakpoint'                          => '782px', // Only used by JS.
		'--wp--custom--border'                  => "var(--wp--custom--border--width,1px) var(--wp--custom--border--style,solid) var(--wp--custom--border--color,#ddd)",
		'--wp--custom--transition'              => "$transition_property $transition_duration $transition_timing",
		'--wp--custom--body--background'        => $body_background,
		'--wp--custom--body--color'             => $body_color,
		'--wp--custom--body--font-family'       => $body_font_family,
		'--wp--custom--body--font-size'         => $body_font_size,
		'--wp--custom--body--font-weight'       => $body_font_weight,
		'--wp--custom--heading--font-family'    => $heading_font_family,
		'--wp--custom--heading--font-weight'    => $heading_font_weight,
		'--wp--custom--heading--line-height'    => $heading_line_height,
		'--wp--custom--heading--letter-spacing' => $heading_letter_spacing,
		'--wp--custom--heading--color'          => $heading_color,

		// Used by .button.
		'--wp--custom--button--background'      => $button_background,
		'--wp--custom--button--color'           => $button_text,
		'--wp--custom--button--padding-top'     => $button_padding['top'] ?? null,
		'--wp--custom--button--padding-right'   => $button_padding['right'] ?? null,
		'--wp--custom--button--padding-bottom'  => $button_padding['bottom'] ?? null,
		'--wp--custom--button--padding-left'    => $button_padding['left'] ?? null,
		'--wp--custom--button--padding'         => 'var(--wp--custom--button--padding-top) var(--wp--custom--button--padding-right) var(--wp--custom--button--padding-bottom) var(--wp--custom--button--padding-left)',
		'--wp--custom--button--border-radius'   => $button_border_radius,
		'--wp--custom--button--border-width'    => $button_border_width,
		'--wp--custom--button--font-size'       => $button_font_size,
		'--wp--custom--button--font-weight'     => $button_font_weight,
		'--wp--custom--button--line-height'     => $button_line_height,

		// Image.
		'--wp--custom--image--border--radius'   => $image_border_radius,

		// Search.
		'--wp--custom--search--gap'             => $search_gap,

		// Link hover color used by navigation.
		'--wp--custom--link--hover--color'      => $link_hover_color,
	];

	if ( $list_gap ) {
		$styles['--wp--custom--list--gap'] = $list_gap;
	}

	if ( $calendar_background ) {
		$styles['--wp--custom--calendar--background'] = $calendar_background;
	}

	$inset      = $box_shadow['inset'] ?? ' ';
	$x          = $box_shadow['x'] ?? '0px';
	$y          = $box_shadow['y'] ?? '0px';
	$blur       = $box_shadow['blur'] ?? '0px';
	$spread     = $box_shadow['spread'] ?? '0px';
	$color      = $box_shadow['color'] ?? 'rgba(0,0,0,0)';
	$box_shadow = "$inset $x $y $blur $spread $color";

	$styles = array_merge(
		$styles,
		[
			'--wp--custom--box-shadow--inset'  => $inset,
			'--wp--custom--box-shadow--x'      => $x,
			'--wp--custom--box-shadow--y'      => $y,
			'--wp--custom--box-shadow--blur'   => $blur,
			'--wp--custom--box-shadow--spread' => $spread,
			'--wp--custom--box-shadow--color'  => $color,
			'--wp--custom--box-shadow'         => $box_shadow,
		]
	);

	/**
	 * Filters the dynamic custom properties.
	 *
	 * @since 1.3.0
	 *
	 * @param array $styles        Dynamic custom properties.
	 * @param array $global_styles Global styles.
	 */
	return apply_filters( 'blockify_dynamic_custom_properties', $styles, $global_styles );
}

add_filter( 'blockify_inline_css', NS . 'add_dynamic_custom_properties', 8 );
/**
 * Adds dynamic custom properties.
 *
 * @since 0.9.19
 *
 * @param string $css Inline CSS.
 *
 * @return string
 */
function add_dynamic_custom_properties( string $css = '' ): string {
	return $css . 'body{' . css_array_to_string( get_dynamic_custom_properties() ) . '}';
}

add_filter( 'blockify_inline_css', NS . 'get_conditional_stylesheets', 10, 3 );
/**
 * Adds conditional stylesheets inline.
 *
 * @since 0.0.27
 *
 * @param string $css     Inline CSS.
 * @param string $content Page content.
 * @param bool   $all     Is editor.
 *
 * @return string
 */
function get_conditional_stylesheets( string $css, string $content, bool $all ): string {
	$styles = [];

	$styles['elements'] = [
		'all'        => true,
		'anchor'     => str_contains( $content, '<a' ),
		'big'        => str_contains( $content, '<big' ),
		'blockquote' => str_contains( $content, '<blockquote' ),
		'body'       => true,
		'button'     => str_contains_any(
			$content,
			'<button',
			'type="button"',
			'type="submit"',
			'type="reset"',
			'nf-form',
			'wp-element-button'
		),
		'caption'    => str_contains( $content, 'wp-element-caption' ),
		'checkbox'   => str_contains( $content, 'type="checkbox"' ),
		'cite'       => str_contains( $content, '<cite' ),
		'code'       => str_contains( $content, '<code' ),
		'hr'         => str_contains( $content, '<hr' ),
		'form'       => str_contains_any(
			$content,
			'<fieldset',
			'<form',
			'<input',
			'nf-form'
		),
		'heading'    => true,
		'html'       => true,
		'list'       => str_contains_any( $content, '<ul', '<ol' ),
		'mark'       => str_contains( $content, '<mark' ),
		'pre'        => str_contains( $content, '<pre' ),
		'radio'      => str_contains( $content, 'type="radio"' ),
		'small'      => str_contains( $content, '<small' ),
		'strong'     => str_contains( $content, '<strong' ),
		'sub'        => str_contains( $content, '<sub' ),
		'sup'        => str_contains( $content, '<sup' ),
		'svg'        => str_contains( $content, '<svg' ),
		'table'      => str_contains( $content, '<table' ),
	];

	$styles['components'] = [
		'admin-bar'          => is_admin_bar_showing(),
		'border'             => str_contains( $content, 'border-width:' ),
		'drop-cap'           => str_contains( $content, 'has-drop-cap' ),
		'edit-link'          => str_contains( $content, 'edit-link' ),
		'inline-image'       => str_contains( $content, 'wp-image-' ),
		'placeholder-image'  => str_contains( $content, 'is-placeholder' ) || is_archive(),
		'screen-reader-text' => true,
		'site-blocks'        => true,
	];

	$styles['block-styles'] = [
		'badge'            => str_contains( $content, 'is-style-badge' ),
		'button-outline'   => str_contains( $content, 'is-style-outline' ),
		'button-secondary' => str_contains( $content, 'is-style-secondary' ),
		'button-ghost'     => str_contains( $content, 'is-style-ghost' ),
		'check-circle'     => str_contains( $content, 'is-style-check-circle' ),
		'check-outline'    => str_contains_any( $content, 'is-style-check-outline', 'is-style-checklist-circle' ),
		'checklist'        => str_contains( $content, 'is-style-checklist' ),
		'curved-text'      => str_contains( $content, 'is-style-curved-text' ),
		'divider-angle'    => str_contains( $content, 'is-style-angle' ),
		'divider-curve'    => str_contains( $content, 'is-style-curve' ),
		'divider-fade'     => str_contains( $content, 'is-style-fade' ),
		'divider-round'    => str_contains( $content, 'is-style-round' ),
		'divider-wave'     => str_contains( $content, 'is-style-wave' ),
		'heading'          => str_contains_any( $content, 'is-style-heading', 'is-style-summary-heading', 'is-style-list-heading' ),
		'list-dash'        => str_contains( $content, 'is-style-dash' ),
		'list-heading'     => str_contains( $content, 'is-style-heading' ),
		'list-none'        => str_contains( $content, 'is-style-none' ),
		'notice'           => str_contains( $content, 'is-style-notice' ),
		'numbered-list'    => str_contains( $content, 'is-style-numbered' ),
		'search-toggle'    => str_contains( $content, 'is-style-toggle' ),
		'square-list'      => str_contains( $content, 'is-style-square' ),
		'sub-heading'      => str_contains( $content, 'is-style-sub-heading' ),
		'surface'          => str_contains( $content, 'is-style-surface' ),
	];

	$styles['block-variations'] = [
		'accordion' => str_contains( $content, 'is-style-accordion' ),
		'counter'   => str_contains( $content, 'is-style-counter' ),
		'icon'      => str_contains( $content, 'is-style-icon' ),
		'marquee'   => str_contains( $content, 'is-marquee' ),
		'svg'       => str_contains( $content, 'is-style-svg' ),
	];

	$styles['formats'] = [
		'animation'  => str_contains_any( $content, 'has-text-animation', 'typewriter' ),
		'arrow'      => str_contains( $content, 'is-underline-arrow' ),
		'brush'      => str_contains( $content, 'is-underline-brush' ),
		'circle'     => str_contains( $content, 'is-underline-circle' ),
		'scribble'   => str_contains( $content, 'is-underline-scribble' ),
		'gradient'   => str_contains( $content, 'has-text-gradient' ),
		'highlight'  => str_contains( $content, 'has-inline-color' ),
		'underline'  => str_contains( $content, 'has-text-underline' ),
		'font-size'  => str_contains( $content, 'has-inline-font-size' ),
		'inline-svg' => str_contains( $content, 'inline-svg' ),
		'outline'    => str_contains( $content, 'has-text-outline' ),
	];

	$styles['extensions'] = [
		'animation'        => str_contains_any( $content, 'has-animation', 'will-animate' ),
		'aspect-ratio'     => str_contains( $content, 'has-aspect-ratio-' ),
		'box-shadow'       => str_contains( $content, 'has-box-shadow' ),
		'dark-mode'        => str_contains_any( $content, 'hide-dark-mode', 'hide-light-mode' ),
		'dark-mode-toggle' => str_contains( $content, 'toggle-switch' ),
		'filter'           => str_contains( $content, 'has-filter' ),
		'gradient-mask'    => str_contains( $content, '-gradient-background' ),
		'grid-pattern'     => str_contains( $content, 'has-grid-gradient-' ),
		'shadow'           => str_contains_any( $content, 'has-shadow', 'has-box-shadow', 'has-text-shadow' ),
		'transform'        => str_contains( $content, 'has-transform' ),
	];

	foreach ( $styles as $group => $stylesheets ) {
		foreach ( $stylesheets as $stylesheet => $condition ) {
			if ( $all || $condition || ! $content ) {
				$file = get_dir() . "assets/css/$group/$stylesheet.css";

				if ( file_exists( $file ) ) {
					$css .= trim( file_get_contents( $file ) );
				}
			}
		}
	}

	return $css;
}

add_action( 'wp_enqueue_scripts', NS . 'add_block_styles' );
/**
 * Adds conditional block styles.
 *
 * @since 0.9.19
 *
 * @return void
 */
function add_block_styles(): void {
	global $wp_styles;

	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		return;
	}

	$handles = array_flip( $wp_styles->queue );

	foreach ( $wp_styles->registered as $handle => $style ) {
		if ( ! isset( $handles[ $handle ] ) ) {
			continue;
		}

		$slug = str_replace( 'wp-block-', '', $handle );
		$file = get_dir() . 'assets/css/blocks/' . $slug . '.css';

		if ( ! file_exists( $file ) ) {
			continue;
		}

		if ( ! is_admin() ) {
			wp_add_inline_style(
				$handle,
				trim( file_get_contents( $file ) )
			);
		}
	}
}

add_filter( 'wp_theme_json_data_theme', NS . 'fix_editor_layout_sizes' );
/**
 * Changes layout size unit from vw to % in editor.
 *
 * @todo  Move layout settings to separate file.
 *
 * @since 0.4.2
 *
 * @param mixed $theme_json WP_Theme_JSON_Data | WP_Theme_JSON_Data_Gutenberg.
 *
 * @return mixed
 */
function fix_editor_layout_sizes( $theme_json ) {
	$default      = $theme_json->get_data();
	$new          = [];
	$content_size = $default['settings']['layout']['contentSize'] ?? 'min(calc(100dvw - var(--wp--preset--spacing--lg,2rem)), 720px)';
	$wide_size    = $default['settings']['layout']['wideSize'] ?? 'min(calc(100dvw - var(--wp--preset--spacing--lg,2rem)), 1200px)';

	if ( is_admin() ) {
		$content_size = str_replace( 'dvw', '%', $content_size );
		$wide_size    = str_replace( 'dvw', '%', $wide_size );
	}

	$new['settings']['layout']['contentSize'] = $content_size;
	$new['settings']['layout']['wideSize']    = $wide_size;

	$theme_json->update_with( array_merge( $default, $new ) );

	return $theme_json;
}

add_action( 'enqueue_block_assets', NS . 'enqueue_editor_only_styles' );
/**
 * Enqueues editor assets.
 *
 * @since 0.3.3
 *
 * @return void
 */
function enqueue_editor_only_styles(): void {
	if ( ! is_admin() || ! is_user_logged_in() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library-theme' );

	$dir     = get_dir();
	$file    = 'assets/css/editor.css';
	$handle  = 'blockify-editor';
	$version = file_exists( $dir . $file ) ? filemtime( $dir . $file ) : wp_get_theme()->get( 'Version' );

	wp_register_style(
		$handle,
		get_uri() . $file,
		[],
		$version
	);

	wp_enqueue_style( $handle );

	$dark_mode_css = get_dark_mode_styles( '' );

	wp_add_inline_style(
		$handle,
		$dark_mode_css
	);
}

add_action( 'admin_init', NS . 'add_editor_stylesheets' );
/**
 * Adds editor only styles.
 *
 * @since 0.9.10
 *
 * @return void
 */
function add_editor_stylesheets() {
	$blocks = glob( get_dir() . 'assets/css/blocks/*.css' );
	$vendor = '';

	if ( is_framework() ) {
		$vendor = 'vendor/blockify/theme/';
	}

	foreach ( $blocks as $block ) {
		add_editor_style( $vendor . 'assets/css/blocks/' . basename( $block ) );
	}

	add_editor_style( 'https://blockify-dynamic-styles' );
}

add_filter( 'pre_http_request', NS . 'generate_dynamic_styles', 10, 3 );
/**
 * Generates dynamic editor styles.
 *
 * @since 0.9.23
 *
 * @param array|bool $response    HTTP response.
 * @param array      $parsed_args Response args.
 * @param string     $url         Response URL.
 *
 * @return array|bool
 */
function generate_dynamic_styles( $response, array $parsed_args, string $url ) {
	if ( $url === 'https://blockify-dynamic-styles' ) {
		$response = [
			'body'     => get_inline_styles( '', true ),
			'headers'  => [],
			'response' => [
				'code'    => 200,
				'message' => 'OK',
			],
			'cookies'  => [],
			'filename' => null,
		];
	}

	return $response;
}

add_filter( 'blockify_inline_css', NS . 'add_child_theme_style_css' );
/**
 * Adds child theme style.css to inline styles.
 *
 * @since 0.9.23
 *
 * @param string $css CSS.
 *
 * @return string
 */
function add_child_theme_style_css( string $css ): string {
	$child = get_stylesheet_directory() . '/style.css';

	if ( file_exists( $child ) ) {
		$content = trim( file_get_contents( $child ) );
		$css     .= str_replace(
			str_between( '/**', '*/', $content ),
			'',
			$content
		);
	}

	return $css;
}

add_filter( 'blockify_inline_css', NS . 'add_deprecated_color_palette' );
/**
 * Adds deprecated color palette to inline styles.
 *
 * @since 1.3.0
 *
 * @param string $css CSS.
 *
 * @return string
 */
function add_deprecated_color_palette( string $css ): string {
	$colors = get_deprecated_colors();
	$styles = [];

	foreach ( $colors as $slug => $value ) {
		if ( $value ) {
			$styles["--wp--preset--color--{$slug}"] = $value;
		}
	}

	if ( ! empty( $styles ) ) {
		$css .= 'body{' . css_array_to_string( $styles ) . '}';
	}

	return $css;
}

add_filter( 'blockify_inline_css', NS . 'add_deprecated_typography' );
/**
 * Adds deprecated typography to inline styles.
 *
 * @since 1.3.0
 *
 * @param string $css CSS.
 *
 * @return string
 */
function add_deprecated_typography( string $css ): string {
	$global_settings = wp_get_global_settings();
	$font_sizes      = $global_settings['typography']['fontSizes']['theme'] ?? [];

	if ( ! $font_sizes ) {
		return $css;
	}

	$has_deprecated = false;
	$slugs          = [];

	foreach ( $font_sizes as $font_size ) {
		$slug = $font_size['slug'] ?? '';

		if ( $slug === '81' ) {
			$has_deprecated = true;
		}

		$slugs[ $slug ] = $font_size;
	}

	if ( ! $has_deprecated ) {
		return $css;
	}

	$theme_json_file = get_template_directory() . '/theme.json';

	if ( ! file_exists( $theme_json_file ) ) {
		return $css;
	}

	$theme_json            = wp_json_file_decode( $theme_json_file );
	$theme_json_font_sizes = (array) ( $theme_json->settings->typography->fontSizes ?? [] );

	if ( ! $theme_json_font_sizes ) {
		return $css;
	}

	$styles = [];

	foreach ( $theme_json_font_sizes as $font_size ) {
		$slug = $font_size->slug ?? '';

		if ( isset( $slugs[ $slug ] ) ) {
			continue;
		}

		$styles["--wp--preset--font-size--{$slug}"] = $font_size->size ?? '';
	}

	return $css . 'body{' . css_array_to_string( $styles ) . '}';
}
