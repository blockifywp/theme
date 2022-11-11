<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function get_template_directory_uri;
use function get_the_block_template_html;
use function get_the_content;
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
	return do_blocks( get_the_block_template_html() . get_the_content() );
}
