<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function add_filter;
use function explode;
use function implode;

add_filter( 'blockify_editor_data', NS . 'register_responsive_settings', 11 );
/**
 * Add default block supports.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_responsive_settings( array $config = [] ): array {
	$config['positionOptions'] = get_block_extra_options();

	return $config;
}

add_filter( 'render_block', NS . 'add_position_classes', 10, 2 );
/**
 * Adds inline block positioning classes.
 *
 * @since 1.0.0
 *
 * @param string $html  Block content.
 * @param array  $block Block data.
 *
 * @return string
 */
function add_position_classes( string $html, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $html;
	}

	$options = get_block_extra_options();

	foreach ( $options as $key => $args ) {
		if ( ! isset( $style[ $key ] ) ) {
			continue;
		}

		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			continue;
		}

		$classes  = explode( ' ', $first->getAttribute( 'class' ) );
		$property = _wp_to_kebab_case( $key );

		if ( isset( $args['options'] ) ) {
			$all     = $style[ $key ]['all'] ?? '';
			$mobile  = $style[ $key ]['mobile'] ?? '';
			$desktop = $style[ $key ]['desktop'] ?? '';

			if ( $all ) {
				$classes[] = "has-{$property}-{$all}";
			}

			if ( $mobile ) {
				$classes[] = "has-{$property}-{$mobile}-mobile";
			}

			if ( $desktop ) {
				$classes[] = "has-{$property}-{$desktop}-desktop";
			}
		} else {
			$classes[] = "has-{$property}";
		}

		$first->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

add_filter( 'render_block', NS . 'add_position_styles', 10, 2 );
/**
 * Renders block inline positioning styles.
 *
 * @since 1.0.0
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function add_position_styles( string $html, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $html;
	}

	$options = get_block_extra_options();

	foreach ( $options as $key => $args ) {

		if ( ! isset( $style[ $key ] ) ) {
			continue;
		}

		// Has utility class.
		if ( isset( $args['options'] ) ) {
			continue;
		}

		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			continue;
		}

		$styles   = css_string_to_array( $first->getAttribute( 'style' ) );
		$property = _wp_to_kebab_case( $key );
		$all      = $style[ $key ]['all'] ?? '';
		$mobile   = $style[ $key ]['mobile'] ?? '';
		$desktop  = $style[ $key ]['desktop'] ?? '';

		if ( $all ) {
			$styles[ '--' . $property ] = $all;
		}

		if ( $mobile ) {
			$styles[ '--' . $property . '-mobile' ] = $mobile;
		}

		if ( $desktop ) {
			$styles[ '--' . $property . '-desktop' ] = $desktop;
		}

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	return $html;
}
