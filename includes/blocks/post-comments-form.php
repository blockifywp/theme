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
		$h3,
		apply_filters( 'blockify_comments_form_title_tag', 'h4' )
	);

	return $dom->saveHTML();
}

