<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function trim;
use function wp_get_global_settings;

add_filter( 'render_block_core/social-links', NS . 'render_social_links_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.24
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_social_links_block( string $html, array $block ): string {
	$dom = dom( $html );
	$ul  = get_dom_element( 'ul', $dom );

	if ( ! $ul || ! $ul->hasChildNodes() ) {
		return $html;
	}

	$global_settings = wp_get_global_settings();
	$color_palette   = $global_settings['color']['palette']['theme'] ?? [];

	foreach ( $ul->childNodes as $child ) {
		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		if ( $child->nodeName === 'li' ) {
			$styles = css_string_to_array( $child->getAttribute( 'style' ) );

			if ( ! $styles['color'] ) {
				continue;
			}

			foreach ( $color_palette as $color ) {
				if ( trim( $styles['color'] ) === trim( $color['color'] ) ) {
					$styles['color'] = "var(--wp--preset--color--{$color['slug']})";
					$child->setAttribute( 'style', css_array_to_string( $styles ) );

					break;
				}
			}

			$child->setAttribute( 'style', css_array_to_string( $styles ) );
		}
	}

	return $dom->saveHTML();
}

