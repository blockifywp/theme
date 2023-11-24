<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function explode;
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
	$dom   = dom( $html );
	$form  = get_dom_element( 'form', $dom );
	$wrap  = get_dom_element( 'div', $form );
	$input = get_dom_element( 'input', $wrap );

	if ( ! $input ) {
		return $html;
	}

	$button              = get_dom_element( 'button', $wrap );
	$use_icon            = $block['attrs']['buttonUseIcon'] ?? false;
	$button_position     = $block['attrs']['buttonPosition'] ?? 'button-outside';
	$show_icon           = ! $use_icon || ( $button_position === 'button-outside' || $button_position === 'no-button' );
	$padding             = $block['attrs']['style']['spacing']['padding'] ?? [];
	$margin              = $block['attrs']['style']['spacing']['margin'] ?? [];
	$background_color    = $block['attrs']['backgroundColor'] ?? '';
	$background_custom   = $block['attrs']['style']['color']['background'] ?? '';
	$input_background    = $block['attrs']['inputBackgroundColor'] ?? '';
	$border              = $block['attrs']['style']['border'] ?? [];
	$border_color        = $block['attrs']['style']['border']['color'] ?? $block['attrs']['borderColor'] ?? '';
	$box_shadow          = $block['attrs']['style']['boxShadow'] ?? [];
	$shadow_preset       = $block['attrs']['shadowPreset'] ?? '';
	$shadow_preset_hover = $block['attrs']['shadowPresetHover'] ?? '';

	$input_styles  = css_string_to_array( $input->getAttribute( 'style' ) );
	$input_classes = explode( ' ', $input->getAttribute( 'class' ) );

	$button_styles = $button ? css_string_to_array( $button->getAttribute( 'style' ) ) : [];

	if ( ! $button || $button_position === 'button-inside' ) {
		if ( $background_color ) {
			$input_classes[] = "has-{$background_color}-background-color";
		}

		if ( $background_custom ) {
			$input_styles['background-color'] = format_custom_property( $background_custom );
		}
	}

	if ( $shadow_preset ) {
		$input_classes[] = "has-{$shadow_preset}-shadow";
	}

	if ( $shadow_preset_hover ) {
		$input_classes[] = "has-{$shadow_preset_hover}-shadow-hover";
	}

	if ( $box_shadow ) {
		$x      = $box_shadow['x'] ?? '0';
		$y      = $box_shadow['y'] ?? '0';
		$blur   = $box_shadow['blur'] ?? '0';
		$spread = $box_shadow['spread'] ?? '0';
		$color  = $box_shadow['color'] ?? '';

		$input_styles['box-shadow'] = "{$x}px {$y}px {$blur}px {$spread}px {$color}";
	}

	if ( $padding['top'] ?? '' ) {
		$input_styles['padding-top'] = format_custom_property( $padding['top'] ?? '' );
	}

	if ( $padding['right'] ?? '' ) {
		$input_styles['padding-right'] = format_custom_property( $padding['right'] ?? '' );
	}

	if ( $padding['bottom'] ?? '' ) {
		$input_styles['padding-bottom'] = format_custom_property( $padding['bottom'] ?? '' );
	}

	if ( $padding['left'] ?? '' ) {
		$padding_left = 'calc(' . format_custom_property( $padding['left'] ?? '' ) . ' * 2)';

		if ( $show_icon ) {
			$padding_left = 'calc(1em + (' . format_custom_property( $padding['left'] ?? '' ) . ' * 2))';
		}

		$input_styles['padding-left'] = $padding_left;
	}

	if ( $border['width'] ?? '' ) {
		$input_styles['border-width'] = format_custom_property( $border['width'] ?? '' );
	}

	if ( $border['style'] ?? '' ) {
		$input_styles['border-style'] = format_custom_property( $border['style'] ?? '' );
	}

	if ( $border_color ) {
		$input_styles['border-color'] = format_custom_property( $border_color );
	}

	if ( $border['radius'] ?? '' ) {
		$input_styles['border-radius'] = format_custom_property( $border['radius'] ?? '' );
	}

	if ( $input_background ) {
		$input_styles['background-color'] = format_custom_property( $input_background );
	}

	if ( $input_styles ) {
		$input_styles['height'] = 'auto';

		$input->setAttribute(
			'style',
			css_array_to_string( $input_styles )
		);
	}

	$input->setAttribute(
		'class',
		implode( ' ', $input_classes )
	);

	if ( $button && $button_styles ) {
		$button->setAttribute(
			'style',
			css_array_to_string( $button_styles )
		);
	}

	$form_styles = css_string_to_array( $form->getAttribute( 'style' ) );

	if ( $margin['top'] ?? '' ) {
		$form_styles['margin-top'] = format_custom_property( $margin['top'] ?? '' );
	}

	if ( $margin['right'] ?? '' ) {
		$form_styles['margin-right'] = format_custom_property( $margin['right'] ?? '' );
	}

	if ( $margin['bottom'] ?? '' ) {
		$form_styles['margin-bottom'] = format_custom_property( $margin['bottom'] ?? '' );
	}

	if ( $margin['left'] ?? '' ) {
		$form_styles['margin-left'] = format_custom_property( $margin['left'] ?? '' );
	}

	if ( $form_styles ) {
		$form->setAttribute(
			'style',
			css_array_to_string( $form_styles )
		);
	}

	$wrap_styles = css_string_to_array( $wrap->getAttribute( 'style' ) );
	$gap         = $block['attrs']['style']['spacing']['blockGap'] ?? '';

	if ( $gap ) {
		$wrap_styles['gap'] = format_custom_property( $gap );
	}

	if ( $wrap_styles ) {
		$wrap->setAttribute(
			'style',
			css_array_to_string( $wrap_styles )
		);
	}

	if ( $show_icon ) {
		$svg_dom = dom( get_icon( 'wordpress', 'search' ) );
		$svg     = get_dom_element( 'svg', $svg_dom );

		if ( ! $svg ) {
			return $dom->saveHTML();
		}

		$svg_classes   = explode( ' ', $svg->getAttribute( 'class' ) );
		$svg_styles    = css_string_to_array( $svg->getAttribute( 'style' ) );
		$svg_classes[] = 'wp-block-search__icon';

		if ( $padding['left'] ?? '' ) {
			$left = format_custom_property( $padding['left'] );

			$svg_styles['left'] = 'calc(0.25em + (' . $left . ' / 2))';
		}

		$svg->setAttribute( 'class', trim( implode( ' ', $svg_classes ) ) );
		$svg->setAttribute( 'style', css_array_to_string( $svg_styles ) );

		$imported_svg = $dom->importNode( $svg, true );
		$wrap->insertBefore( $imported_svg, $input );
	}

	$post_type = $block['attrs']['postType'] ?? '';

	if ( $post_type ) {
		$form = get_dom_element( 'form', $dom );

		if ( $form ) {
			$post_type_field = create_element( 'input', $dom );
			$post_type_field->setAttribute( 'type', 'hidden' );
			$post_type_field->setAttribute( 'name', 'post_type' );
			$post_type_field->setAttribute( 'value', $post_type );

			$form->insertBefore( $post_type_field, $form->firstChild );
		}
	}

	return $dom->saveHTML();
}
