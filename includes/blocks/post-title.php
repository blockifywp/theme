<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function get_option;
use function get_post;
use function get_the_ID;
use function is_home;
use function is_null;
use function str_contains;
use function str_replace;
use function strip_tags;

add_filter( 'render_block_core/post-title', NS . 'render_post_title_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.1
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_post_title_block( string $content, array $block ): string {
	if ( is_home() && str_contains( $content, '<h1' ) ) {
		$text           = strip_tags( $content );
		$page_for_posts = get_post( get_option( 'page_for_posts' ) );

		if ( $page_for_posts->post_type === 'page' ) {
			$title = $page_for_posts->post_title;
		} else {
			$title = __( 'Latest Posts', 'blockify' );
		}

		$content = str_replace( $text, $title, $content );
	}

	if ( $padding = $block['attrs']['style']['spacing']['padding'] ?? false ) {
		$styles = [];
		foreach ( $padding as $key => $value ) {
			$styles[ 'padding-' . $key ] = $value;
		}

		$dom = dom( $content );

		/**
		 * @var \DOMElement $first
		 */
		$first = $dom->firstChild;

		$first->setAttribute(
			'style',
			css_array_to_string( $styles ) . ';' . $first->getAttribute( 'style' )
		);

		$content = $dom->saveHTML();
	}

	return $content;
}

