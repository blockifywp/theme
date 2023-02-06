<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function get_option;
use function get_post;
use function is_home;
use function sanitize_title_with_dashes;
use function str_contains;
use function str_replace;
use function wp_strip_all_tags;

add_filter( 'render_block_core/post-title', NS . 'render_post_title_block', 10, 2 );
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
function render_post_title_block( string $html, array $block ): string {
	if ( is_home() && str_contains( $html, '<h1' ) ) {
		$text           = wp_strip_all_tags( $html );
		$page_for_posts = get_post( get_option( 'page_for_posts' ) );

		if ( $page_for_posts->post_type === 'page' ) {
			$title = $page_for_posts->post_title;
		} else {
			$title = __( 'Latest Posts', 'blockify' );
		}

		$html = str_replace( $text, $title, $html );
	}

	$tag     = 'h' . ( $block['attrs']['level'] ?? 2 );
	$dom     = dom( $html );
	$heading = get_dom_element( $tag, $dom );

	if ( $heading instanceof DOMElement ) {
		$heading->setAttribute(
			'id',
			$block['attrs']['anchor'] ?? sanitize_title_with_dashes( $heading->textContent )
		);
	}

	return $dom->saveHTML();
}

