<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function dirname;
use function get_template;
use function get_template_directory_uri;
use function is_child_theme;
use function is_null;
use function plugin_dir_url;
use function str_contains;

/**
 * Checks if Blockify is installed as framework.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function is_framework(): bool {
	return get_template() !== 'blockify';
}

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
 * Returns path to theme directory.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_dir(): string {
	static $dir = null;

	if ( is_null( $dir ) ) {
		$vendor = is_framework() ? '/vendor/blockify/theme/' : DS;
		$dir    = get_template_directory() . $vendor;

		if ( is_plugin() ) {
			$dir = dirname( __DIR__, 2 ) . DS;
		}
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
	static $uri = null;

	if ( is_null( $uri ) ) {
		$vendor = is_framework() ? '/vendor/blockify/theme/' : DS;
		$uri    = get_template_directory_uri() . $vendor;

		if ( is_plugin() ) {
			$uri = plugin_dir_url( dirname( __DIR__ ) );
		}
	}

	return $uri;
}

/**
 * Returns path to asset parent directory relative to theme root.
 *
 * Used for editor stylesheets and font src files.
 *
 * @since 1.0.0
 *
 * @return string
 */
function get_editor_stylesheet_path(): string {
	$path = '';

	if ( is_framework() ) {
		$path = 'vendor/blockify/theme/';
	}

	if ( is_child_theme() ) {
		$path = '../blockify/';
	}

	if ( is_plugin() ) {
		$path = '../../plugins/blockify/vendor/blockify/theme/';
	}

	return $path;
}
