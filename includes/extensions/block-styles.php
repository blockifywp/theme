<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_map;
use function implode;
use function preg_split;
use function str_contains;
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
		'core/buttons'    => [ 'surface' ],
		'core/button'     => [ 'ghost' ],
		'core/code'       => [ 'surface' ],
		'core/columns'    => [ 'surface' ],
		'core/column'     => [ 'surface' ],
		'core/group'      => [ 'surface' ],
		'core/list'       => [ 'checklist', 'checklist-circle', 'square', 'accordion', 'none' ],
		'core/list-item'  => [ 'surface' ],
		'core/page-list'  => [ 'none' ],
		'core/paragraph'  => [ 'sub-heading', 'notice', 'heading' ],
		'core/post-terms' => [ 'badges' ],
		'core/read-more'  => [ 'button' ],
		'core/site-title' => [ 'heading' ],
		'core/spacer'     => [ 'angle', 'curve', 'round', 'wave', 'fade' ],
		'core/quote'      => [ 'surface' ],
	];

	$button_secondary = wp_get_global_settings()['custom']['buttonSecondary'] ?? null;

	if ( $button_secondary ) {
		$register['core/button'][] = 'secondary';
	}

	$dark_mode = wp_get_global_settings()['custom']['darkMode'] ?? null;

	if ( $dark_mode ) {
		$register['core/code'][]    = 'light';
		$register['core/code'][]    = 'dark';
		$register['core/column'][]  = 'light';
		$register['core/column'][]  = 'dark';
		$register['core/columns'][] = 'light';
		$register['core/columns'][] = 'dark';
		$register['core/group'][]   = 'light';
		$register['core/group'][]   = 'dark';
	}

	$config['blockStyles'] = [
		'register'   => $register,
		'unregister' => [
			'core/image'     => [ 'rounded', 'default' ],
			'core/site-logo' => [ 'default', 'rounded' ],
			'core/separator' => [ 'wide', 'dots' ],
		],
	];

	return $config;
}

add_filter( 'blockify_inline_css', NS . 'get_block_style_heading_styles', 10, 3 );
/**
 * Get block style heading styles.
 *
 * @since 1.1.2
 *
 * @param string $css       Inline CSS.
 * @param string $content   Page Content.
 * @param bool   $is_editor Is editor page.
 *
 * @return string
 */
function get_block_style_heading_styles( string $css, string $content, bool $is_editor ): string {
	$global_styles = wp_get_global_styles();

	if ( ! str_contains( $content, 'is-style-heading' ) && ! $is_editor ) {
		return $css;
	}

	$typography = $global_styles['elements']['heading']['typography'] ?? [];
	$color      = $global_styles['elements']['heading']['color'] ?? [];

	if ( ! $typography && ! $color ) {
		return $css;
	}

	$styles = [];

	foreach ( $typography as $key => $value ) {
		$pieces   = preg_split( '/(?=[A-Z])/', $key );
		$property = implode( '-', array_map( 'strtolower', $pieces ) );

		$styles[ $property ] = $value;
	}

	if ( $color['text'] ?? null ) {
		$styles['color'] = $color['text'];
	}

	return $css . '.is-style-heading{' . css_array_to_string( $styles ) . '}';
}
