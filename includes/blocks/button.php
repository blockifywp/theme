<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function explode;
use function implode;
use function str_contains;

add_filter( 'render_block_core/button', NS . 'render_button_block', 10, 2 );
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
	if ( str_contains( $content, 'is-style-outline' ) ) {
		$dom = dom( $content );

		/** @var DOMElement $button */
		$button = $dom->firstChild;

		/** @var DOMElement $link */
		$link    = $button->getElementsByTagName( 'a' )->item( 0 );
		$classes = explode( ' ', $link->getAttribute( 'class' ) );
		$link->setAttribute( 'class', implode( ' ', [
			...$classes,
			'wp-element-button',
		] ) );

		$content = $dom->saveHTML();
	}

	if ( str_contains( $content, '-border-' ) ) {
		$global_settings = \wp_get_global_settings();
		$dom             = dom( $content );

		/** @var DOMElement $button */
		$button = $dom->firstChild;

		/** @var DOMElement $link */
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
