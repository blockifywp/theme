<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function add_filter;
use function explode;
use function implode;
use function sprintf;
use function str_contains;

add_filter( 'blockify_editor_data', NS . 'register_responsive_settings', 11 );
/**
 * Add default block supports.
 *
 * @param array $config Blockify editor config.
 *
 * @since 0.9.10
 *
 * @return array
 */
function register_responsive_settings( array $config = [] ): array {
	$config['positionOptions'] = get_block_extra_options();

	return $config;
}

add_filter( 'blockify_inline_css', NS . 'get_block_extra_styles', 11, 3 );
/**
 * Conditionally adds CSS for utility classes
 *
 * @param string $css       Inline CSS.
 * @param string $content   Page Content.
 * @param bool   $is_editor Is editor page.
 *
 * @since 0.9.19
 *
 * @return string
 */
function get_block_extra_styles( string $css, string $content, bool $is_editor ): string {
	$options = get_block_extra_options();
	$all     = '';
	$mobile  = '';
	$desktop = '';

	foreach ( $options as $key => $args ) {
		$property       = _wp_to_kebab_case( $key );
		$select_options = $args['options'] ?? [];

		foreach ( $select_options as $option ) {
			$value = $option['value'] ?? '';

			if ( ! $value ) {
				continue;
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$value}" ) ) {
				$all .= sprintf(
					'.has-%1$s-%2$s{%1$s:%2$s !important}',
					$property,
					$value,
				);
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$value}-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s-%2$s-mobile{%1$s:%2$s !important}',
					$property,
					$value,
				);
			}

			if ( $is_editor || str_contains( $content, " has-{$property}-{$value}-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s-%2$s-desktop{%1$s:%2$s !important}',
					$property,
					$value,
				);
			}
		}

		// Has custom value.
		if ( ! $select_options ) {

			if ( $is_editor || str_contains( $content, " has-$property" ) ) {
				$all .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s)}',
					$property
				);
			}

			if ( $is_editor || str_contains( $content, "--$property-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-mobile,var(--%1$s))}',
					$property
				);
			}

			if ( $is_editor || str_contains( $content, "--$property-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-desktop,var(--%1$s))}',
					$property
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
 * Adds inline block positioning classes.
 *
 * @param string $html  Block content.
 * @param array  $block Block data.
 *
 * @since 1.0.0
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
		if ( ! isset( $style[ $key ] ) || $style[ $key ] === '' ) {
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
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @since 1.0.0
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


