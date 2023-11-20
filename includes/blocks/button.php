<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;
use function array_unique;
use function explode;
use function implode;
use function in_array;
use function str_contains;
use function str_replace;
use function wp_get_global_settings;

add_filter( 'render_block', NS . 'render_button_block', 9, 2 );
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
function render_button_block( string $html, array $block ): string {
	$block_name = $block['blockName'] ?? null;

	// Using render_block for earlier priority.
	if ( 'core/button' !== $block_name ) {
		return $html;
	}

	if ( str_contains( $html, 'is-style-outline' ) ) {
		$dom    = dom( $html );
		$div    = get_dom_element( 'div', $dom );
		$anchor = get_dom_element( 'a', $div );

		if ( $anchor ) {
			$classes = explode( ' ', $anchor->getAttribute( 'class' ) );
			$anchor->setAttribute(
				'class',
				implode(
					' ',
					[
						...$classes,
						'wp-element-button',
					]
				)
			);

			$html = $dom->saveHTML();
		}
	}

	if ( str_contains( $html, '-border-' ) ) {
		$global_settings = wp_get_global_settings();
		$dom             = dom( $html );
		$div             = get_dom_element( 'div', $dom );
		$link            = get_dom_element( 'a', $dom );

		if ( ! $div ) {
			$div = create_element( 'div', $dom );
		}

		if ( ! $link ) {
			$link = create_element( 'a', $dom );
		}

		$classes     = explode( ' ', $div->getAttribute( 'class' ) );
		$styles      = explode( ';', $div->getAttribute( 'style' ) );
		$div_classes = [];
		$div_styles  = [];

		foreach ( $classes as $class ) {
			if ( ! str_contains( $class, '-border-' ) ) {
				$div_classes[] = $class;
			}
		}

		foreach ( $styles as $style ) {
			if ( ! str_contains( $style, 'border-' ) ) {
				$div_styles[] = $style;
			}
		}

		$border_width = $block['attrs']['style']['border']['width'] ?? null;
		$border_color = $block['attrs']['style']['border']['color'] ?? null;

		$link_styles = explode( ';', $link->getAttribute( 'style' ) );

		if ( $border_width || $border_color ) {
			$border_width  = $border_width ?? $global_settings['custom']['border']['width'];
			$link_styles[] = "line-height:calc(1em - $border_width)";
		}

		$link->setAttribute( 'style', implode( ';', $link_styles ) );

		$div->setAttribute( 'class', implode( ' ', $div_classes ) );
		$div->setAttribute( 'style', implode( ';', $div_styles ) );

		if ( ! $div->getAttribute( 'style' ) ) {
			$div->removeAttribute( 'style' );
		}

		$html = $dom->saveHTML();
	}

	$icon_set  = $block['attrs']['iconSet'] ?? '';
	$icon_name = $block['attrs']['iconName'] ?? '';
	$icon      = $icon_set && $icon_name ? get_icon( $icon_set, $icon_name ) : '';

	if ( $icon ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( ! $div ) {
			$div   = create_element( 'div', $dom );
			$div   = dom_element( $dom->appendChild( $div ) );
			$class = $block['attrs']['className'] ?? null;

			$div->setAttribute( 'class', 'wp-block-button ' . $class );
		}

		$div_styles = css_string_to_array( $div->getAttribute( 'style' ) );

		foreach ( $div_styles as $key => $style ) {
			if ( str_contains( $key, '--wp--custom--icon--' ) ) {
				unset( $div_styles[ $key ] );
			}
		}

		$div->setAttribute( 'style', css_array_to_string( $div_styles ) );

		$a = get_dom_element( 'a', $div );

		if ( ! $a ) {
			$a = create_element( 'a', $dom );

			$a->setAttribute( 'class', 'wp-block-button__link wp-element-button' );

			$a = dom_element( $div->appendChild( $a ) );
		}

		$svg_dom  = dom( $icon );
		$svg      = get_dom_element( 'svg', $svg_dom );
		$imported = dom_element( $dom->importNode( $svg, true ) );
		$gap      = $block['attrs']['style']['spacing']['blockGap'] ?? null;
		$classes  = explode( ' ', $a->getAttribute( 'class' ) );
		$styles   = css_string_to_array( $a->getAttribute( 'style' ) );

		if ( $gap ) {
			$styles['gap'] = format_custom_property( $gap );
		}

		$padding = $block['attrs']['style']['spacing']['padding'] ?? [];

		foreach ( $padding as $side => $value ) {
			$styles["padding-$side"] = format_custom_property( $value );
		}

		$text_color = $block['attrs']['textColor'] ?? null;

		if ( $text_color ) {
			$styles['color'] = format_custom_property( $text_color );
		}

		$background_color = $block['attrs']['backgroundColor'] ?? null;

		if ( $background_color ) {
			$styles['background-color'] = format_custom_property( $background_color );
			$classes[]                  = 'has-background';
		}

		$border_width = $block['attrs']['style']['border']['width'] ?? null;
		$border_style = $block['attrs']['style']['border']['style'] ?? null;
		$border_color = $block['attrs']['style']['border']['color'] ?? null;

		if ( $border_width ) {
			$styles['border-width'] = format_custom_property( $border_width );
		}

		if ( $border_style ) {
			$styles['border-style'] = format_custom_property( $border_style );
		}

		if ( $border_color ) {
			$styles['border-color'] = format_custom_property( $border_color );
		}

		if ( $styles ) {
			$a->setAttribute( 'style', css_array_to_string( $styles ) );
		}

		$a->setAttribute( 'class', implode( ' ', array_unique( $classes ) ) );

		$on_click = $block['attrs']['onclick'] ?? null;

		if ( $on_click ) {
			$a->setAttribute( 'onclick', format_inline_js( $on_click ) );
		}

		$url = $block['attrs']['url'] ?? $a->getAttribute( 'href' );

		if ( ! $url ) {
			if ( ! $on_click ) {
				$a->setAttribute( 'href', '#' );
			} else {
				$a->setAttribute( 'href', 'javascript:void(0)' );
			}
		}

		$size = ( $block['attrs']['iconSize'] ?? null ) ?: '20';

		if ( str_contains( $size, 'var' ) ) {
			$svg_styles = css_string_to_array( $svg->getAttribute( 'style' ) );

			unset ( $svg_styles['enable-background'] );

			$svg_styles['height'] = format_custom_property( $size );
			$svg_styles['width']  = format_custom_property( $size );

			$imported->setAttribute( 'style', css_array_to_string( $svg_styles ) );

		} else {
			$imported->setAttribute( 'height', $size );
			$imported->setAttribute( 'width', $size );
		}

		$fill = $imported->getAttribute( 'fill' );

		if ( ! $fill ) {
			$imported->setAttribute( 'fill', 'currentColor' );
		}

		$icon_position = $block['attrs']['iconPosition'] ?? 'end';

		if ( $icon_position === 'start' ) {
			$svg = $a->insertBefore( $imported, $a->firstChild );
		} else {
			$svg = $a->appendChild( $imported );
		}

		$title = $svg->insertBefore(
			create_element( 'title', $dom ),
			$svg->firstChild
		);

		$text = $dom->createTextNode( to_title_case( $block['attrs']['iconName'] ?? '' ) );

		$title->appendChild( $text );

		$html = add_position_classes( $dom->saveHTML(), $block );
	}

	$url = $block['attrs']['url'] ?? null;

	if ( ! $url ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );
		$a   = get_dom_element( 'a', $div );

		if ( $a ) {
			$href = $a->getAttribute( 'href' );

			if ( $href ) {
				$a->setAttribute( 'href', $href );
			} else {

				$on_click = $block['attrs']['onclick'] ?? null;

				if ( ! $on_click ) {
					$a->setAttribute( 'href', '#' );
				} else {
					$a->setAttribute( 'href', 'javascript:void(0)' );
				}
			}
		}

		$html = $dom->saveHTML();
	}

	$size = $block['attrs']['size'] ?? 'medium';

	if ( in_array( $size, [ 'small', 'large' ] ) ) {
		$dom = dom( $html );
		$div = get_dom_element( 'div', $dom );

		if ( $div ) {
			$div_classes   = explode( ' ', $div->getAttribute( 'class' ) );
			$div_classes[] = "is-style-$size";
			$div->setAttribute( 'class', implode( ' ', $div_classes ) );
		}

		$html = $dom->saveHTML();
	}

	$inner_html = $block['innerHTML'] ?? $block['innerContent'] ?? $html;
	$back_urls  = [
		'javascript:history.go(-1)',
		'javascript: history.go(-1)',
	];

	foreach ( $back_urls as $back_url ) {
		if ( str_contains( $inner_html, $back_url ) ) {
			$html = str_replace( 'href="#"', 'href="' . $back_url . '"', $html );
		}
	}

	if ( str_contains( $html, 'javascript:void' ) ) {
		$html = str_replace(
			[
				'http://javascript:void',
				'target="_blank"',
			],
			[
				'javascript:void',
				'disabled',
			],
			$html
		);
	}

	if ( str_contains( $html, 'href="http://#"' ) ) {
		$html = str_replace(
			[
				'href="http://#"',
				'target="_blank"',
			],
			[
				'href="#"',
				'',
			],
			$html
		);
	}

	return $html;
}
