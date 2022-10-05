<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function file_get_contents;
use function get_stylesheet_directory;
use function get_template_directory;
use function wp_add_inline_script;

add_action( 'wp_enqueue_scripts', NS . 'animate_blocks' );
/**
 * Animations.
 *
 * @since 1.0.0
 *
 * @return void
 */
function animate_blocks() {
	$has_animation = apply_filters( 'blockify_animate_blocks', get_template_directory() !== get_stylesheet_directory() );

	if ( ! $has_animation ) {
		return;
	}

	// No dependencies, 500 bytes.
	wp_add_inline_script( 'wp-block-navigation-view', file_get_contents( DIR . 'assets/js/public/animation.js' ) );
}
