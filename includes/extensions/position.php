<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function add_action;
use function add_filter;
use function explode;
use function implode;
use function is_admin;
use function sprintf;
use function str_contains;

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

add_filter( 'blockify_inline_css', NS . 'enqueue_position_styles', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $css     CSS.
 * @param string $content Page Content.
 *
 * @return string
 */
function enqueue_position_styles( string $css, string $content ): string {
	$options = get_block_extra_options();
	$all     = '';
	$mobile  = '';
	$desktop = '';
	$editor  = is_admin() ? '.editor-styles-wrapper ' : '';

	foreach ( $options as $key => $args ) {
		$property       = _wp_to_kebab_case( $key );
		$select_options = $args['options'] ?? [];

		// Only select controls have utility classes.
		if ( ! $select_options ) {

			if ( $editor || str_contains( $content, " has-$property" ) ) {
				$all .= sprintf(
					$editor . '.has-%1$s{%1$s:var(--%1$s)}',
					$property
				);
			}

			if ( $editor || str_contains( $content, "--$property-mobile" ) ) {
				$mobile .= sprintf(
					$editor . '.has-%1$s{%1$s:var(--%1$s-mobile,var(--%1$s))}',
					$property
				);
			}

			if ( $editor || str_contains( $content, "--$property-desktop" ) ) {
				$desktop .= sprintf(
					$editor . '.has-%1$s{%1$s:var(--%1$s-desktop,var(--%1$s))}',
					$property
				);
			}
		}

		foreach ( $select_options as $option ) {
			if ( ! $option['value'] ) {
				continue;
			}

			if ( $editor || str_contains( $content, " has-{$property}-{$option['value']}" ) ) {
				$all .= sprintf(
					$editor . '.has-%1$s-%2$s{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}

			if ( $editor || str_contains( $content, " has-{$property}-{$option['value']}-mobile" ) ) {
				$mobile .= sprintf(
					$editor . '.has-%1$s-%2$s-mobile{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}

			if ( $editor || str_contains( $content, " has-{$property}-{$option['value']}-desktop" ) ) {
				$desktop .= sprintf(
					$editor . '.has-%1$s-%2$s-desktop{%1$s:%2$s !important}',
					$property,
					$option['value'] ?? '',
				);
			}
		}
	}

	if ( $all ) {
		$css .= $all;
	}

	if ( $mobile ) {
		$css .= sprintf( '@media(max-width:781px){%s}', $mobile );
	}

	if ( $desktop ) {
		$css .= sprintf( '@media(min-width:782px){%s}', $desktop );
	}

	return $css;
}

add_filter( 'render_block', NS . 'add_position_classes', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function add_position_classes( string $content, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $content;
	}

	$options = get_block_extra_options();

	foreach ( $options as $key => $args ) {
		if ( ! isset( $style[ $key ] ) ) {
			continue;
		}

		$dom   = dom( $content );
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

		$content = $dom->saveHTML();
	}

	return $content;
}

add_filter( 'render_block', NS . 'add_position_styles', 10, 2 );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function add_position_styles( string $content, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $content;
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

		$dom   = dom( $content );
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

		$content = $dom->saveHTML();
	}

	return $content;
}
