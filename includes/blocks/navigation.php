<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function array_keys;
use function is_array;
use function is_string;
use function str_replace;

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

	$spacing = $block['attrs']['style']['spacing'] ?? null;

	if ( ! $spacing ) {
		return $html;
	}

	$dom = dom( $html );
	$nav = get_dom_element( 'nav', $dom );

	if ( ! $nav ) {
		return $html;
	}

	$styles = css_string_to_array( $nav->getAttribute( 'style' ) );

	foreach ( array_keys( $spacing ) as $attribute ) {
		$prop = $attribute === 'blockGap' ? 'gap' : $attribute;

		if ( is_string( $spacing[ $attribute ] ) ) {
			$styles[ $prop ] = format_custom_property( $spacing[ $attribute ] );
		}

		if ( is_array( $spacing[ $attribute ] ) ) {
			foreach ( array_keys( $spacing[ $attribute ] ) as $side ) {
				$styles[ "$prop-$side" ] = format_custom_property( $spacing[ $attribute ][ $side ] );
			}
		}
	}

	if ( $styles ) {
		$nav->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$buttons = get_elements_by_class_name( $dom, 'wp-block-navigation-submenu__toggle' );

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
