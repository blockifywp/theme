<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function explode;
use function implode;
use function str_contains;
use function add_filter;

add_filter( 'render_block', NS . 'render_button_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_button_block( string $content, array $block ): string {
	if ( 'core/button' !== $block['blockName'] ) {
		return $content;
	}

	if ( str_contains( $content, '-border-' ) ) {
		$global_settings = \wp_get_global_settings();
		$dom = dom( $content );

		/**
		 * @var $button DOMElement Fixes button link border inheritance.
		 */
		$button = $dom->firstChild;

		/**
		 * @var $link DOMElement
		 */
		$link        = $button->getElementsByTagName( 'a' )->item( 0 );
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

		$content = $dom->saveHTML();
	}

	return $content;
}
