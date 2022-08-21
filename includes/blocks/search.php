<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function array_diff;
use function explode;
use function str_contains;
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
	$dom     = dom( $content );

	/**
	 * @var $form DOMElement
	 */
	$form = $dom->firstChild;
	$divs = $form->getElementsByTagName( 'div' );

	if ( $divs->item( 0 ) ) {

		/** @var DOMElement $div */
		$div     = $divs->item( 0 );
		$buttons = $div->getElementsByTagName( 'button' );

		if ( $buttons->item( 0 ) ) {

			/** @var DOMElement $button */
			$button = $buttons->item( 0 );

			$classes = explode( ' ', $button->getAttribute( 'class' ) );

			$button->setAttribute( 'class', implode( ' ', $classes ) );
		}

		$inputs = $div->getElementsByTagName( 'input' );

		if ( ( $block['attrs']['style']['spacing']['padding'] ?? false ) && $inputs->item( 0 ) ) {

			/** @var DOMElement $input */
			$input = $inputs->item( 0 );

			$input->setAttribute( 'style', implode( ';', [
				'padding-top:' . ( $padding['top'] ?? '' ),
				'padding-right:' . ( $padding['right'] ?? '' ),
				'padding-bottom:' . ( $padding['bottom'] ?? '' ),
				'padding-left:' . ( $padding['left'] ?? '' ),
			] ) );
		}
	}

	$content = $dom->saveHTML();

	$class_name = $block['attrs']['className'] ?? '';

	if ( $class_name && str_contains( $class_name, 'is-style-toggle' ) ) {
		$dom = dom( $content );

		/**
		 * @var $form   DOMElement
		 * @var $label  DOMElement
		 * @var $wrap   DOMElement
		 * @var $input  DOMElement
		 * @var $button DOMElement
		 */
		$form     = $dom->firstChild;
		$label    = $form->getElementsByTagName( 'label' )->item( 0 );
		$wrap     = $form->getElementsByTagName( 'div' )->item( 0 );
		$input    = $wrap->getElementsByTagName( 'input' )->item( 0 );
		$button   = $wrap->getElementsByTagName( 'button' )->item( 0 );
		$checkbox = $dom->createElement( 'input' );
		$button   = change_tag_name( $button, 'label' );

		$checkbox->setAttribute( 'class', 'wp-block-search__checkbox screen-reader-text' );
		$checkbox->setAttribute( 'type', 'checkbox' );
		$checkbox->setAttribute( 'id', $label->getAttribute( 'for' ) . '-checkbox' );
		$button->setAttribute( 'for', $checkbox->getAttribute( 'id' ) );

		$wrap->appendChild( $input );
		$form->removeChild( $label );
		$form->removeChild( $wrap );
		$form->appendChild( $wrap );
		$form->insertBefore( $checkbox, $wrap );
		$form->insertBefore( $button, $wrap );

		$close = $dom->createElement( 'svg' );
		$close->setAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
		$close->setAttribute( 'viewBox', '0 0 24 24' );
		$close->setAttribute( 'class', 'close-icon' );

		$close_path = $dom->createElement( 'path' );
		$close_path->setAttribute( 'd', 'm13 11.8 6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z' );
		$close_path->setAttribute( 'fill', 'currentColor' );

		$close->appendChild( $close_path );
		$button->appendChild( $close );

		$content = $dom->saveHTML();
	}

	return $content;
}

