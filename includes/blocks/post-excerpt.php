<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_action;
use function add_filter;
use function add_post_type_support;
use function apply_filters;
use function explode;
use function get_post_field;
use function get_the_excerpt;
use function get_the_ID;
use function get_the_title;
use function implode;
use function in_array;
use function is_array;
use function str_replace;
use function trim;
use function wp_trim_words;

add_filter( 'render_block_core/post-excerpt', NS . 'render_post_excerpt', 10, 3 );
/**
 * Renders post excerpt block.
 *
 * @since 1.2.4
 *
 * @param string   $block_content The block content.
 * @param array    $block         The block.
 * @param WP_Block $object        The block object.
 *
 * @return string
 */
function render_post_excerpt( string $block_content, array $block, WP_Block $object ): string {
	$hide_read_more = $block['attrs']['hideReadMore'] ?? false;

	if ( $hide_read_more ) {
		$dom   = dom( $block_content );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			return $block_content;
		}

		$read_more = get_elements_by_class_name( 'wp-block-post-excerpt__more-text', $dom )[0] ?? null;

		if ( $read_more ) {
			$read_more->parentNode->removeChild( $read_more );
		} else {
			$classes = explode( ' ', $first->getAttribute( 'class' ) );

			if ( ! in_array( 'hide-read-more', $classes, true ) ) {
				$classes[] = 'hide-read-more';
			}

			$first->setAttribute( 'class', implode( ' ', $classes ) );
		}

		$block_content = $dom->saveHTML();
	}

	$more_text = $block['attrs']['moreText'] ?? '';

	if ( $more_text ) {
		$dom       = dom( $block_content );
		$more_link = get_elements_by_class_name( 'wp-block-post-excerpt__more-link', $dom )[0] ?? null;

		if ( $more_link ) {
			$screen_reader = create_element( 'span', $dom );

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

	$excerpt_length  = $block['attrs']['excerptLength'] ?? apply_filters( 'excerpt_length', 55 );
	$default_excerpt = $block['attrs']['defaultExcerpt'] ?? '';
	$custom_excerpt  = get_post_field( 'post_excerpt', $object->context['postId'] ?? get_the_ID() );
	$excerpt         = $custom_excerpt ?: $default_excerpt;

	if ( ! $excerpt ) {
		$excerpt = get_the_excerpt();
	}

	$dom = dom( $block_content );
	$div = get_elements_by_class_name( 'wp-block-post-excerpt', $dom )[0] ?? null;
	$p   = get_elements_by_class_name( 'wp-block-post-excerpt__excerpt', $dom )[0] ?? null;

	if ( ! $p ) {
		$div = $div ?? create_element( 'div', $dom );
		$p   = create_element( 'p', $dom );

		$p->textContent = $excerpt;
		$p->setAttribute( 'class', 'wp-block-post-excerpt__excerpt' );

		$div->appendChild( $p );
	}

	$p->textContent = $excerpt;
	$div_classes    = explode( ' ', $div->getAttribute( 'class' ) );
	$styles         = [];
	$text_color     = $block['attrs']['textColor'] ?? null;

	if ( $text_color ) {
		$custom_property = ! str_contains_any( $text_color, '#', 'rgb', 'hsl' );

		$styles['color'] = $custom_property ? 'var(--wp--preset--color--' . $text_color . ')' : $text_color;
	}

	if ( $block['attrs']['textAlign'] ?? '' ) {
		$div_classes[] = 'has-text-align-' . $block['attrs']['textAlign'];
	}

	$margin = $block['attrs']['style']['spacing']['margin'] ?? '';

	if ( is_array( $margin ) ) {
		foreach ( $margin as $side => $value ) {
			$styles[ 'margin-' . $side ] = $value;
		}
	}

	$padding = $block['attrs']['style']['spacing']['padding'] ?? '';

	if ( is_array( $padding ) ) {
		foreach ( $padding as $side => $value ) {
			$styles[ 'padding-' . $side ] = $value;
		}
	}

	if ( $styles ) {
		$div->setAttribute( 'style', css_array_to_string( $styles ) );
	}

	$align = $block['attrs']['align'] ?? '';

	if ( $align ) {
		$div_classes[] = 'align' . $align;
	}

	$div->setAttribute( 'class', trim( implode( ' ', $div_classes ) ) );
	$dom->appendChild( $div );

	// Limit length.
	$p->nodeValue = wp_trim_words(
		$p->nodeValue ?? '',
		$excerpt_length,
	);

	return $dom->saveHTML();
}

add_action( 'after_setup_theme', NS . 'add_page_excerpt_support' );
/**
 * Adds excerpt support to pages.
 *
 * @since 1.3.0
 *
 * @return void
 */
function add_page_excerpt_support(): void {
	add_post_type_support( 'page', 'excerpt' );
}

add_filter( 'excerpt_more', NS . 'remove_brackets_from_excerpt' );
/**
 * Removes brackets from excerpt more string.
 *
 * @since 1.3.0
 *
 * @param string $more Read more text.
 *
 * @return string
 */
function remove_brackets_from_excerpt( string $more ): string {
	return str_replace( [ '[', ']' ], '', $more );
}
