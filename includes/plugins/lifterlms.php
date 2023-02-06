<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_action;
use function add_filter;
use function add_theme_support;
use function class_exists;
use function register_sidebar;

add_action( 'after_setup_theme', NS . 'lifterlms_theme_support' );
/**
 * Adds theme support for LifterLMS course and lesson sidebars.
 *
 * @since 1.0.0
 *
 * @return void
 */
function lifterlms_theme_support(): void {
	if ( class_exists( 'LifterLMS' ) ) {
		add_theme_support( 'lifterlms-sidebars' );
		add_filter( 'llms_get_theme_default_sidebar', fn() => null );
	}
}
