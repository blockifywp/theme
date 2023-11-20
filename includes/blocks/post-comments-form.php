<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function apply_filters;

add_filter( 'render_block_core/post-comments-form', NS . 'render_post_comments_form_block', 10, 2 );
/**
 * Renders the Post Comments Form block.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_post_comments_form_block( string $html, array $block ): string {
	$dom = dom( $html );
	$div = get_dom_element( 'div', $dom );
	$h3  = get_dom_element( 'h3', $div );

	if ( ! $h3 ) {
		return $html;
	}

	change_tag_name(
		apply_filters( 'blockify_comments_form_title_tag', 'h4' ),
		$h3
	);

	return $dom->saveHTML();
}

add_filter( 'register_block_type_args', NS . 'register_comments_args', 10, 2 );
/**
 * Registers the Post Comments Form block.
 *
 * @param array  $args       The block arguments.
 * @param string $block_type The block handle.
 *
 * @return array
 */
function register_comments_args( array $args, string $block_type ): array {
	if ( 'core/comments' === $block_type ) {
		$args['available_context'] = [
			'postId' => '',
		];
	}

	return $args;
}
