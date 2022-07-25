<?php

declare( strict_types=1 );

namespace Blockify;

use stdClass;
use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use WP_Post;
use function add_action;
use function array_map;
use function basename;
use function current_time;
use function dirname;
use function get_page_by_path;
use function glob;
use function home_url;
use function in_array;
use function is_null;
use function ob_get_clean;
use function ob_start;
use function preg_match_all;
use function register_block_pattern_category;
use function str_contains;
use function str_replace;
use function strtolower;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;

add_action( 'init', NS . 'register_root_level_pattern_categories', 11 );
/**
 * Generates categories for patterns automatically registered by core.
 *
 * @since 0.0.2
 *
 * @return void
 */
function register_root_level_pattern_categories(): void {
	$block_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

	foreach ( $block_patterns as $block_pattern ) {
		if ( ! isset( $block_pattern['categories'] ) ) {
			continue;
		}

		foreach ( $block_pattern['categories'] as $category ) {
			$categories = wp_list_pluck( WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(), 'name' );

			if ( in_array( $category, $categories ) ) {
				continue;
			}

			register_block_pattern_category( $category, [
				'label' => ucfirst( $category ),
			] );
		}
	}
}

add_filter( 'the_posts', NS . 'generate_pattern_preview', -10 );
/**
 * Allows patterns to be previewed on front end without creating posts in the database.
 *
 * For demonstration purposes only.
 *
 * @since 0.0.9
 *
 * @param array $posts Original posts object.
 *
 * @return array
 */
function generate_pattern_preview( array $posts ): array {
	global $wp, $wp_query;
	static $static = null;

	if ( ! is_null( $static ) ) {
		return $posts;
	}

	$pages = array_map( fn( $file ) => str_replace(
		dirname( $file ) . DIRECTORY_SEPARATOR,
		'',
		basename( $file, '.php' )
	), glob( DIR . 'patterns/*.php' ) );

	foreach ( $pages as $page ) {
		if ( strtolower( $wp->request ) !== $page ) {
			continue;
		}

		if ( get_page_by_path( $page, OBJECT, [ 'post', 'page', 'block_pattern' ] ) ) {
			continue;
		}

		$static  = true;
		$is_page = str_contains( $page, 'page-' );

		preg_match_all( '!\d+!', $page, $matches );

		add_action( 'wp_head', fn() => print '<meta name="robots" content="noindex,nofollow">' );

		ob_start();

		if ( $is_page ) {
			include DIR . 'patterns/header-' . ( $matches[0][0] ?? 1 ) . '.php';
		}

		include DIR . 'patterns/' . $page . '.php';

		if ( $is_page ) {
			include DIR . 'patterns/footer-' . ( $matches[0][0] ?? 1 ) . '.php';
		}

		$content = ob_get_clean();

		/**
		 * @var $post WP_Post
		 */
		$post                          = new stdClass;
		$post->post_author             = 1;
		$post->post_name               = $page;
		$post->guid                    = home_url() . '/' . $page;
		$post->post_title              = ucwords( str_replace( '-', ' ', $page ) );
		$post->post_content            = $content;
		$post->ID                      = -999;
		$post->post_type               = 'block_pattern';
		$post->post_status             = 'private';
		$post->comment_status          = 'closed';
		$post->ping_status             = 'closed';
		$post->comment_count           = 0;
		$post->post_date               = current_time( 'mysql' );
		$post->post_date_gmt           = current_time( 'mysql', 1 );
		$posts                         = null;
		$posts[]                       = $post;
		$wp_query->is_page             = true;
		$wp_query->is_singular         = true;
		$wp_query->is_home             = false;
		$wp_query->is_archive          = false;
		$wp_query->is_category         = false;
		$wp_query->query_vars['error'] = '';
		$wp_query->is_404              = false;
		unset( $wp_query->query['error'] );
		$static = true;
	}

	return $posts;
}
