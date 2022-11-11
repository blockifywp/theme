<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function get_template_directory_uri;
use function get_the_block_template_html;
use function get_the_content;
use function sprintf;
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
 * @since 1.0.0
 *
 * @return string
 */
function get_page_content(): string {
	$template = get_the_block_template_html() ?? '';
	$content  = get_the_content() ?? '';

	return do_blocks( $template . $content );
}
