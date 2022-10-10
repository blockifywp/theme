<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
use function file_get_contents;
use function filemtime;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_get_global_settings;
use function wp_register_script;

add_action( 'wp_enqueue_scripts', NS . 'animate_blocks' );
/**
 * Animations.
 *
 * @since 1.0.0
 *
 * @return void
 */
function animate_blocks() {
	$global_settings = wp_get_global_settings();
	$has_animation   = apply_filters( 'blockify_animate_blocks', $global_settings['custom']['animation'] ?? null );

	if ( ! $has_animation ) {
		return;
	}

	// Less than 500 bytes.
	$file = DIR . 'assets/js/public/animation.js';

	wp_register_script( SLUG, '', [], filemtime( $file ), true );
	wp_enqueue_script( SLUG );
	wp_add_inline_script( SLUG, file_get_contents( $file ) );
}
