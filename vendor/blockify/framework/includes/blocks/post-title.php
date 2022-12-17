<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

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
 * @param string $html Block HTML.
 * @param array  $block   Block data.
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

	$padding = $block['attrs']['style']['spacing']['padding'] ?? null;

	if ( $padding ) {
		$styles = [];

		foreach ( $padding as $key => $value ) {
			$styles[ 'padding-' . $key ] = $value;
		}

		$dom = dom( $html );

		// No way of knowing tag.
		$heading = get_dom_element( 'h1', $dom ) ?? get_dom_element( 'h2', $dom ) ?? get_dom_element( 'h3', $dom ) ?? get_dom_element( 'h4', $dom ) ?? get_dom_element( 'h5', $dom ) ?? get_dom_element( 'h6', $dom );

		$class = $heading->getAttribute( 'class' );

		if ( ! $class ) {
			return $html;
		}

		$heading->setAttribute(
			'style',
			css_array_to_string( $styles ) . ';' . $heading->getAttribute( 'style' )
		);

		$html = $dom->saveHTML();
	}

	return $html;
}

