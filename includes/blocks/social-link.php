<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'render_block_core/social-link', NS . 'render_social_link_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.24
 *
 * @param string $html Block HTML.
 * @param array  $block   Block data.
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

	return $html;
}

