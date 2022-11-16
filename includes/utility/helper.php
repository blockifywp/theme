<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function do_blocks;
use function function_exists;
use function get_template_directory_uri;
use function get_the_block_template_html;
use function get_the_content;
use function is_shop;
use function is_woocommerce;
use function trailingslashit;

/**
 * Returns the URL for the theme or plugin.
 *
 * @since 0.0.13
 *
 * @return string
 */
function get_url(): string {
	return trailingslashit( get_template_directory_uri() );
}

/**
 * Get entire rendered page html.
 *
 * @since 0.9.10
 *
 * @return string
 */
function get_page_content(): string {
	$content = get_the_content();

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return do_blocks( $content );
	}

	$template = get_the_block_template_html();

	return do_blocks( $template . $content );
}
