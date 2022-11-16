<?php

declare( strict_types=1 );

namespace Blockify\Theme;

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
	$form    = get_dom_element( 'form', $dom );

	if ( ! $form ) {
		return $content;
	}

	$div = get_dom_element( 'div', $form );

	if ( ! $div ) {
		return $content;
	}

	$input = get_dom_element( 'input', $div );

	if ( ( $block['attrs']['style']['spacing']['padding'] ?? false ) && $input ) {
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

	$content    = $dom->saveHTML();
	$class_name = $block['attrs']['className'] ?? '';

	if ( $class_name && str_contains( $class_name, 'is-style-toggle' ) ) {
		$dom         = dom( $content );
		$form        = get_dom_element( 'form', $dom );
		$label       = get_dom_element( 'label', $form );
		$wrap        = get_dom_element( 'div', $form );
		$input       = get_dom_element( 'input', $wrap );
		$button      = get_dom_element( 'button', $wrap );
		$button      = change_tag_name( $button, 'label' );
		$checkbox    = $dom->createElement( 'input' );
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
			\trim(
				\str_replace(
					[ 'wp-block-search__button', 'has-icon', 'wp-element-button' ],
					'',
					$button->getAttribute( 'class' ) . ' ' . $wrap->getAttribute( 'class' )
				)
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
		$form->insertBefore( $button, $wrap );
		$button->insertBefore( $checkbox, $button->firstChild );

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

