<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function wp_get_global_settings;

add_filter( 'body_class', NS . 'add_default_mode_body_class' );
/**
 * Adds default mode body class.
 *
 * @param array $classes Array of body classes.
 *
 * @since 1.2.4
 *
 * @return array
 */
function add_default_mode_body_class( array $classes ): array {
	$global_settings = wp_get_global_settings();
	$dark_mode       = $global_settings['custom']['darkMode'] ?? [];
	$light_mode      = $global_settings['custom']['lightMode'] ?? [];
	$classes[]       = $light_mode && ! $dark_mode ? 'default-mode-dark' : 'default-mode-light';

	return $classes;
}
