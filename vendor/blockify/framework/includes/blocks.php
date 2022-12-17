<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_action;
use function register_block_type;

add_action( 'init', NS . 'register_dark_mode_toggle_block' );
/**
 * Registers the dark mode toggle block.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_dark_mode_toggle_block() {

	// Only register blocks if installed as plugin.
	if ( is_plugin() ) {
		register_block_type( DIR . 'assets/js/blocks/dark-mode-toggle' );
	}
}

add_filter( 'block_categories_all', NS . 'register_block_categories' );
/**
 * Registers block categories.
 *
 * @since 0.0.2
 *
 * @param array $categories
 *
 * @return array
 */
function register_block_categories( array $categories ): array {
	return array_merge(
		$categories,
		[
			[
				'slug'  => SLUG,
				'title' => NAME,
			],
			[
				'slug'  => 'blockify-form',
				'title' => __( 'Form', 'blockify' ),
			],
		]
	);
}
