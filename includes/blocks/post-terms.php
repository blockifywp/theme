<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_key_last;
use function array_unique;
use function explode;
use function get_post_type;
use function get_term_link;
use function get_terms;
use function in_array;
use function is_array;
use function is_front_page;
use function is_singular;
use function is_string;
use function trim;

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
		$html    = dom( $html );
		$div     = get_dom_element( 'div', $html );
		$classes = array_unique( [
			'wp-block-post-terms',
			'flex',
			'wrap',
			'justify-' . ( $block['attrs']['align'] ?? 'left' ),
			...( explode( ' ', $div->getAttribute( 'class' ) ) ),
		] );

		$div->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $html->saveHTML();
	}

	$term = $block['attrs']['term'] ?? '';

	if ( ! $term ) {
		return $html;
	}

	// Remove empty separator elements.
	$separator = $block['attrs']['separator'] ?? null;

	if ( $separator === '' ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {

			foreach ( $div->childNodes as $child ) {

				if ( $child->nodeName === 'span' && ! trim( $child->nodeValue ) ) {
					$div->removeChild( $child );
				}
			}

			$html = $dom->saveHTML();
		}
	}

	$show_all = $block['attrs']['showAll'] ?? false;

	if ( ! $html || $show_all ) {
		$dom         = dom( '<div></div>' );
		$div         = get_dom_element( 'div', $dom );
		$div_classes = [
			...( explode( ' ', $block['attrs']['className'] ?? '' ) ),
			'wp-block-post-terms',
			'taxonomy-' . $term,
		];

		$text_align = $block['attrs']['textAlign'] ?? null;

		if ( $text_align ) {
			$div_classes[] = 'has-text-align-' . $text_align;
			$div_classes[] = 'justify-' . $text_align;
		}

		$text_color = $block['attrs']['textColor'] ?? null;

		if ( $text_color ) {
			$div_classes[] = 'has-' . $text_color . '-color';
		}

		$taxonomy  = get_taxonomy( $term );
		$post_type = $taxonomy->object_type[0] ?? get_post_type();

		if ( ( is_singular() && ! is_front_page() ) || ! $show_all ) {
			$p            = create_element( 'p', $dom );
			$p->nodeValue = $taxonomy->labels->not_found ?? '';

			$p->setAttribute( 'class', 'margin-top-auto margin-bottom-auto' );

			$div->appendChild( $p );

		} else {
			$a            = create_element( 'a', $dom );
			$archive_link = get_post_type_archive_link( $post_type );

			if ( ! is_string( $archive_link ) ) {
				return '';
			}

			$a->setAttribute( 'href', $archive_link );
			$a->setAttribute( 'class', 'wp-block-post-terms__link' );
			$a->setAttribute( 'rel', 'tag' );
			$a->nodeValue = __( 'All', 'blockify' );

			$div->appendChild( $a );

			if ( ! in_array( 'is-style-badges', $div_classes, true ) ) {
				$div->appendChild(
					$dom->createTextNode( $separator ?? '' )
				);
			}

			$terms = get_terms(
				[
					'taxonomy'   => $term,
					'hide_empty' => true,
				]
			);

			foreach ( $terms as $index => $term_object ) {
				$a         = create_element( 'a', $dom );
				$term_link = get_term_link( $term_object );

				if ( ! is_string( $term_link ) ) {
					continue;
				}

				$a->setAttribute( 'href', $term_link );
				$a->setAttribute( 'class', 'wp-block-post-terms__link' );
				$a->setAttribute( 'rel', 'tag' );
				$a->nodeValue = $term_object->name;

				$div->appendChild( $a );

				if ( ! in_array( 'is-style-badges', $div_classes, true ) && $index !== array_key_last( $terms ) ) {
					$div->appendChild(
						$dom->createTextNode( $separator ?? '' )
					);
				}
			}
		}

		$styles = [];
		$margin = $block['attrs']['style']['spacing']['margin'] ?? 0;

		if ( $margin ) {
			if ( is_array( $margin ) ) {
				$styles = add_shorthand_property( $styles, 'margin', $margin );
			} else {
				$styles['margin'] = format_custom_property( $margin );
			}
		}

		$text_decoration = $block['attrs']['style']['typography']['textDecoration'] ?? null;

		if ( $text_decoration ) {
			$styles['text-decoration'] = $text_decoration;
		}

		$font_size = $block['attrs']['fontSize'] ?? null;

		if ( $font_size ) {
			$div_classes[] = 'has-font-size';
			$div_classes[] = 'has-' . $font_size . '-font-size';
		}

		$font_size_custom = $block['attrs']['style']['typography']['fontSize'] ?? null;

		if ( $font_size_custom ) {
			$styles['font-size'] = format_custom_property( $font_size_custom );
		}

		if ( $font_size_custom ) {
			$div_classes[] = 'has-font-size';
		}

		$padding = $block['attrs']['style']['spacing']['padding'] ?? null;

		if ( $padding ) {
			$styles = add_shorthand_property( $styles, 'padding', $padding );
		}

		$div->setAttribute( 'class', implode( ' ', $div_classes ) );

		if ( $styles ) {
			$div->setAttribute( 'style', css_array_to_string( $styles ) );
		}

		$html = $dom->saveHTML();
	}

	$dom = dom( $html );

	// First child either div or ul.
	$first = dom_element( $dom->firstChild );

	if ( $first ) {
		$styles = css_string_to_array( $first->getAttribute( 'style' ) );

		$gap = $block['attrs']['style']['spacing']['blockGap'] ?? '';

		if ( $gap ) {
			$styles['gap'] = format_custom_property( $gap );
		}

		$font_weight = $block['attrs']['style']['typography']['fontWeight'] ?? null;

		if ( $font_weight ) {
			$styles['font-weight'] = $font_weight;
		}

		$border = $block['attrs']['style']['border'] ?? null;

		if ( $border['radius'] ?? '' ) {
			$styles['--wp--custom--border--radius'] = $border['radius'];
		}

		$first->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$class_names = explode( ' ', $block['attrs']['className'] ?? '' );

	if ( in_array( 'is-style-badges', $class_names, true ) ) {
		$padding = $block['attrs']['style']['spacing']['padding'] ?? null;

		if ( $padding ) {
			$styles = css_string_to_array( $first->getAttribute( 'style' ) );
			unset( $styles['padding'] );

			$styles = add_shorthand_property( $styles, '--wp--custom--badge--padding', $padding );

			$first->setAttribute( 'style', css_array_to_string( $styles ) );
		}
	}

	return $dom->saveHTML();
}
