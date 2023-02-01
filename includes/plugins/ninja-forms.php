<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function wp_dequeue_style;

add_action( 'nf_display_enqueue_scripts', NS . 'dequeue_ninja_forms_css' );
/**
 * Dequeue Ninja Forms CSS.
 *
 * @since 0.9.35
 *
 * @return void
 */
function dequeue_ninja_forms_css() {
	wp_dequeue_style( 'nf-display' );
}
