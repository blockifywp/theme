<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function wp_get_global_settings;

add_filter( 'render_block', NS . 'render_box_shadow', 10, 2 );
/**
 * Adds box shadow to blocks.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_box_shadow( string $html, array $block ): string {
	$color       = $block['attrs']['style']['boxShadow']['color'] ?? null;
	$hover_color = $block['attrs']['style']['boxShadow']['hover']['color'] ?? null;

	if ( $color || $hover_color ) {
		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			return $html;
		}

		$styles  = css_string_to_array( $first->getAttribute( 'style' ) );
		$palette = wp_get_global_settings()['color']['palette']['theme'] ?? [];

		foreach ( $palette as $theme_color ) {
			if ( $theme_color['color'] === $color ) {
				$styles['--wp--custom--box-shadow--color'] = "var(--wp--preset--color--{$theme_color['slug']})";
			}

			if ( $theme_color['color'] === $hover_color ) {
				$styles['--wp--custom--box-shadow--hover--color'] = "var(--wp--preset--color--{$theme_color['slug']})";
			}
		}

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

