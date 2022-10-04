<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function wp_add_inline_script;
use function wp_enqueue_script;
use function wp_register_script;

add_action( 'wp_enqueue_scripts', NS . 'add_animation_script' );
/**
 * Animations.
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_animation_script() {

	$js = <<<JS
document.addEventListener( 'DOMContentLoaded', () => {
	const observer = new IntersectionObserver( containers => {
		containers.forEach( container => {
			if ( container.isIntersecting ) {
				const blocks = container.target.children;
				let delay = 0;

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

	[...containers].forEach( container => {
		observer.observe( container );

		const blocks = container.children;

		[ ...blocks ].forEach( block => {
			block.style.opacity = 0;
		} );
	} );
} );
JS;
	
	wp_add_inline_script( 'wp-block-navigation-view', $js, 'before' );
}
