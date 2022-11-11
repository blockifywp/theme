<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function glob;
use function in_array;
use function register_block_pattern_category;
use function remove_theme_support;
use function sanitize_title_with_dashes;
use function sort;
use function trim;
use function ucfirst;
use function wp_list_pluck;
use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_pattern_dir(): string {
	return apply_filters(
		'blockify_pattern_dir',
		DIR . 'patterns/default'
	);
}

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

add_action( false, NS . 'register_block_patterns' );
/**
 * Registers default block patterns.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_block_patterns(): void {
	$dir = get_pattern_dir();

	foreach ( glob( $dir . '/*.php' ) as $file ) {
		register_block_pattern_from_file( $file );
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
	$headers = get_file_data(
		$file,
		[
			'title'         => 'Title',
			'slug'          => 'Slug',
			'categories'    => 'Categories',
			'block_types'   => 'Block Types',
			'post_types'    => 'Post Types',
			'inserter'      => 'Inserter',
			'keywords'      => 'Keywords',
			'viewportWidth' => 'Viewport Width',
		]
	);

	$categories = explode( ',', $headers['categories'] );

	[ $category ] = $categories;

	$category = trim( sanitize_title_with_dashes( $category ) );

	ob_start();
	include $file;
	$content = ob_get_clean();
	$content = str_replace(
		str_between( '<?php', '?>', $content ),
		'',
		$content
	);

	$pattern = [
		'title'      => $headers['title'],
		'content'    => $content,
		'categories' => $categories,
	];

	if ( $headers['block_types'] ) {
		$pattern['blockTypes'] = $headers['block_types'];
	}

	if ( $headers['post_types'] ) {
		$pattern['postTypes'] = $headers['post_types'];
	}

	if ( $headers['inserter'] ) {
		$pattern['inserter'] = $headers['inserter'];
	}

	if ( $category === 'template' ) {
		$pattern['inserter'] = false;
	}

	foreach ( $categories as $category ) {
		register_block_pattern_category(
			$category,
			[
				'label' => ucwords( $category ),
			]
		);
	}

	// @phpstan-ignore-next-line
	register_block_pattern( $headers['slug'], $pattern );
}
