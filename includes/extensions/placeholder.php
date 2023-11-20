<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use DOMDocument;
use DOMElement;
use WP_Block;
use function apply_filters;
use function array_merge;
use function count;
use function explode;
use function glob;
use function implode;
use function in_array;
use function property_exists;
use function str_replace;

/**
 * Returns placeholder HTML element string.
 *
 * @since 0.9.10
 *
 * @param string   $html   Block content.
 * @param array    $block  Block attributes.
 * @param WP_Block $object Block object.
 *
 * @return string
 */
function render_image_placeholder( string $html, array $block, WP_Block $object ): string {
	$use_placeholder = $block['attrs']['usePlaceholder'] ?? 'default';

	if ( ! $use_placeholder || $use_placeholder === 'none' ) {
		return $html;
	}

	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );
	$img    = get_dom_element( 'img', $figure );

	if ( $img && $img->getAttribute( 'src' ) ) {
		return $html;
	}

	$block_name = str_replace(
		'core/',
		'',
		$block['blockName'] ?? ''
	);

	$html   = $html ?: "<figure class='wp-block-{$block_name}'></figure>";
	$dom    = dom( $html );
	$figure = get_dom_element( 'figure', $dom );

	if ( ! $figure ) {
		return $html;
	}

	$img = get_dom_element( 'img', $figure );

	if ( $img ) {
		$figure->removeChild( $img );
	}

	$classes = explode( ' ', $figure->getAttribute( 'class' ) );

	if ( ! in_array( 'is-placeholder', $classes, true ) ) {
		$classes[] = 'is-placeholder';
	}

	if ( $block['align'] ?? null ) {
		$classes[] = 'align' . $block['align'];
	}

	$is_link     = $block['attrs']['isLink'] ?? false;
	$placeholder = get_placeholder_image( $dom );

	if ( $placeholder->tagName === 'svg' ) {
		$classes[] = 'has-placeholder-icon';
	}

	if ( $is_link ) {
		$context = (object) ( property_exists( $object, 'context' ) ? $object->context : null );
		$link    = create_element( 'a', $dom );
		$id_key  = 'postId';

		if ( property_exists( $context, $id_key ) ) {
			$post_id = $context->$id_key ?? null;
			$href    = get_permalink( $post_id );

			if ( $href ) {
				$link->setAttribute( 'href', $href );
			}
		}

		$link_target = $block['linkTarget'] ?? '';

		if ( $link_target ) {
			$link->setAttribute( 'target', $link_target );
		}

		$rel = $block['rel'] ?? '';

		if ( $rel ) {
			$link->setAttribute( 'rel', $rel );
		}

		$link_classes = explode( ' ', $link->getAttribute( 'class' ) );

		if ( ! in_array( 'wp-block-image__link', $link_classes, true ) ) {
			$link_classes[] = 'wp-block-image__link';
		}

		if ( ! in_array( 'is-placeholder', $classes, true ) && ! in_array( 'is-placeholder', $link_classes, true ) ) {
			$link_classes[] = 'is-placeholder';
		}

		$link->setAttribute(
			'class',
			implode( ' ', $link_classes )
		);
		$link->appendChild( $placeholder );
		$figure->appendChild( $link );
	} else {
		$figure->appendChild( $placeholder );
	}

	$style            = $block['attrs']['style'] ?? [];
	$spacing          = $style['spacing'] ?? [];
	$margin           = $spacing['margin'] ?? [];
	$padding          = $spacing['padding'] ?? [];
	$border           = $style['border'] ?? [];
	$radius           = $border['radius'] ?? [];
	$aspect_ratio     = $block['attrs']['aspectRatio'] ?? null;
	$background_color = $block['attrs']['backgroundColor'] ?? null;

	$styles = [
		'width'                      => $block['width'] ?? null,
		'height'                     => $block['height'] ?? null,
		'margin-top'                 => $margin['top'] ?? null,
		'margin-right'               => $margin['right'] ?? null,
		'margin-bottom'              => $margin['bottom'] ?? null,
		'margin-left'                => $margin['left'] ?? null,
		'padding-top'                => $padding['top'] ?? null,
		'padding-right'              => $padding['right'] ?? null,
		'padding-bottom'             => $padding['bottom'] ?? null,
		'padding-left'               => $padding['left'] ?? null,
		'border-width'               => $border['width'] ?? null,
		'border-style'               => $border['style'] ?? ( ( $border['width'] ?? null ) ? 'solid' : null ),
		'border-color'               => $border['color'] ?? null,
		'border-top-left-radius'     => $radius['topLeft'] ?? null,
		'border-top-right-radius'    => $radius['topRight'] ?? null,
		'border-bottom-left-radius'  => $radius['bottomLeft'] ?? null,
		'border-bottom-right-radius' => $radius['bottomRight'] ?? null,
		'position'                   => $style['position']['all'] ?? null,
		'top'                        => $style['top']['all'] ?? null,
		'right'                      => $style['right']['all'] ?? null,
		'bottom'                     => $style['bottom']['all'] ?? null,
		'left'                       => $style['left']['all'] ?? null,
		'z-index'                    => $style['zIndex']['all'] ?? null,
	];

	if ( $aspect_ratio && $aspect_ratio !== 'auto' ) {
		$styles['aspect-ratio'] = $aspect_ratio;
	}

	if ( $background_color === 'transparent' ) {
		$classes[] = 'has-transparent-background-color';
	} else {
		$styles['background-color'] = $background_color;
	}

	$css = css_array_to_string(
		array_merge(
			css_string_to_array(
				$figure->getAttribute( 'style' )
			),
			$styles,
		)
	);

	if ( $css ) {
		$figure->setAttribute( 'style', $css );
	}

	$figure->setAttribute( 'class', implode( ' ', $classes ) );

	return $dom->saveHTML();
}

/**
 * Returns placeholder image element.
 *
 * @param DOMDocument $dom DOM document.
 *
 * @return ?DOMElement
 */
function get_placeholder_image( DOMDocument $dom ): ?DOMElement {
	$image_paths = apply_filters(
		'blockify_placeholder_images',
		glob( get_dir() . 'assets/img/placeholder-*.png' )
	);

	static $last_index = 0;

	$count = count( $image_paths );

	if ( $last_index >= $count ) {
		$last_index = 0;
	}

	if ( $count > 0 ) {
		$img = create_element( 'img', $dom );
		$img->setAttribute( 'src', get_uri() . 'assets/img/' . basename( $image_paths[ $last_index ] ) );
		$img->setAttribute( 'alt', '' );

		$result = dom_element( $img );

		$last_index++;
	} else {
		$svg_title = __( 'Image placeholder', 'blockify' );
		$svg_icon  = <<<HTML
<svg xmlns="http://www.w3.org/2000/svg" role="img" viewBox="0 0 64 64" width="32" height="32">
	<title>$svg_title</title>
	<circle cx="52" cy="18" r="7"/>
	<path d="M47 32.1 39 41 23 20.9 0 55.1h64z"/>
</svg>
HTML;

		/**
		 * Filters the SVG icon for the placeholder image.
		 *
		 * @since 1.3.0
		 *
		 * @param string $svg_icon  SVG icon.
		 * @param string $svg_title SVG title.
		 */
		$svg_icon    = apply_filters( 'blockify_placeholder_svg', $svg_icon, $svg_title );
		$svg_dom     = dom( $svg_icon );
		$svg_element = get_dom_element( 'svg', $svg_dom );

		if ( ! $svg_element ) {
			return create_element( 'span', $dom );
		}

		$svg_classes   = explode( ' ', $svg_element->getAttribute( 'class' ) );
		$svg_classes[] = 'wp-block-image__placeholder-icon';

		$svg_element->setAttribute( 'class', implode( ' ', $svg_classes ) );
		$svg_element->setAttribute( 'fill', 'currentColor' );

		$imported = $dom->importNode( $svg_element, true );
		$result   = dom_element( $imported );
	}

	return $result ?? null;
}
