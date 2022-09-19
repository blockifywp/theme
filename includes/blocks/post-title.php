<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function get_option;
use function get_post;
use function is_home;
use function str_contains;
use function str_replace;
use function wp_strip_all_tags;

add_filter( 'render_block_core/post-title', NS . 'render_post_title_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.1
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_post_title_block( string $content, array $block ): string {
	if ( is_home() && str_contains( $content, '<h1' ) ) {
		$text           = wp_strip_all_tags( $content );
		$page_for_posts = get_post( get_option( 'page_for_posts' ) );

		if ( $page_for_posts->post_type === 'page' ) {
			$title = $page_for_posts->post_title;
		} else {
			$title = __( 'Latest Posts', 'blockify' );
		}

		$content = str_replace( $text, $title, $content );
	}

	$padding = $block['attrs']['style']['spacing']['padding'] ?? null;

	if ( $padding ) {
		$styles = [];

		foreach ( $padding as $key => $value ) {
			$styles[ 'padding-' . $key ] = $value;
		}

		$dom = dom( $content );

		// No way of knowing tag.
		$h1 = $dom->getElementsByTagName( 'h1' )->item( 0 );
		$h2 = $dom->getElementsByTagName( 'h2' )->item( 0 );
		$h3 = $dom->getElementsByTagName( 'h3' )->item( 0 );
		$h4 = $dom->getElementsByTagName( 'h4' )->item( 0 );
		$h5 = $dom->getElementsByTagName( 'h5' )->item( 0 );
		$h6 = $dom->getElementsByTagName( 'h6' )->item( 0 );

		/* @var \DOMElement $heading Heading element. */
		$heading = $h1 ?? $h2 ?? $h3 ?? $h4 ?? $h5 ?? $h6;

		$heading->setAttribute(
			'style',
			css_array_to_string( $styles ) . ';' . $heading->getAttribute( 'style' )
		);

		$content = $dom->saveHTML();
	}

	return $content;
}

