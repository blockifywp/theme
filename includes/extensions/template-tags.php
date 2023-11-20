<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use function add_filter;
use function apply_filters;
use function esc_html;
use function get_bloginfo;
use function get_post_field;
use function get_post_meta;
use function get_the_ID;
use function gmdate;
use function home_url;
use function is_callable;
use function is_null;
use function is_string;
use function preg_match_all;
use function shortcode_exists;
use function str_replace;

add_filter( 'render_block', NS . 'render_template_tags', 8, 3 );
/**
 * Allow custom data to be rendered in blocks.
 *
 * Runs before the block is rendered, so that the custom field
 * string can be used in the shortcode block.
 *
 * @since 0.9.34
 *
 * @param string   $html   Block HTML.
 * @param array    $block  Block data.
 * @param WP_Block $object Block context.
 *
 * @return string
 */
function render_template_tags( string $html, array $block, WP_Block $object ): string {
	$html = str_replace(
		[ '&#123;', '&#125;', '%7B', '%7D' ],
		[ '{', '}', '{', '}' ],
		$html
	);

	preg_match_all( '#\{(.*?)}#', $html, $matches );

	if ( ! $matches ) {
		return $html;
	}

	$tags = apply_filters(
		'blockify_template_tags',
		[
			'year'         => gmdate( 'Y' ),
			'current_year' => gmdate( 'Y' ),
			'date'         => gmdate( 'm/d/Y' ),
			'home_url'     => esc_url( home_url() ),
			'site_title'   => get_bloginfo( 'name', 'display' ),
			'site_name'    => get_bloginfo( 'name', 'display' ),
		]
	);

	$tags['archive_title'] = static function (): string {
		add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

		$title = get_the_archive_title();

		remove_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

		return $title;
	};

	for ( $i = 0; $i < count( $matches ); $i++ ) {
		$with_tags = $matches[0][ $i ] ?? '';

		if ( ! $with_tags ) {
			continue;
		}

		$without_tags = str_replace( [ '{', '}' ], '', $with_tags );

		if ( ! $without_tags ) {
			continue;
		}

		if ( shortcode_exists( $without_tags ) ) {
			return $html;
		}

		$id         = $object->context['postId'] ?? get_the_ID();
		$post_field = null;
		$post_meta  = null;

		if ( ! is_null( $id ) ) {
			$post_field = esc_html( get_post_field( $without_tags, $id ) );

			if ( $post_field ) {
				$html = str_replace( $with_tags, $post_field, $html );
			}

			$post_meta = esc_html( get_post_meta( $id, $without_tags, true ) );

			if ( ! $post_field && $post_meta ) {
				$html = str_replace( $with_tags, $post_meta, $html );
			}
		}

		$custom = $tags[ $with_tags ] ?? $tags[ $without_tags ] ?? '';

		if ( is_callable( $custom ) ) {
			$custom = $custom();
		}

		if ( ! $post_field && ! $post_meta && $custom && is_string( $custom ) ) {
			$html = str_replace( $with_tags, esc_html( $custom ), $html );
		}
	}

	return $html;
}
