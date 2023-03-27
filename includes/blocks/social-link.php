<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function file_get_contents;

add_filter( 'render_block_core/social-link', NS . 'render_social_link_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @since 0.0.24
 *
 * @return string
 */
function render_social_link_block( string $html, array $block ): string {
	$textColor = $block['attrs']['textColor'] ?? null;

	if ( $textColor ) {
		$dom       = dom( $html );
		$list_item = get_dom_element( 'li', $dom );

		if ( ! $list_item ) {
			return $html;
		}

		$styles          = css_string_to_array( $list_item->getAttribute( 'style' ) );
		$styles['color'] = "var(--wp--preset--color--$textColor)";

		$list_item->setAttribute( 'style', css_array_to_string( $styles ) );

		$classes = explode( ' ', $list_item->getAttribute( 'class' ) );

		$classes[] = 'has-text-color';

		$list_item->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	$service = $block['attrs']['service'] ?? null;

	if ( $service === 'slack' ) {
		$dom         = dom( $html );
		$li          = get_dom_element( 'li', $dom );
		$a           = get_dom_element( 'a', $li );
		$default_svg = get_dom_element( 'svg', $a );

		if ( ! $default_svg ) {
			return $html;
		}

		$svg_dom = dom( file_get_contents( get_dir() . 'assets/svg/social/slack.svg' ) );
		$svg     = get_dom_element( 'svg', $svg_dom );

		$svg->setAttribute( 'fill', 'currentColor' );
		$svg->setAttribute( 'width', '24' );
		$svg->setAttribute( 'height', '24' );
		$svg->setAttribute( 'aria-hidden', 'true' );
		$svg->setAttribute( 'focusable', 'false' );
		$svg->setAttribute( 'role', 'img' );

		$imported = $dom->importNode( $svg, true );

		$a->appendChild( $imported );
		$a->removeChild( $default_svg );

		$html = $dom->saveHTML( $li );
	}

	$url = $block['attrs']['url'] ?? null;

	if ( $url === '#' ) {
		$dom = dom( $html );
		$li  = get_dom_element( 'li', $dom );
		$a   = get_dom_element( 'a', $li );

		$a->setAttribute( 'href', '#' );

		$html = $dom->saveHTML( $li );
	}

	return $html;
}
