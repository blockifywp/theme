<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMException;
use WP_Block;
use function add_filter;
use function get_the_title;
use function str_replace;

add_filter( 'render_block_core/post-excerpt', NS . 'render_post_excerpt', 10, 3 );
/**
 * Renders post excerpt block.
 *
 * @param string   $block_content The block content.
 * @param array    $block         The block.
 * @param WP_Block $object        The block object.
 *
 * @throws DOMException If the DOM element is not created.
 * @since 1.2.4
 *
 * @return string
 */
function render_post_excerpt( string $block_content, array $block, WP_Block $object ): string {
	$more_text = $block['attrs']['moreText'] ?? '';

	if ( $more_text ) {
		$dom       = dom( $block_content );
		$more_link = get_elements_by_class_name( $dom, 'wp-block-post-excerpt__more-link' )[0] ?? null;

		if ( $more_link ) {
			$screen_reader = $dom->createElement( 'span' );

			$screen_reader->setAttribute( 'class', 'screen-reader-text' );

			$post_id    = $object->context['postId'] ?? '';
			$post_title = get_the_title( $post_id );

			if ( ! $post_title ) {
				$post_title = __( 'this post', 'blockify' );
			}

			$screen_reader->textContent = __( ' about ', 'blockify' ) . ( $post_title );

			$more_link->appendChild( $screen_reader );

			$block_content = $dom->saveHTML();
		}
	}

	return $block_content;
}

add_filter( 'excerpt_more', NS . 'remove_brackets_from_excerpt' );
/**
 * Removes brackets from excerpt more string.
 *
 * @param string $more Read more text.
 *
 * @since 0.0.1
 *
 * @return string
 */
function remove_brackets_from_excerpt( string $more ): string {
	return str_replace( [ '[', ']' ], '', $more );
}
