<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_diff;
use function in_array;
use function str_contains;
use function wp_get_global_settings;

add_filter( 'render_block', NS . 'render_box_shadow', 10, 2 );
/**
 * Adds box shadow to blocks.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_box_shadow( string $html, array $block ): string {
	$nested_element_blocks = [
		'core/button',
	];

	$shadow_preset = $block['attrs']['shadowPreset'] ?? null;

	if ( $shadow_preset ) {

		if ( in_array( $block['blockName'], $nested_element_blocks, true ) ) {
			$dom    = dom( $html );
			$first  = get_dom_element( '*', $dom );
			$second = get_dom_element( '*', $first );

			if ( ! $first || ! $second ) {
				return $html;
			}

			$first_classes  = explode( ' ', $first->getAttribute( 'class' ) );
			$second_classes = explode( ' ', $second->getAttribute( 'class' ) );

			foreach ( $first_classes as $index => $class ) {
				$exploded = explode( '-', $class );
				$has      = 'has' === ( $exploded[0] ?? null );
				$shadow   = in_array( 'shadow', [ $exploded[1] ?? '', $exploded[2] ?? '' ], true );

				if ( $has && $shadow ) {
					unset( $first_classes[ $index ] );
					$second_classes[] = $class;
				}
			}

			$first->setAttribute( 'class', implode( ' ', $first_classes ) );
			$second->setAttribute( 'class', implode( ' ', $second_classes ) );

			$html = $dom->saveHTML();
		}
	}

	$hover_preset = $block['attrs']['shadowPresetHover'] ?? null;

	if ( $hover_preset && ! $shadow_preset ) {
		$dom       = dom( $html );
		$first     = get_dom_element( '*', $dom );
		$classes   = explode( ' ', $first->getAttribute( 'class' ) );
		$classes   = array_diff( $classes, [ 'has-shadow' ] );
		$classes[] = 'has-shadow-hover';

		$first->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	$custom_shadow = $block['attrs']['style']['boxShadow'] ?? null;

	if ( ! $custom_shadow ) {
		return $html;
	}

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$first_classes   = explode( ' ', $first->getAttribute( 'class' ) );
	$first_classes[] = 'has-box-shadow';

	$first->setAttribute( 'class', implode( ' ', $first_classes ) );

	$styles = css_string_to_array( $first->getAttribute( 'style' ) );

	$inset       = $custom_shadow['inset'] ?? null;
	$inset_hover = $custom_shadow['hover']['inset'] ?? null;

	if ( $inset ) {
		$styles['--wp--custom--box-shadow--inset'] = 'inset';
	}

	if ( $inset_hover ) {
		$styles['--wp--custom--box-shadow--hover--inset'] = 'inset';
	}

	foreach ( [ 'x', 'y', 'blur', 'spread' ] as $property ) {

		if ( $custom_shadow[ $property ] ?? '' ) {
			$styles[ '--wp--custom--box-shadow--' . $property ] = $custom_shadow[ $property ] . 'px';
		}

		if ( $custom_shadow['hover'][ $property ] ?? '' ) {
			$styles[ '--wp--custom--box-shadow--hover--' . $property ] = $custom_shadow['hover'][ $property ] . 'px';
		}

	}

	$color       = $custom_shadow['color'] ?? null;
	$hover_color = $custom_shadow['hover']['color'] ?? null;
	$palette     = wp_get_global_settings()['color']['palette']['theme'] ?? [];

	foreach ( $palette as $theme_color ) {
		if ( $theme_color['color'] === $color ) {
			$styles['--wp--custom--box-shadow--color'] = "var(--wp--preset--color--{$theme_color['slug']})";
		}

		if ( $theme_color['color'] === $hover_color ) {
			$styles['--wp--custom--box-shadow--hover--color'] = "var(--wp--preset--color--{$theme_color['slug']})";
		}
	}

	$first->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}

add_filter( 'blockify_dynamic_custom_properties', NS . 'add_shadow_custom_properties', 10, 2 );
/**
 * Adds box shadow custom properties.
 *
 * @param array $styles        The custom properties.
 * @param array $global_styles The global styles.
 *
 * @return array
 */
function add_shadow_custom_properties( array $styles, array $global_styles ): array {
	global $template_html;

	$is_editor = is_admin() && ! wp_doing_ajax();
	$settings  = wp_get_global_settings();
	$presets   = $settings['shadow']['presets']['theme'] ?? [];

	foreach ( $presets as $preset ) {
		$slug   = $preset['slug'] ?? null;
		$shadow = $preset['shadow'] ?? null;

		if ( ! $slug || ! $shadow ) {
			continue;
		}

		if ( ! $is_editor && ! str_contains( $template_html ?? '', "has-{$slug}" ) ) {
			continue;
		}

		$styles[ '--wp--preset--shadow--' . $slug . '--hover' ] = $preset['shadow'];
	}

	return $styles;
}
