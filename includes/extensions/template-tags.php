<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block;
use WP_Block_Type_Registry;
use function add_filter;
use function apply_filters;
use function array_keys;
use function esc_html;
use function get_bloginfo;
use function get_post_field;
use function get_post_meta;
use function get_post_type;
use function get_post_type_object;
use function get_the_ID;
use function get_the_title;
use function gmdate;
use function home_url;
use function in_array;
use function is_archive;
use function is_callable;
use function is_home;
use function is_null;
use function preg_match_all;
use function shortcode_exists;
use function str_contains;
use function str_replace;
use function strtolower;

add_filter( 'render_block', NS . 'render_template_tags', 8, 3 );
/**
 * Allow custom data to be rendered in blocks.
 *
 * Runs before the block is rendered, so that the custom field
 * string can be used in shortcode block attributes.
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
	$block_name        = $block['blockName'] ?? '';
	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	if ( ! array_key_exists( $block_name, $registered_blocks ) ) {
		return $html;
	}

	$category = $registered_blocks[ $block_name ]->category ?? '';

	$other_blocks = [ 'core/button', 'core/navigation-link', 'core/image' ];

	if ( 'text' !== $category && ! in_array( $block_name, $other_blocks, true ) ) {
		return $html;
	}

	$html = str_replace(
		[ '&#123;', '&#125;', '%7B', '%7D' ],
		[ '{', '}', '{', '}' ],
		$html
	);

	if ( ! str_contains( $html, '{' ) || ! str_contains( $html, '}' ) ) {
		return $html;
	}

	preg_match_all( '#\{(.*?)}#', $html, $matches );

	$without_brackets = $matches[1] ?? [];

	if ( empty( $without_brackets ) ) {
		return $html;
	}

	$post_id      = $object->context['postId'] ?? get_the_ID();
	$replacements = [];

	foreach ( $without_brackets as $tag ) {
		$tag         = strtolower( $tag );
		$replacement = '';

		if ( shortcode_exists( $tag ) ) {
			continue;
		}

		if ( ! is_null( $post_id ) ) {
			$post_field = get_post_field( $tag, $post_id );

			if ( $post_field ) {
				$replacement = $post_field;
			} else {
				$post_meta = get_post_meta( $post_id, $tag, true );

				if ( $post_meta ) {
					$replacement = $post_meta;
				}
			}
		}

		if ( ! $replacement ) {
			$tags = get_template_tags( $post_id ?: null );

			if ( isset( $tags[ $tag ] ) ) {
				$replacement = is_callable( $tags[ $tag ] ) ? call_user_func( $tags[ $tag ] ) : $tags[ $tag ];
			}
		}

		if ( $replacement ) {
			$replacements[ '{' . $tag . '}' ] = esc_html( $replacement );
		}
	}

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $html );
}

/**
 * Get template tags.
 *
 * @since 0.9.34
 *
 * @param ?int $post_id Extra tags.
 *
 * @return array
 */
function get_template_tags( ?int $post_id ): array {
	static $tags = null;

	if ( is_null( $tags ) ) {
		$year      = gmdate( 'Y' );
		$site_name = get_bloginfo( 'name', 'display' );

		$tags = [
			'year'         => $year,
			'current_year' => $year, // Backwards compatibility.
			'date'         => gmdate( 'm/d/Y' ),
			'home_url'     => home_url(),
			'site_title'   => $site_name,
			'site_name'    => $site_name,
		];
	}

	/**
	 * Filter template tags.
	 *
	 * @since 0.9.34
	 *
	 * @param array $tags    Template tags.
	 * @param int   $post_id Post ID.
	 *
	 * @return array
	 */
	return apply_filters( 'blockify_template_tags', $tags, $post_id );
}

add_filter( 'blockify_template_tags', NS . 'add_post_template_tags', 10, 2 );
/**
 * Adds post tags not included in post fields.
 *
 * @since 1.3.0
 *
 * @param array $tags    Template tags.
 * @param ?int  $post_id Post ID.
 *
 * @return array
 */
function add_post_template_tags( array $tags, ?int $post_id ): array {
	if ( ! $post_id ) {
		return $tags;
	}

	$tags['post_id']   = $post_id;
	$tags['permalink'] = get_permalink( $post_id );

	$post_type        = get_post_type( $post_id );
	$post_type_object = get_post_type_object( $post_type );

	if ( $post_type_object ) {
		$tags['post_type_label'] = $post_type_object->label;
	}

	return $tags;
}

add_filter( 'blockify_template_tags', NS . 'add_archive_template_tags', 10, 2 );
/**
 * Adds archive tags.
 *
 * @since 1.3.0
 *
 * @param array $tags    Template tags.
 * @param ?int  $post_id Post ID.
 *
 * @return array
 */
function add_archive_template_tags( array $tags, ?int $post_id ): array {
	if ( is_archive() || is_home() ) {
		$tags['archive_title'] = static function (): string {
			add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

			$title = get_the_archive_title();

			remove_filter( 'get_the_archive_title_prefix', '__return_empty_string' );

			return $title;
		};
	}

	return $tags;
}

add_filter( 'get_the_archive_title', NS . 'get_the_archive_title_home', 10, 1 );
/**
 * Get the archive title for the home page.
 *
 * @since 1.3.0
 *
 * @param string $title Archive title.
 *
 * @return string
 */
function get_the_archive_title_home( string $title ): string {
	if ( is_home() ) {
		$title = get_the_title( get_option( 'page_for_posts', true ) );
	}

	return $title;
}
