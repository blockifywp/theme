<?php

declare( strict_types=1 );

namespace Blockify\Plugin;

use function add_filter;
use function get_option;

add_filter( 'body_class', NS . 'add_dark_mode_body_class' );
/**
 * Sets default body class.
 *
 * @since 0.9.10
 *
 * @param array $classes Body classes.
 *
 * @return array
 */
function add_dark_mode_body_class( array $classes ): array {
	$dark_mode = ( ( $_COOKIE['blockifyDarkMode'] ?? '' ) === 'true' );

	if ( $dark_mode ) {
		$classes[] = 'is-style-dark';
	}

	return $classes;
}

add_filter( 'blockify_inline_js', NS . 'add_dark_mode_inline_js' );
/**
 * Adds dark mode inline JS.
 *
 * @since 0.9.10
 *
 * @param string $js JS.
 *
 * @return string
 */
function add_dark_mode_inline_js( string $js ): string {
	$options = get_option( SLUG );
	$cookie  = $_COOKIE['blockifyDarkMode'] ?? '';

	if ( $cookie === 'false' ) {
		return $js;
	}

	if ( $options['autoDarkMode'] ?? null ) {
		$js = <<<JS
if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.body.classList.add('is-style-dark');
}
JS;
	}

	return $js;
}
