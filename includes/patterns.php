<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Post;
use function add_action;
use function add_filter;
use function apply_filters;
use function basename;
use function current_time;
use function explode;
use function file_exists;
use function file_get_contents;
use function get_stylesheet_directory;
use function get_template_directory;
use function glob;
use function in_array;
use function is_null;
use function is_post_type_archive;
use function locate_block_template;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
use function remove_theme_support;
use function str_replace;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;
use stdClass;
use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;

add_action( 'init', NS . 'remove_core_patterns', 9 );
/**
 * Removes core block patterns.
 *
 * @since 0.0.2
 *
 * @return void
 */
function remove_core_patterns(): void {
	remove_theme_support( 'core-block-patterns' );
}

add_action( 'init', NS . 'register_block_patterns' );
/**
 * Registers default block patterns.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_block_patterns(): void {
	$patterns = [];
	$dirs     = [
		...glob( get_template_directory() . '/patterns/*', GLOB_ONLYDIR ),
		...glob( get_stylesheet_directory() . '/patterns/*', GLOB_ONLYDIR ),
	];

	foreach ( $dirs as $dir ) {
		$files          = glob( $dir . '/*.php' );
		$category_slug  = basename( $dir );
		$category_title = ucwords( str_replace( '-', ' ', $category_slug ) );

		foreach ( $files as $file ) {
			if ( in_array( $file, $patterns, true ) ) {
				continue;
			}

			$pattern_base  = basename( $file, '.php' );
			$pattern_slug  = $category_slug . '-' . $pattern_base;
			$pattern_title = $category_title . ' ' . ucwords( str_replace( '-', ' ', $pattern_base ) );

			$pattern = [
				'title'      => $pattern_title,
				'content'    => file_get_contents( $file ),
				'categories' => [ $category_slug ],
			];

			if ( $category_slug === 'page' ) {
				$pattern['blockTypes'] = [ 'core/post-content' ];
				$pattern['postTypes']  = [ 'page' ];
			}

			if ( in_array( $category_slug, [ 'header', 'footer' ] ) ) {
				$pattern['blockTypes'] = [ 'core/template-part/' . $category_slug ];
			}

			if ( $category_slug === 'template' ) {
				$pattern['inserter'] = [ 'false' ];
			}

			$patterns[ $pattern_slug ] = $pattern;
		}
	}

	$patterns = apply_filters( 'blockify_patterns', $patterns );

	foreach ( $patterns as $pattern => $args ) {
		register_block_pattern(
			$pattern,
			$args
		);
	}
}

add_action( 'init', NS . 'auto_register_pattern_categories', 11 );
/**
 * Generates any missing categories for registered patterns.
 *
 * @since 0.0.2
 *
 * @return void
 */
function auto_register_pattern_categories(): void {
	$block_patterns = WP_Block_Patterns_Registry::get_instance()->get_all_registered();

	foreach ( $block_patterns as $block_pattern ) {
		if ( ! isset( $block_pattern['categories'] ) ) {
			continue;
		}

		foreach ( $block_pattern['categories'] as $category ) {
			$categories = wp_list_pluck( WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(), 'name' );

			if ( in_array( $category, $categories, true ) ) {
				continue;
			}

			register_block_pattern_category(
				$category,
				[
					'label' => ucfirst( $category ),
				]
			);
		}
	}
}

/**
 * Front end.
 */

add_filter( 'template_include', NS . 'archive_block_pattern_template' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $template
 *
 * @return string
 */
function archive_block_pattern_template( string $template ): string {
	if ( is_post_type_archive( 'block_pattern' ) ) {
		$template = locate_block_template( get_template_directory() . '/templates/archive.html', 'patterns', [] );
	}

	return $template;
}

add_filter( 'template_include', NS . 'single_block_pattern_template' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $template
 *
 * @return string
 */
function single_block_pattern_template( string $template ): string {
	global $wp;

	$request   = explode( DS, ( $wp->request ?? '' ) );

	if ( ! isset( $request[0] ) || $request[0] !== 'pattern' ) {
		return $template;
	}

	return locate_block_template( get_template_directory() . '/templates/blank.html', 'blank', [] );
}

add_filter( 'the_posts', NS . 'block_pattern_preview', -99 );
/**
 * Generates dynamic block pattern previews without registering a CPT.
 *
 * @param array    $posts    Original posts object
 *
 * @return  array  $posts  Modified posts object
 * @global  object $wp_query The main WordPress query object
 * @global  object $wp       The main WordPress object
 */
function block_pattern_preview( array $posts ): array {
	global $wp, $wp_query;

	$post_type = 'pattern';
	$request   = explode( DS, ( $wp->request ?? '' ) );

	if ( ! isset( $request[0] ) || $request[0] !== $post_type ) {
		return $posts;
	}

	// Run once.
	static $cache = null;

	if ( ! is_null( $cache ) ) {
		return $posts;
	}

	$cache    = true;
	$category = $request[1] ?? '';
	$name     = $request[2] ?? '';

	if ( ! $category || ! $name ) {
		return $posts;
	}

	$parent = get_template_directory() . "/patterns/{$category}/{$name}.php";
	$child  = get_stylesheet_directory() . "/patterns/{$category}/{$name}.php";

	if ( ! file_exists( $child ) && ! file_exists( $parent ) ) {
		return $posts;
	}

	$file    = file_exists( $child ) ? $child : $parent;
	$pattern = file_get_contents( $file );

	ob_start();
	echo $pattern;
	$content = ob_get_clean();

	/* @var WP_Post $post */
	$post                  = new stdClass;
	$post->post_author     = 1;
	$post->post_name       = $name;
	$post->guid            = home_url() . DS . $post_type . DS . $name;
	$post->post_title      = ucwords( str_replace( '-', ' ', $name ) );
	$post->post_content    = $content;
	$post->ID              = -999;
	$post->post_type       = 'page';
	$post->post_status     = 'static';
	$post->comment_status  = 'closed';
	$post->ping_status     = 'open';
	$post->comment_count   = 0;
	$post->post_date       = current_time( 'mysql' );
	$post->post_date_gmt   = current_time( 'mysql', 1 );
	$post->page_template   = 'blank';
	$posts                 = null;
	$posts[]               = $post;
	$wp_query->is_page     = true;
	$wp_query->is_singular = true;
	$wp_query->is_home     = false;
	$wp_query->is_archive  = false;
	$wp_query->is_category = false;
	$wp_query->is_404      = false;

	add_filter( 'show_admin_bar', fn() => false );

	return $posts;
}
