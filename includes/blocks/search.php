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
	$dom     = dom( $content );

	/**
	 * @var $form DOMElement
	 */
	$form = $dom->firstChild;
	$divs = $form->getElementsByTagName( 'div' );

	if ( $divs->item( 0 ) ) {

		/**
		 * @var $div DOMElement;
		 */
		$div     = $divs->item( 0 );
		$buttons = $div->getElementsByTagName( 'button' );

		if ( $buttons->item( 0 ) ) {

			/**
			 * @var $button DOMElement
			 */
			$button = $buttons->item( 0 );

			$button->setAttribute( 'class', implode( ' ', [
				$button->getAttribute( 'class' ),
				'wp-block-button__link',
			] ) );
		}

		$inputs = $div->getElementsByTagName( 'input' );

		if ( ( $block['attrs']['style']['spacing']['padding'] ?? false ) && $inputs->item( 0 ) ) {

			/**
			 * @var $input DOMElement;
			 */
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

	if ( isset( $block['attrs']['className'] ) && \str_contains( $block['attrs']['className'], 'is-style-toggle' ) ) {
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

		$content = $dom->saveHTML();
	}

	return $content;
}

