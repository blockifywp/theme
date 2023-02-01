<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function get_term_link;
use function get_terms;
use function str_contains;
use function str_replace;

add_filter( 'render_block_core/post-terms', NS . 'render_post_terms_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.1
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_post_terms_block( string $html, array $block ): string {
	if ( $block['attrs']['align'] ?? null ) {
		$html = str_replace(
			[
				'wp-block-post-terms',
				'rel="tag"',
			],
			[
				'wp-block-post-terms flex justify-' . $block['attrs']['align'],
				'class="wp-block-post-terms__link" rel="tag"',
			],
			$html
		);
	}

	// Remove empty separator elements.
	$separator = $block['attrs']['separator'] ?? null;

	if ( $separator === '' ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {
			$separators = $div->getElementsByTagName( 'span' );

			foreach ( $separators as $empty_separator ) {
				$empty_separator->parentNode->removeChild( $empty_separator );
			}

			$html = $dom->saveHTML();
		}
	}

	if ( str_contains( $html, 'all-terms' ) ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( ! $div ) {
			return $html;
		}

		$terms = get_terms(
			[
				'taxonomy'   => $block['attrs']['term'],
				'hide_empty' => true,
			]
		);

		$links = get_elements_by_class_name( $div, 'wp-block-post-terms__link' );

		foreach ( $links as $link ) {
			$link->parentNode->removeChild( $link );
		}

		foreach ( $terms as $term ) {
			$link = $dom->createElement( 'a' );

			$link->setAttribute( 'href', get_term_link( $term ) );

			$link->setAttribute( 'class', 'wp-block-post-terms__link' );

			$link->setAttribute( 'rel', 'tag' );

			$link->nodeValue = $term->name;

			$div->appendChild( $link );
		}

		$html = $dom->saveHTML();
	}

	$margin = $block['attrs']['style']['spacing']['margin'] ?? [];

	if ( $margin ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {
			$styles = css_string_to_array( $div->getAttribute( 'style' ) );

			foreach ( $margin as $key => $value ) {
				$styles[ 'margin-' . $key ] = format_custom_property( $value );
			}

			$div->setAttribute( 'style', css_array_to_string( $styles ) );

			$html = $dom->saveHTML();
		}
	}

	return $html;
}
