<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function esc_attr;
use function esc_html;
use function get_option;
use function implode;
use function is_front_page;
use function is_home;

add_filter( 'render_block_core/query-title', NS . 'render_archive_title_block', 10, 2 );
/**
 * Renders the Archive Title block.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_archive_title_block( string $html, array $block ): string {
	if ( $html ) {
		return $html;
	}

	if ( ! is_home() || is_front_page() ) {
		return $html;
	}

	$page_for_posts = get_option( 'page_for_posts' );

	if ( ! $page_for_posts ) {
		return '';
	}

	$dom = dom( $html );
	$h1  = create_element( 'h1', $dom );

	$classes = [
		'wp-block-query-title',
	];

	$text_align = $block['attrs']['textAlign'] ?? null;

	if ( $text_align ) {
		$classes[] = 'has-text-align-' . esc_attr( $text_align );
	}

	$h1->setAttribute( 'class', implode( ' ', $classes ) );

	$styles  = [];
	$margin  = $block['attrs']['style']['spacing']['margin'] ?? [];
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];
	$styles  = add_shorthand_property( $styles, 'margin', $margin );
	$styles  = add_shorthand_property( $styles, 'padding', $padding );

	$h1->setAttribute( 'style', css_array_to_string( $styles ) );
	$h1->nodeValue = esc_html( get_the_title( $page_for_posts ) );
	$dom->appendChild( $h1 );

	return $dom->saveHTML();
}

