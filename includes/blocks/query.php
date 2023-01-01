<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMText;
use function add_filter;
use function get_object_taxonomies;
use function get_permalink;
use function get_post_type;
use function get_posts;
use function get_the_ID;
use function get_the_terms;
use function is_a;
use function trim;
use function wp_list_pluck;

add_filter( 'render_block_core/query', NS . 'render_query_block', 10, 2 );
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
function render_query_block( string $html, array $block ): string {
	$block_gap = $block['attrs']['style']['spacing']['blockGap'] ?? null;

	if ( $block_gap ) {
		$dom    = dom( $html );
		$div    = get_dom_element( 'div', $dom );
		$styles = css_string_to_array( $div->getAttribute( 'style' ) );

		$styles['--wp--style--block-gap'] = format_custom_property( $block_gap );

		$div->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );

	if ( ! $div ) {
		return $html;
	}

	if ( ! is_a( $div->firstChild, DOMText::class ) ) {
		return $html;
	}

	$inner_html = trim( $div->firstChild->wholeText );

	if ( $inner_html ) {
		return $html;
	}

	$taxonomies = get_object_taxonomies(
		get_post_type(),
		'objects',
	);

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( get_the_ID(), $taxonomy->name );

		if ( ! $terms ) {
			continue;
		}

		$related_articles = get_posts(
			[
				'post_type'      => get_post_type(),
				'posts_per_page' => 3,
				'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $taxonomy->name,
						'field'    => 'term_id',
						'terms'    => wp_list_pluck( $terms, 'term_id' ),
					],
				],
			]
		);

		foreach ( $related_articles as $related_article ) {
			if ( $related_article->ID === get_the_ID() ) {
				continue;
			}

			$a = $dom->createElement( 'a' );

			$a->setAttribute( 'href', get_permalink( $related_article ) );
			$a->textContent = $related_article->post_title;

			$div->appendChild( $a );
		}
	}

	return $dom->saveHTML();
}
