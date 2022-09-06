<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use WP_Block_Pattern_Categories_Registry;
use WP_Block_Patterns_Registry;
use function add_action;
use function in_array;
use function register_block_pattern_category;
use function ucfirst;
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
