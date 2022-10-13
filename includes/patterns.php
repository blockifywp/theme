<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function basename;
use function explode;
use function file_get_contents;
use function get_stylesheet_directory;
use function get_template_directory;
use function glob;
use function in_array;
use function ob_get_clean;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
use function remove_theme_support;
use function str_replace;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;
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
	$patterns       = [];
	$stylesheet_dir = get_stylesheet_directory();
	$template_dir   = get_template_directory();
	$pattern_dir    = $stylesheet_dir === $template_dir ? $template_dir : $stylesheet_dir;
	$dirs           = glob( $pattern_dir . '/patterns/*', GLOB_ONLYDIR );

	foreach ( $dirs as $dir ) {
		$files          = glob( $dir . '/*.html' );
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

			$pattern_base    = basename( $file, '.' . $file_type );
			$pattern_slug    = $category_slug . '-' . $pattern_base;
			$pattern_title   = $category_title . ' ' . ucwords( str_replace( '-', ' ', $pattern_base ) );
			$pattern_content = file_get_contents( $file );

			$pattern = [
				'title'      => $pattern_title,
				'content'    => $pattern_content,
				'categories' => [ $category_slug ],
			];

			ob_start();
			include $file;
			$pattern['content'] = ob_get_clean();

			if ( $file_type === 'php' ) {
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

			$exclude = [
				'icon-guide',
			];

			if ( in_array( $pattern_base, $exclude, true ) ) {
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
