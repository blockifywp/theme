<?php

declare( strict_types=1 );

namespace Blockify;

use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use function add_action;
use function in_array;
use function ob_get_clean;
use function ob_start;
use function register_block_pattern;
use function register_block_pattern_category;
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

/**
 * Parses and registers block pattern from PHP file with header comment.
 *
 * @since 0.0.8
 *
 * @param string $file
 *
 * @return void
 */
function register_block_pattern_from_file( string $file ): void {
	$headers = get_file_data( $file, [
		'categories'  => 'Categories',
		'title'       => 'Title',
		'slug'        => 'Slug',
		'block_types' => 'Block Types',
	] );

	$categories = explode( ',', $headers['categories'] );

	ob_start();
	include $file;
	$content = ob_get_clean();

	$pattern = [
		'title'      => $headers['title'],
		'content'    => $content,
		'categories' => [ ...$categories ],
	];

	if ( $headers['block_types'] ) {
		$pattern['blockTypes'] = $headers['block_types'];
	}

	foreach ( $categories as $category ) {
		register_block_pattern_category( $category, [
			'label' => ucwords( $category ),
		] );
	}

	register_block_pattern( $headers['slug'], $pattern );
}
