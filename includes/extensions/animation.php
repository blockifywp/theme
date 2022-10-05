<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function apply_filters;
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

	$js = <<<JS
document.addEventListener( 'DOMContentLoaded', () => {
	const observer = new IntersectionObserver( containers => {
		containers.forEach( container => {
			if ( container.isIntersecting ) {
				const blocks = container.target.children;
				let delay    = 0;

				[ ...blocks ].forEach( block => {
					delay = delay + 100;
					block.style.opacity = 1;
					block.style.transition = 'opacity 1s';
					block.style.transitionDelay = delay + 'ms';
				} );
            }
		} );
	} );

	const containers = document.querySelectorAll( '.wp-block-columns, main > .wp-block-group' );

	[...containers].forEach( containers => {
		observer.observe( containers );

		const blocks = containers.children;

		[ ...blocks ].forEach( block => {
			block.style.opacity = 0;
		} );
	} );
} );
JS;

	wp_add_inline_script( 'wp-block-navigation-view', $js );
}
