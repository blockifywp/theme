<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function __;
use function add_filter;
use function html_entity_decode;
use function trim;
use function wp_strip_all_tags;

add_filter( 'render_block_core/code', NS . 'render_code_block', 10, 2 );
/**
 * Renders the code block.
 *
 * @param string $html  The block content.
 * @param array  $block The block.
 *
 * @return string
 */
function render_code_block( string $html, array $block ): string {
	$content       = trim( html_entity_decode( wp_strip_all_tags( $block['innerHTML'] ) ) );
	$label         = __( 'Copy', 'blockify' );
	$copied        = __( 'Copied', 'blockify' );
	$svg           = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="20" width="20" viewBox="0 0 24 24"><path d="M20.2 8v11c0 .7-.6 1.2-1.2 1.2H6v1.5h13c1.5 0 2.7-1.2 2.7-2.8V8zM18 16.4V4.6c0-.9-.7-1.6-1.6-1.6H4.6C3.7 3 3 3.7 3 4.6v11.8c0 .9.7 1.6 1.6 1.6h11.8c.9 0 1.6-.7 1.6-1.6zm-13.5 0V4.6c0-.1.1-.1.1-.1h11.8c.1 0 .1.1.1.1v11.8c0 .1-.1.1-.1.1H4.6l-.1-.1z"/></svg>';
	$click_to_copy = "<div class='click-to-copy'><span >$copied</span><button title='$label'>$svg</button><textarea>$content</textarea></div>";
	$dom           = dom( $html );
	$pre           = get_dom_element( 'pre', $dom );
	$code          = get_dom_element( 'code', $dom );
	$element       = $pre ?? $code ?? null;

	if ( ! $element ) {
		return $html;
	}

	$copy_dom = dom( $click_to_copy );
	$div      = get_dom_element( 'div', $copy_dom );

	$imported = $element->ownerDocument->importNode( $div, true );

	$element->insertBefore( $imported, $element->firstChild );

	$html = $dom->saveHTML();

	return $html;
}

add_filter( 'blockify_inline_js', NS . 'add_click_to_copy', 10, 2 );
/**
 * Add click to copy JS.
 *
 * @since 0.9.34
 *
 * @param string $js   Inline JS.
 * @param string $html Page HTML content.
 *
 * @return string
 */
function add_click_to_copy( string $js, string $html ): string {
	if ( str_contains( $html, 'click-to-copy' ) ) {
		$js .= file_get_contents( DIR . 'assets/js/clickToCopy.js' );
	}

	return $js;
}
