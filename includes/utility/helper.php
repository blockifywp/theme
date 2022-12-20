<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function basename;
use function defined;
use function dirname;
use function do_blocks;
use function function_exists;
use function get_template_directory_uri;
use function get_the_block_template_html;
use function get_the_content;
use function is_admin;
use function is_woocommerce;
use function plugin_dir_url;
use function str_contains;
use function trailingslashit;

/**
 * Checks if installed as plugin or composer package.
 *
 * @since 0.4.0
 *
 * @return bool
 */
function is_plugin(): bool {
	return defined( 'Blockify\\Plugin\\SLUG' );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function is_plugin_only(): bool {
	return ! str_contains( DIR, 'wp-content/themes' );
}

/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param string $path Optional path.
 *
 * @return string
 */
function get_uri( string $path = '' ): string {
	return trailingslashit( is_plugin_only() ? plugin_dir_url( FILE ) : get_template_directory_uri() . DS ) . $path;
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
	return is_plugin() ? "../../plugins/blockify/vendor/blockify/theme/" : '';
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
function get_asset_path(): string {
	return is_plugin() ? DIR . 'vendor/blockify/theme/' : DIR;
}

/**
 * Get entire rendered page html.
 *
 * @since 0.9.10
 *
 * @param bool $do_blocks Whether to run do_blocks on the template html.
 *
 * @return string
 */
function get_page_content( bool $do_blocks = true ): string {
	if ( is_admin() ) {
		return '';
	}

	// Todo: Fix RCP conflict.
	if ( function_exists( 'rcp_should_show_discounts' ) ) {
		return '';
	}

	$content = get_the_content();

	if ( ! $do_blocks ) {
		return $content;
	}

	// Todo: Find better check.
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return do_blocks( $content );
	}

	$template = get_the_block_template_html();

	return do_blocks( $template . $content );
}
