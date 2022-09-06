<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;

add_filter( 'render_block_core/social-link', NS . 'render_social_link_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.24
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_social_link_block( string $content, array $block ): string {
	$textColor = $block['attrs']['textColor'] ?? null;

	if ( $textColor ) {
		$dom = dom( $content );

		/* @var $first DOMElement */
		$first = $dom->firstChild;

		if ( ! $first ) {
			return $content;
		}

		$styles          = css_string_to_array( $first->getAttribute( 'style' ) );
		$styles['color'] = "var(--wp--preset--color--$textColor)";

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$classes = explode( ' ', $first->getAttribute( 'class' ) );

		$classes[] = 'has-text-color';

		$first->setAttribute( 'class', implode( ' ', $classes ) );

		$content = $dom->saveHTML();
	}

	return $content;
}

