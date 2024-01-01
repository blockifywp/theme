<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function dirname;
use function get_template;
use function get_template_directory_uri;
use function is_array;
use function plugin_dir_url;
use function str_contains;
use function str_replace;
use function str_starts_with;
use function strtolower;
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

/**
 * Given an array of settings, extracts the CSS Custom Properties
 * for the custom values and adds them to the $declarations
 * array following the format:
 *
 *     array(
 *       'property_name' => 'property_value,
 *     )
 *
 * This is slightly different from the implementation in
 * wp-includes/class-wp-theme-json.php which is:
 *
 *     array(
 *       'name'  => 'property_name',
 *       'value' => 'property_value,
 *     )
 *
 * @since 5.8.0
 *
 * @param array $custom_values Settings to process.
 *
 * @see   WP_Theme_JSON::compute_theme_vars()
 *
 * @return array The modified $declarations.
 */
function compute_theme_vars( array $custom_values ): array {
	$declarations = [];
	$css_vars     = flatten_tree( $custom_values );

	foreach ( $css_vars as $key => $value ) {
		$declarations[ '--wp--custom--' . $key ] = $value;
	}

	return $declarations;
}

/**
 * Given a tree, it creates a flattened one
 * by merging the keys and binding the leaf values
 * to the new keys.
 *
 * It also transforms camelCase names into kebab-case
 * and substitutes '/' by '-'.
 *
 * This is thought to be useful to generate
 * CSS Custom Properties from a tree,
 * although there's nothing in the implementation
 * of this function that requires that format.
 *
 * For example, assuming the given prefix is '--wp'
 * and the token is '--', for this input tree:
 *
 *     {
 *       'some/property': 'value',
 *       'nestedProperty': {
 *         'sub-property': 'value'
 *       }
 *     }
 *
 * it'll return this output:
 *
 *     {
 *       '--wp--some-property': 'value',
 *       '--wp--nested-property--sub-property': 'value'
 *     }
 *
 * @since 5.8.0
 *
 * @param array  $tree   Input tree to process.
 * @param string $prefix Optional. Prefix to prepend to each variable. Default
 *                       empty string.
 * @param string $token  Optional. Token to use between levels. Default '--'.
 *
 * @see   WP_Theme_JSON::flatten_tree()
 *
 * @return array The flattened tree.
 */
function flatten_tree( array $tree, string $prefix = '', string $token = '--' ): array {
	$result = [];

	foreach ( $tree as $property => $value ) {
		$new_key = $prefix . str_replace(
				'/',
				'-',
				strtolower( _wp_to_kebab_case( $property ) )
			);

		if ( is_array( $value ) ) {
			$new_prefix        = $new_key . $token;
			$flattened_subtree = flatten_tree( $value, $new_prefix, $token );

			foreach ( $flattened_subtree as $subtree_key => $subtree_value ) {
				$result[ $subtree_key ] = $subtree_value;
			}

		} else {
			$result[ $new_key ] = $value;
		}
	}

	return $result;
}
