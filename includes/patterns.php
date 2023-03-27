<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use function _cleanup_header_comment;
use function add_action;
use function explode;
use function file_exists;
use function get_file_data;
use function glob;
use function in_array;
use function is_child_theme;
use function is_readable;
use function ob_get_clean;
use function ob_start;
use function preg_match;
use function preg_quote;
use function register_block_pattern;
use function register_block_pattern_category;
use function remove_theme_support;
use function sort;
use function str_contains;
use function str_replace;
use function ucfirst;
use function ucwords;
use function wp_list_pluck;

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

		sort( $block_pattern['categories'] );

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

add_action( 'init', NS . 'register_default_patterns' );
/**
 * Manually registers default patterns to avoid loading in child themes.
 *
 * @since 1.0.1
 *
 * @return void
 */
function register_default_patterns(): void {
	if ( is_child_theme() || is_framework() ) {
		return;
	}

	$files = glob( get_dir() . 'patterns/default/*.php' );

	foreach ( $files as $file ) {
		register_block_pattern_from_file( $file );
	}
}

/**
 * Parses and registers block pattern from PHP file with header comment.
 *
 * @since 0.0.8
 *
 * @param string $file Path to PHP file.
 *
 * @return void
 */
function register_block_pattern_from_file( string $file ): void {
	if ( ! is_readable( $file ) ) {
		return;
	}

	$content = $file;

	if ( file_exists( $file ) ) {
		$headers = get_file_data(
			$file,
			[
				'categories'  => 'Categories',
				'title'       => 'Title',
				'slug'        => 'Slug',
				'block_types' => 'Block Types',
			]
		);

		ob_start();
		include $file;
		$content = ob_get_clean();

	} elseif ( str_contains( $file, 'Title: ' ) ) {
		$headers = [
			'title'       => 'Title',
			'slug'        => 'Slug',
			'categories'  => 'Categories',
			'block_types' => 'Block Types',
		];

		// Use regex from get_file_data().
		foreach ( $headers as $field => $regex ) {
			if ( preg_match( '/^(?:[ \t]*<\?php)?[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file, $match ) && $match[1] ) {
				$headers[ $field ] = _cleanup_header_comment( $match[1] );
			} else {
				$headers[ $field ] = '';
			}
		}
	}

	if ( ! isset( $headers['title'], $headers['slug'], $headers['categories'] ) ) {
		return;
	}

	$categories = explode( ',', $headers['categories'] );

	$pattern = [
		'title'         => $headers['title'],
		'content'       => str_replace(
			str_between( '<?php', '?>', $content ),
			'',
			$content
		),
		'categories'    => [ ...$categories ],
		'description'   => $headers['description'] ?? '',
		'viewportWidth' => $headers['viewport_width'] ?? null,
		'blockTypes'    => $headers['block_types'] ?? [],
	];

	foreach ( $categories as $category ) {
		register_block_pattern_category(
			$category,
			[
				'label' => ucwords( $category ),
			]
		);
	}

	// @phpstan-ignore-next-line.
	register_block_pattern( $headers['slug'], $pattern );
}
