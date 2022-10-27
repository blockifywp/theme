<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_editor_style;
use function add_filter;
use function array_flip;
use function array_merge;
use function basename;
use function dirname;
use function file_exists;
use function file_get_contents;
use function filemtime;
use function get_option;
use function get_site_url;
use function get_stylesheet_directory_uri;
use function get_the_block_template_html;
use function get_the_content;
use function glob;
use function is_a;
use function is_admin;
use function is_admin_bar_showing;
use function is_array;
use function is_child_theme;
use function is_front_page;
use function str_contains;
use function str_replace;
use function trim;
use function wp_add_inline_style;
use function wp_dequeue_style;
use function wp_enqueue_style;
use function wp_get_global_settings;
use function wp_get_global_styles;

add_action( 'wp_enqueue_scripts', NS . 'add_dynamic_custom_properties' );
/**
 * Adds custom properties.
 *
 * @since 0.0.19
 *
 * @return void
 */
function add_dynamic_custom_properties(): void {
	$element        = is_admin() ? 'body,.editor-styles-wrapper' : 'body';
	$settings       = wp_get_global_settings();
	$global_styles  = wp_get_global_styles();
	$custom         = $settings['custom'] ?? [];
	$border_width   = $custom['border']['width'] ?? '1px';
	$border_style   = $custom['border']['style'] ?? 'solid';
	$border_color   = $custom['border']['color'] ?? '#ddd';
	$bodyBackground = $global_styles['color']['background'] ?? null;
	$body_color     = $global_styles['color']['text'] ?? null;
	$box_shadow     = $custom['boxShadow'] ?? [];

	// Also used by b, strong elements and legend element.
	$heading_font_weight = $global_styles['elements']['heading']['typography']['fontWeight'] ?? null;
	$heading_font_family = $global_styles['elements']['heading']['typography']['fontFamily'] ?? null;

	$all = [
		'--wp--custom--border'               => "$border_width $border_style $border_color",
		'--wp--custom--body--background'     => $bodyBackground,
		'--wp--custom--body--color'          => $body_color,
		'--wp--custom--heading--font-weight' => $heading_font_weight,
		'--wp--custom--heading--font-family' => $heading_font_family,
	];

	if ( $box_shadow ) {
		if ( is_array( $box_shadow ) ) {
			$inset      = $box_shadow['inset'] ?? ' ';
			$x          = $box_shadow['x'] ?? null;
			$y          = $box_shadow['y'] ?? null;
			$blur       = $box_shadow['blur'] ?? null;
			$spread     = $box_shadow['spread'] ?? null;
			$color      = $box_shadow['color'] ?? null;
			$box_shadow = "$inset $x $y $blur $spread $color";
		}

		$all = array_merge(
			$all,
			[
				'--wp--custom--box-shadow' => $box_shadow,
			]
		);
	}

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

	$handles = array_flip( $wp_styles->queue );

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

add_action( 'admin_init', NS . 'add_conditional_style_sheets' );
add_action( 'wp_enqueue_scripts', NS . 'add_conditional_style_sheets' );
/**
 * Adds split styles.
 *
 * @since 0.0.27
 *
 * @return void
 */
function add_conditional_style_sheets(): void {
	$content = get_the_content() . get_the_block_template_html();
	$options = get_option( 'blockify_settings' ) ?? [];

	$stylesheets = [
		...( is_admin() ? glob( DIR . 'assets/css/blocks/*.css' ) : [] ),
		...glob( DIR . 'assets/css/elements/*.css' ),
		...glob( DIR . 'assets/css/components/*.css' ),
		...glob( DIR . 'assets/css/formats/*.css' ),
		...glob( DIR . 'assets/css/extensions/*.css' ),
		...glob( DIR . 'assets/css/block-styles/*.css' ),
		...glob( DIR . 'assets/css/utility/*.css' ),
		...glob( DIR . 'assets/css/plugins/*.css' ),
	];

	if ( is_child_theme() && ( $options['load_child_theme_style_css'] ?? false ) ) {
		$stylesheets[] = get_stylesheet_directory_uri() . '/style.css';
	}

	$conditions = [];

	// Load all block CSS in admin.
	foreach ( $stylesheets as $stylesheet ) {
		$dir  = basename( dirname( $stylesheet ) );
		$file = basename( $stylesheet, '.css' );

		if ( $dir === 'blocks' ) {
			$conditions['blocks'][ $file ] = true;
		}
	}

	$conditions['block-styles'] = [
		'button-outline'   => str_contains( $content, ' is-style-outline' ),
		'button-secondary' => str_contains( $content, ' is-style-secondary' ),
		'checklist-circle' => str_contains( $content, ' is-style-checklist-circle' ),
		'checklist'        => str_contains( $content, ' is-style-checklist' ),
		'divider-angle'    => str_contains( $content, ' is-style-angle' ),
		'divider-curve'    => str_contains( $content, ' is-style-curve' ),
		'divider-fade'     => str_contains( $content, ' is-style-fade' ),
		'divider-round'    => str_contains( $content, ' is-style-round' ),
		'divider-wave'     => str_contains( $content, ' is-style-wave' ),
		'mega-menu'        => str_contains( $content, ' is-style-mega-menu' ),
		'notice'           => str_contains( $content, ' is-style-notice' ),
		'numbered-list'    => str_contains( $content, ' is-style-numbered' ),
		'search-toggle'    => str_contains( $content, ' is-style-toggle' ),
		'square-list'      => str_contains( $content, ' is-style-square' ),
		'sub-heading'      => str_contains( $content, ' is-style-sub-heading' ),
		'surface'          => str_contains( $content, ' is-style-surface' ),
	];

	$conditions['elements'] = [
		'all'        => true,
		'big'        => str_contains( $content, '<big' ),
		'blockquote' => str_contains( $content, '<blockquote' ),
		'body'       => true,
		'button'     => str_contains( $content, '<button' ),
		'cite'       => str_contains( $content, '<cite' ),
		'code'       => str_contains( $content, '<code' ),
		'hr'         => str_contains( $content, '<hr' ),
		'form'       => str_contains( $content, '<fieldset' ) || str_contains( $content, '<form' ),
		'html'       => true,
		'link'       => str_contains( $content, '<link' ),
		'list'       => str_contains( $content, '<list' ),
		'mark'       => str_contains( $content, '<mark' ),
		'pre'        => str_contains( $content, '<pre' ),
		'small'      => str_contains( $content, '<small' ),
		'strong'     => str_contains( $content, '<strong' ),
		'sub'        => str_contains( $content, '<sub' ),
		'sup'        => str_contains( $content, '<sup' ),
		'table'      => str_contains( $content, '<table' ),
	];

	$conditions['components'] = [
		'admin-bar'          => is_admin_bar_showing(),
		'border'             => str_contains( $content, 'border-width:' ),
		'drop-cap'           => str_contains( $content, 'has-drop-cap' ),
		'layout'             => true,
		'placeholder-image'  => str_contains( $content, 'is-placeholder' ),
		'screen-reader-text' => true,
		'site-blocks'        => true,
	];

	$conditions['extensions'] = [
		'box-shadow' => str_contains( $content, ' has-box-shadow' ),
		'icon'       => str_contains( $content, ' is-style-icon' ),
		'mobile'     => str_contains( $content, '-mobile' ),
	];

	$conditions['formats'] = [
		'arrow'     => str_contains( $content, 'is-underline-arrow' ),
		'brush'     => str_contains( $content, 'is-underline-brush' ),
		'circle'    => str_contains( $content, 'is-underline-circle' ),
		'gradient'  => str_contains( $content, 'has-text-gradient' ),
		'highlight' => str_contains( $content, 'has-inline-color' ),
		'underline' => str_contains( $content, 'has-text-underline' ),
	];

	$conditions['utility'] = [
		'button-width' => str_contains( $content, 'wp-block-button__width-' ),
		'margin'       => str_contains( $content, ' margin-auto' ),
		'position'     => str_contains( $content, ' position-' ),
	];

	add_conditional_style_sheets_inline( $stylesheets, $conditions );
}

/**
 * Adds conditional stylesheets inline.
 *
 * @since 0.0.27
 *
 * @param array $stylesheets Stylesheets.
 * @param array $conditions  Conditions.
 *
 * @return void
 */
function add_conditional_style_sheets_inline( array $stylesheets, array $conditions ): void {
	$styles = '';
	$url    = get_site_url();

	foreach ( $stylesheets as $stylesheet ) {
		$dir       = basename( dirname( $stylesheet ) );
		$condition = $conditions[ $dir ][ basename( $stylesheet, '.css' ) ];

		if ( str_contains( $url, 'wp-themes.com' ) && ! is_front_page() ) {
			$condition = true;
		}

		if ( $condition ) {
			$styles .= trim( file_get_contents( $stylesheet ) );
		}

		if ( is_admin() ) {
			add_editor_style( 'assets/css/' . $dir . DS . basename( $stylesheet ) );
		}
	}

	wp_add_inline_style(
		'global-styles',
		$styles
	);
}

add_action( 'blockify_editor_scripts', NS . 'enqueue_editor_only_styles', 9 );
/**
 * Enqueues editor assets.
 *
 * @since 0.3.3
 *
 * @return void
 */
function enqueue_editor_only_styles(): void {
	wp_dequeue_style( 'wp-block-library-theme' );

	wp_register_style(
		'blockify-editor',
		get_url() . 'assets/css/editor.css',
		[],
		filemtime( DIR . 'assets/css/editor.css' )
	);

	wp_enqueue_style( 'blockify-editor' );

	add_dynamic_custom_properties();
}

add_filter( 'theme_json_theme', NS . 'fix_layout_sizes' );
add_filter( 'wp_theme_json_data_theme', NS . 'fix_layout_sizes' );
/**
 * Filters theme.json font families.
 *
 * @todo  Move layout settings to separate file.
 *
 * @since 0.4.2
 *
 * @param mixed $theme_json WP_Theme_JSON_Data | WP_Theme_JSON_Data_Gutenberg.
 *
 * @return mixed
 */
function fix_layout_sizes( $theme_json ) {
	$default      = $theme_json->get_data();
	$new          = [];
	$content_size = $default['settings']['layout']['contentSize'] ?? 'min(calc(100vw - 3rem), 800px)';
	$wide_size    = $default['settings']['layout']['wideSize'] ?? 'min(calc(100vw - 3rem), 1200px)';

	if ( is_admin() ) {
		$content_size = str_replace( 'vw', '%', $content_size );
		$wide_size    = str_replace( 'vw', '%', $wide_size );
	}

	$new['settings']['layout']['contentSize'] = $content_size;
	$new['settings']['layout']['wideSize']    = $wide_size;

	$theme_json->update_with( array_merge( $default, $new ) );

	return $theme_json;
}
