<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function do_blocks;
use function function_exists;
use function get_the_block_template_html;
use function get_the_content;
use function is_admin;

/**
 * Get entire rendered page html.
 *
 * @since 0.9.10
 *
 * @return string
 */
function get_page_content(): string {
	if ( is_admin() ) {
		return '';
	}

	// Todo: Fix RCP conflict.
	if ( function_exists( 'rcp_should_show_discounts' ) ) {
		return '';
	}

	$content = get_the_content();

	// Todo: Find better check.
	if ( function_exists( 'is_woocommerce' ) && \is_woocommerce() ) {
		return do_blocks( $content );
	}

	$template = get_the_block_template_html();

	return do_blocks( $template . $content );
}
