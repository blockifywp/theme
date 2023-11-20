<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function add_filter;
use function str_contains;
use function str_replace;
use function wp_get_global_settings;
use function wp_get_global_styles;

add_filter( 'blockify_editor_data', NS . 'register_block_styles' );
/**
 * Adds default blocks styles.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_block_styles( array $config ): array {
	$register = [
		'core/archive-title'       => [ 'sub-heading' ],
		'core/buttons'             => [ 'surface' ],
		'core/button'              => [ 'ghost' ],
		'core/code'                => [ 'surface' ],
		'core/columns'             => [ 'surface' ],
		'core/column'              => [ 'surface' ],
		'core/comment-author-name' => [ 'heading' ],
		'core/details'             => [ 'plus' ],
		'core/group'               => [ 'surface' ],
		'core/list'                => [ 'checklist', 'check-outline', 'check-circle', 'square', 'list-heading', 'dash', 'none' ],
		'core/list-item'           => [ 'surface' ],
		'core/navigation'          => [ 'heading' ],
		'core/page-list'           => [ 'none' ],
		'core/paragraph'           => [ 'sub-heading', 'notice', 'heading' ],
		'core/post-author-name'    => [ 'heading' ],
		'core/post-terms'          => [ 'list', 'sub-heading', 'badges' ],
		'core/post-title'          => [ 'sub-heading' ],
		'core/query-pagination'    => [ 'badges' ],
		'core/read-more'           => [ 'button' ],
		'core/site-title'          => [ 'heading' ],
		'core/spacer'              => [ 'angle', 'curve', 'round', 'wave', 'fade' ],
		'core/tag-cloud'           => [ 'badges' ],
		'core/quote'               => [ 'surface' ],
	];

	$global_settings  = wp_get_global_settings();
	$button_secondary = $global_settings['custom']['buttonSecondary'] ?? null;

	if ( $button_secondary ) {
		$register['core/button'][] = 'secondary';
	}

	$dark_mode  = $global_settings['custom']['darkMode'] ?? null;
	$light_mode = $global_settings['custom']['lightMode'] ?? null;

	if ( $dark_mode || $light_mode ) {
		$register['core/code'][]    = 'light';
		$register['core/code'][]    = 'dark';
		$register['core/column'][]  = 'light';
		$register['core/column'][]  = 'dark';
		$register['core/columns'][] = 'light';
		$register['core/columns'][] = 'dark';
		$register['core/group'][]   = 'light';
		$register['core/group'][]   = 'dark';
	}

	// Values must be arrays.
	$unregister = [
		'core/image'     => [ 'rounded', 'default' ],
		'core/site-logo' => [ 'default', 'rounded' ],
		'core/separator' => [ 'wide', 'dots' ],
	];

	$config['blockStyles'] = [
		'register'   => $register,
		'unregister' => $unregister,
	];

	return $config;
}

add_filter( 'blockify_inline_css', NS . 'get_block_style_heading_styles', 10, 3 );
/**
 * Get block style heading styles.
 *
 * @since 1.1.2
 *
 * @param string $css     Inline CSS.
 * @param string $content Page Content.
 * @param bool   $all     Load all styles.
 *
 * @return string
 */
function get_block_style_heading_styles( string $css, string $content, bool $all ): string {
	$global_styles = wp_get_global_styles();

	if ( ! str_contains( $content, 'is-style-heading' ) && ! $all ) {
		return $css;
	}

	$typography = $global_styles['elements']['heading']['typography'] ?? [];
	$color      = $global_styles['elements']['heading']['color'] ?? [];

	if ( ! $typography && ! $color ) {
		return $css;
	}

	$styles = [];

	foreach ( $typography as $key => $value ) {
		$styles[ _wp_to_kebab_case( $key ) ] = format_custom_property( $value );
	}

	if ( $color['text'] ?? null ) {
		$styles['color'] = $color['text'];
	}

	return $css . '.is-style-heading{' . css_array_to_string( $styles ) . '}';
}

add_filter( 'render_block', NS . 'add_sub_heading_clip_text', 10, 2 );
/**
 * Add sub heading clip text.
 *
 * @since 1.3.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function add_sub_heading_clip_text( string $html, array $block ): string {
	$class_names = $block['attrs']['className'] ?? '';

	if ( ! str_contains( $class_names, 'is-style-sub-heading' ) ) {
		return $html;
	}

	$global_settings = wp_get_global_settings();
	$background      = $global_settings['custom']['subHeading']['background'] ?? '';

	if ( ! str_contains( $background, 'gradient' ) ) {
		return $html;
	}

	return str_replace( 'is-style-sub-heading', 'is-style-sub-heading has-text-gradient-background', $html );
}
