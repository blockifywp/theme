<?php

declare( strict_types=1 );

namespace Blockify\Theme;

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
use function locate_block_template;
use function ob_get_clean;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
use function remove_theme_support;
use function str_contains;
use function str_replace;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;
use stdClass;
use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use WP_Post;

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
		$files          = glob( $dir . '/*' );
		$category_slug  = basename( $dir );
		$category_title = ucwords( str_replace( '-', ' ', $category_slug ) );

		foreach ( $files as $file ) {
			if ( in_array( $file, $patterns, true ) ) {
				continue;
			}

			$file_base = basename( $file );
			$file_type = explode( '.', $file_base )[1] ?? 'html';

			// TODO: Add json support.
			if ( ! in_array( $file_type, [ 'html', 'php' ], true ) ) {
				continue;
			}

			$pattern_base  = basename( $file, '.' . $file_type );
			$pattern_slug  = $category_slug . '-' . $pattern_base;
			$pattern_title = $category_title . ' ' . ucwords( str_replace( '-', ' ', $pattern_base ) );

			$pattern = [
				'title'      => $pattern_title,
				'content'    => file_get_contents( $file ),
				'categories' => [ $category_slug ],
			];

			if ( $file_type === 'php' ) {
				ob_start();
				include $file;

				$pattern['content']  = ob_get_clean();
				$pattern['inserter'] = false;
			}

			if ( $category_slug === 'page' ) {
				$pattern['blockTypes'] = [ 'core/post-content' ];
				$pattern['postTypes']  = [ 'page' ];
			}

			if ( in_array( $category_slug, [ 'header', 'footer' ], true ) ) {
				$pattern['blockTypes'] = [ 'core/template-part/' . $category_slug ];
			}

			if ( $category_slug === 'template' ) {
				$pattern['inserter'] = false;
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

add_filter( 'template_include', NS . 'single_block_pattern_template' );
/**
 * Filter pattern template.
 *
 * @since 0.4.0
 *
 * @param string $template Template slug.
 *
 * @return string
 */
function single_block_pattern_template( string $template ): string {
	global $wp;

	$request = explode( DS, ( $wp->request ?? '' ) );

	if ( ! isset( $request[0] ) || $request[0] !== 'pattern' ) {
		return $template;
	}

	return locate_block_template( get_template_directory() . '/templates/blank.html', 'blank', [] );
}

add_filter( 'the_posts', NS . 'block_pattern_preview', -10 );
/**
 * Generates dynamic block pattern previews without registering a CPT.
 *
 * @param array $posts Original posts object.
 *
 * @return array
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
	$category = explode( '-', $request[1] ?? '' )[0] ?? '';
	$name     = str_replace( $category . '-', '', $request[1] ?? '' );

	if ( ! $category || ! $name ) {
		return $posts;
	}

	$paths = [
		get_template_directory() . "/patterns/{$category}/{$name}.html",
		get_template_directory() . "/patterns/{$category}/{$name}.php",
		get_stylesheet_directory() . "/patterns/{$category}/{$name}.html",
		get_stylesheet_directory() . "/patterns/{$category}/{$name}.php",
	];

	$pattern = '';

	foreach ( $paths as $path ) {
		if ( file_exists( $path ) ) {
			$pattern = file_get_contents( $path );

			if ( str_contains( $path, '.php' ) ) {
				ob_start();
				include $path;

				$pattern = ob_get_clean();
			}
		}
	}

	if ( ! $pattern ) {
		return $posts;
	}

	$admin_bar = 'show_admin_bar';

	/* @var WP_Post $post Post object */
	$post                  = new stdClass();
	$post->post_author     = 1;
	$post->post_name       = $name;
	$post->guid            = home_url() . DS . $post_type . DS . $name;
	$post->post_title      = ucwords( str_replace( '-', ' ', $name ) );
	$post->post_content    = $pattern;
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

	add_filter( $admin_bar, fn() => false );

	return $posts;
}
