<?php

declare( strict_types=1 );

namespace Blockify;

use DOMElement;
use function add_filter;
use function str_replace;

add_filter( 'render_block', NS . 'render_search_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $block
 *
 * @return string
 */
function render_search_block( string $content, array $block ): string {
	if ( 'core/search' !== $block['blockName'] ) {
		return $content;
	}

	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

	if ( $padding ) {
		$dom = dom( $content );

		/**
		 * @var $form DOMElement
		 */
		$form = $dom->firstChild;

		$divs = $form->getElementsByTagName( 'div' );


		if ( $divs->item( 0 ) ) {

			/**
			 * @var $div DOMElement;
			 */
			$div = $divs->item( 0 );

			$inputs = $div->getElementsByTagName( 'input' );

			if ( $inputs->item( 0 ) ) {

				/**
				 * @var $input DOMElement;
				 */
				$input = $inputs->item( 0 );

				$input->setAttribute( 'style', implode( ';', [
					'padding-top:' . $padding['top'],
					'padding-right:' . $padding['right'],
					'padding-bottom:' . $padding['bottom'],
					'padding-left:' . $padding['left'],
				] ) );
			}
		}

		$content = $dom->saveHTML();
	}

	return str_replace(
		'wp-block-search__button ',
		'wp-block-search__button wp-block-button__link ',
		$content
	);
}

