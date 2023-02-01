<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
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

	$text_align = $block['attrs']['textAlign'] ?? null;

	$dom = dom( $html );
	$h1  = $dom->createElement( 'h1' );

	$classes = [
		'wp-block-query-title',
	];

	if ( $text_align ) {
		$classes[] = 'has-text-align-' . $text_align;
	}

	$h1->setAttribute( 'class', implode( ' ', $classes ) );

	$margin  = $block['attrs']['style']['spacing']['margin'] ?? [];
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];
	$styles  = [];

	foreach ( $margin as $key => $value ) {
		$styles[ 'margin-' . $key ] = $value;
	}

	foreach ( $padding as $key => $value ) {
		$styles[ 'padding-' . $key ] = $value;
	}

	$h1->setAttribute( 'style', css_array_to_string( $styles ) );

	$h1->nodeValue = get_the_title( $page_for_posts );

	$dom->appendChild( $h1 );

	return $dom->saveHTML();
}

