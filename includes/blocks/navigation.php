<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_keys;
use function explode;
use function implode;
use function in_array;
use function is_array;
use function is_string;
use function str_contains;
use function str_replace;
use function trim;
use function wp_get_global_settings;
use function wp_get_global_styles;
use function wp_list_pluck;

add_filter( 'render_block_core/navigation', NS . 'render_navigation_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_navigation_block( string $html, array $block ): string {

	// Replace invalid root relative URLs.
	$html = str_replace( 'http://./', './', $html );
	$dom  = dom( $html );
	$nav  = get_dom_element( 'nav', $dom );

	if ( ! $nav ) {
		return $html;
	}

	$styles       = css_string_to_array( $nav->getAttribute( 'style' ) );
	$classes      = explode( ' ', $nav->getAttribute( 'class' ) );
	$overlay_menu = $block['attrs']['overlayMenu'] ?? true;
	$filter       = $block['attrs']['style']['filter'] ?? null;

	if ( $overlay_menu && $filter ) {

		$filter_value = '';

		foreach ( $filter as $property => $value ) {
			if ( $property === 'backdrop' ) {
				continue;
			}

			$value = format_custom_property( $value ) . 'px';

			$filter_value .= "$property($value) ";
		}

		$styles['--wp--custom--nav--filter'] = trim( $filter_value );

		$background_color = $block['attrs']['backgroundColor'] ?? $block['attrs']['style']['color']['background'] ?? '';

		$global_settings = wp_get_global_settings();
		$color_slugs     = wp_list_pluck( $global_settings['color']['palette']['theme'] ?? [], 'slug' );

		if ( in_array( $background_color, $color_slugs, true ) ) {
			$background_color = "var(--wp--preset--color--{$background_color})";
		}

		if ( $background_color ) {
			$styles['--wp--custom--nav--background-color'] = format_custom_property( $background_color );
		}

		$nav->setAttribute( 'style', css_array_to_string( $styles ) );

		if ( $filter['backdrop'] ?? null ) {
			$classes[] = 'has-backdrop-filter';
		}

		$nav->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	$spacing = $block['attrs']['style']['spacing'] ?? null;

	if ( ! $spacing ) {
		return $html;
	}

	$padding = $spacing['padding'] ?? null;

	unset( $spacing['padding'] );

	foreach ( array_keys( $spacing ) as $attribute ) {
		$prop = $attribute === 'blockGap' ? 'gap' : $attribute;

		if ( is_string( $spacing[ $attribute ] ) ) {
			$styles[ $prop ] = format_custom_property( $spacing[ $attribute ] );
		}

		if ( is_array( $spacing[ $attribute ] ) ) {
			foreach ( array_keys( $spacing[ $attribute ] ) as $side ) {
				$styles["$prop-$side"] = format_custom_property( $spacing[ $attribute ][ $side ] );
			}
		}
	}

	if ( $padding ) {
		if ( is_array( $padding ) ) {
			$styles['--wp--custom--nav--padding'] = format_custom_property( $padding['top'] ?? 0 );
		} else {
			$styles['--wp--custom--nav--padding'] = format_custom_property( $padding );
		}
	}

	if ( $styles ) {
		$nav->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$buttons = get_elements_by_class_name( 'wp-block-navigation-submenu__toggle', $dom );

	foreach ( $buttons as $button ) {
		$span = $button->nextSibling;

		if ( ! $span || $span->tagName !== 'span' ) {
			continue;
		}

		$span->parentNode->removeChild( $span );
		$button->appendChild( $span );
	}

	$html = $dom->saveHTML();

	return $html;
}

add_filter( 'blockify_inline_css', NS . 'add_submenu_border_css', 11, 3 );
/**
 * Adds CSS for submenu borders.
 *
 * @since 0.0.2
 *
 * @param string $css     CSS string.
 * @param string $content Page HTML content.
 * @param bool   $all     Is editor.
 *
 * @return string
 */
function add_submenu_border_css( string $css, string $content, bool $all ): string {

	if ( $all || str_contains( $content, 'wp-block-navigation__submenu-container' ) ) {

		$global_styles = wp_get_global_styles();
		$border        = $global_styles['blocks']['core/navigation-submenu']['border'] ?? [];
		$styles        = [];

		foreach ( [ 'top', 'right', 'bottom', 'left' ] as $side ) {
			if ( ! isset( $border[ $side ] ) ) {
				continue;
			}

			if ( $border[ $side ]['width'] ?? '' ) {
				$styles["border-$side-width"] = $border[ $side ]['width'];
			}

			if ( $border[ $side ]['style'] ?? '' ) {
				$styles["border-$side-style"] = $border[ $side ]['style'];
			}

			if ( $border[ $side ]['color'] ?? '' ) {
				$styles["border-$side-color"] = format_custom_property( $border[ $side ]['color'] );
			}
		}

		$radius = $border['radius'] ?? null;

		if ( $radius ) {
			$styles['border-radius'] = format_custom_property( $radius );
		}

		if ( $styles ) {
			$css .= '.wp-block-navigation-submenu{border:0}.wp-block-navigation .wp-block-navigation-item .wp-block-navigation__submenu-container{' . css_array_to_string( $styles ) . '}';
		}
	}

	return $css;
}
