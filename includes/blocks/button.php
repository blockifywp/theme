<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
use function implode;
use function str_contains;
use function wp_get_global_settings;

add_filter( 'render_block_core/button', NS . 'render_button_block', 10, 2 );
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
function render_button_block( string $html, array $block ): string {
	if ( str_contains( $html, 'is-style-outline' ) ) {
		$dom    = dom( $html );
		$button = get_dom_element( 'div', $dom );
		$anchor = get_dom_element( 'a', $button );

		if ( $anchor ) {
			$classes = explode( ' ', $anchor->getAttribute( 'class' ) );
			$anchor->setAttribute(
				'class',
				implode(
					' ',
					[
						...$classes,
						'wp-element-button',
					]
				)
			);

			$html = $dom->saveHTML();
		}
	}

	if ( str_contains( $html, '-border-' ) ) {
		$global_settings = wp_get_global_settings();
		$dom             = dom( $html );
		$button          = get_dom_element( 'div', $dom );
		$link            = get_dom_element( 'a', $dom );

		if ( ! $button || ! $link ) {
			return $html;
		}

		$classes     = explode( ' ', $button->getAttribute( 'class' ) );
		$styles      = explode( ';', $button->getAttribute( 'style' ) );
		$new_classes = [];
		$new_styles  = [];

		foreach ( $classes as $class ) {
			if ( ! str_contains( $class, '-border-' ) ) {
				$new_classes[] = $class;
			}
		}

		foreach ( $styles as $style ) {
			if ( ! str_contains( $style, 'border-' ) ) {
				$new_styles[] = $style;
			}
		}

		$border_width = $block['attrs']['style']['border']['width'] ?? null;
		$border_color = $block['attrs']['style']['border']['color'] ?? null;

		$link_styles = explode( ';', $link->getAttribute( 'style' ) );

		if ( $border_width || $border_color ) {
			$border_width  = $border_width ?? $global_settings['custom']['border']['width'];
			$link_styles[] = "line-height:calc(1em - $border_width)";
		}

		$link->setAttribute( 'style', implode( ';', $link_styles ) );

		$button->setAttribute( 'class', implode( ' ', $new_classes ) );
		$button->setAttribute( 'style', implode( ';', $new_styles ) );

		if ( ! $button->getAttribute( 'style' ) ) {
			$button->removeAttribute( 'style' );
		}

		$html = $dom->saveHTML();
	}

	return $html;
}
