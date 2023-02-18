<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function apply_filters;
use function explode;
use function get_post_type;
use function is_post_type_archive;
use function str_contains;
use function str_replace;
use function trim;

add_filter( 'render_block_core/search', NS . 'render_search_block', 10, 2 );
/**
 * Modifies front end HTML output of block.
 *
 * @since 0.0.2
 *
 * @param string $html  Block HTML.
 * @param array  $block Block data.
 *
 * @return string
 */
function render_search_block( string $html, array $block ): string {
	$dom  = dom( $html );
	$form = get_dom_element( 'form', $dom );

	if ( ! $form ) {
		return $html;
	}

	$div = get_dom_element( 'div', $form );

	if ( ! $div ) {
		return $html;
	}

	$padding = $block['attrs']['style']['spacing']['padding'] ?? [];
	$input   = get_dom_element( 'input', $div );

	if ( $padding && $input ) {
		$form->setAttribute(
			'style',
			implode(
				';',
				[
					'padding-top:' . format_custom_property( $padding['top'] ?? '' ),
					'padding-right:' . format_custom_property( $padding['right'] ?? '' ),
					'padding-bottom:' . format_custom_property( $padding['bottom'] ?? '' ),
					'padding-left:' . format_custom_property( $padding['left'] ?? '' ),
				]
			)
		);
	}

	$html       = $dom->saveHTML();
	$class_name = $block['attrs']['className'] ?? '';

	if ( $class_name && str_contains( $class_name, 'is-style-toggle' ) ) {
		$dom         = dom( $html );
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
			trim(
				str_replace(
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

		$html = $dom->saveHTML();
	}

	$post_type = get_post_type();

	if ( $post_type && is_post_type_archive() ) {
		$dom  = dom( $html );
		$form = get_dom_element( 'form', $dom );

		if ( $form ) {
			$post_type_field = $dom->createElement( 'input' );
			$post_type_field->setAttribute( 'type', 'hidden' );
			$post_type_field->setAttribute( 'name', 'post_type' );
			$post_type_field->setAttribute( 'value', $post_type );

			$form->insertBefore( $post_type_field, $form->firstChild );

			$html = $dom->saveHTML();
		}
	}

	return $html;
}
