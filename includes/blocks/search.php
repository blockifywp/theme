<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMElement;
use function add_filter;
use function apply_filters;
use function explode;
use function str_contains;

add_filter( 'render_block_core/search', NS . 'render_search_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $content Block HTML.
 * @param array  $block   Block data.
 *
 * @return string
 */
function render_search_block( string $content, array $block ): string {
	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];
	$dom     = dom( $content );

	/* @var \DOMElement $form Form element. */
	$form = $dom->getElementsByTagName( 'form' )->item( 0 );
	$divs = $form->getElementsByTagName( 'div' );

	if ( $divs->item( 0 ) ) {

		/* @var DOMElement $div Div element. */
		$div = $divs->item( 0 );

		$inputs = $div->getElementsByTagName( 'input' );

		if ( ( $block['attrs']['style']['spacing']['padding'] ?? false ) && $inputs->item( 0 ) ) {

			/* @var DOMElement $input Input element. */
			$input = $inputs->item( 0 );

			$input->setAttribute(
				'style',
				implode(
					';',
					[
						'padding-top:' . ( $padding['top'] ?? '' ),
						'padding-right:' . ( $padding['right'] ?? '' ),
						'padding-bottom:' . ( $padding['bottom'] ?? '' ),
						'padding-left:' . ( $padding['left'] ?? '' ),
					]
				)
			);
		}
	}

	$content = $dom->saveHTML();

	$class_name = $block['attrs']['className'] ?? '';

	if ( $class_name && str_contains( $class_name, 'is-style-toggle' ) ) {
		$dom = dom( $content );

		/* @var \DOMElement $form Form element. */
		$form = $dom->getElementsByTagName( 'form' )->item( 0 );

		/* @var \DOMElement $label Label element. */
		$label = $form->getElementsByTagName( 'label' )->item( 0 );

		/* @var \DOMElement $wrap Wrap element. */
		$wrap = $form->getElementsByTagName( 'div' )->item( 0 );

		/* @var \DOMElement $input Input element. */
		$input = $wrap->getElementsByTagName( 'input' )->item( 0 );

		/* @var \DOMElement $button Button element. */
		$button   = $wrap->getElementsByTagName( 'button' )->item( 0 );
		$checkbox = $dom->createElement( 'input' );
		$button   = change_tag_name( $button, 'label' );

		$placeholder = $input->getAttribute( 'placeholder' );

		if ( ! $placeholder ) {
			$input->setAttribute(
				'placeholder',
				apply_filters( 'blockify_search_placeholder', __( 'Search this website', 'blockify' ) )
			);
		}

		$checkbox->setAttribute( 'class', 'wp-block-search__checkbox screen-reader-text' );
		$checkbox->setAttribute( 'type', 'checkbox' );
		$checkbox->setAttribute( 'id', $label->getAttribute( 'for' ) . '-checkbox' );
		$button->setAttribute( 'for', $checkbox->getAttribute( 'id' ) );

		$wrap->setAttribute(
			'class',
			\str_replace(
				[ 'wp-block-search__button', 'has-icon' ],
				'',
				$button->getAttribute( 'class' ) . ' ' . $wrap->getAttribute( 'class' )
			)
		);

		$button_classes = explode( ' ', $button->getAttribute( 'class' ) );
		$button_class   = '';

		foreach ( $button_classes as $class ) {
			if ( ! str_contains( $class, '-background' ) ) {
				$button_class .= $class . ' ';
			}
		}

		$button->setAttribute( 'class', $button_class );

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

