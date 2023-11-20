<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function dirname;
use function get_template;
use function get_template_directory_uri;
use function plugin_dir_url;
use function str_contains;
use function str_starts_with;
use function trailingslashit;

/**
 * Checks if Blockify is installed as plugin.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function is_plugin(): bool {
	return str_contains( __DIR__, 'plugins/blockify' );
}

/**
 * Checks if Blockify is installed as framework.
 *
 * Returns false if installed as a theme or plugin.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function is_framework(): bool {
	return ! str_starts_with( get_template(), 'blockify' ) && ! is_plugin();
}

/**
 * Returns path to theme directory.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_dir(): string {
	static $dir = '';

	if ( ! $dir ) {
		$vendor = is_framework() ? '/vendor/blockify/theme/' : '';
		$dir    = get_template_directory() . $vendor;

		if ( is_plugin() ) {
			$dir = dirname( __DIR__, 2 ) . DS;
		}

		$dir = trailingslashit( $dir );
	}

	return $dir;
}

/**
 * Returns URI to theme directory.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_uri(): string {
	static $uri = '';

	if ( ! $uri ) {
		$vendor = is_framework() ? '/vendor/blockify/theme/' : DS;
		$uri    = get_template_directory_uri() . $vendor;

		if ( is_plugin() ) {
			$uri = plugin_dir_url( dirname( __DIR__ ) );
		}

		$uri = trailingslashit( $uri );
	}

	return $uri;
}
