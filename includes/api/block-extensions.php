<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function _wp_to_kebab_case;
use function add_filter;
use function array_merge;
use function implode;
use function sprintf;
use function str_contains;
use function str_replace;

/**
 * Returns responsive settings config.
 *
 * @since 0.9.10
 *
 * @return array
 */
function get_block_extension_options(): array {
	return [
		'position'            => [
			'property' => 'position',
			'label'    => __( 'Position', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Relative', 'blockify' ),
					'value' => 'relative',
				],
				[
					'label' => __( 'Absolute', 'blockify' ),
					'value' => 'absolute',
				],
				[
					'label' => __( 'Sticky', 'blockify' ),
					'value' => 'sticky',
				],
				[
					'label' => __( 'Fixed', 'blockify' ),
					'value' => 'fixed',
				],
				[
					'label' => __( 'Static', 'blockify' ),
					'value' => 'static',
				],
			],
		],
		'top'                 => [
			'property' => 'top',
			'label'    => __( 'Top', 'blockify' ),
		],
		'right'               => [
			'property' => 'right',
			'label'    => __( 'Right', 'blockify' ),
		],
		'bottom'              => [
			'property' => 'bottom',
			'label'    => __( 'Bottom', 'blockify' ),
		],
		'left'                => [
			'property' => 'left',
			'label'    => __( 'Left', 'blockify' ),
		],
		'zIndex'              => [
			'property' => 'z-index',
			'label'    => __( 'Z-Index', 'blockify' ),
		],
		'display'             => [
			'property' => 'display',
			'label'    => __( 'Display', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'Flex', 'blockify' ),
					'value' => 'flex',
				],
				[
					'label' => __( 'Inline Flex', 'blockify' ),
					'value' => 'inline-flex',
				],
				[
					'label' => __( 'Block', 'blockify' ),
					'value' => 'block',
				],
				[
					'label' => __( 'Inline Block', 'blockify' ),
					'value' => 'inline-block',
				],
				[
					'label' => __( 'Inline', 'blockify' ),
					'value' => 'inline',
				],
				[
					'label' => __( 'Grid', 'blockify' ),
					'value' => 'grid',
				],
				[
					'label' => __( 'Inline Grid', 'blockify' ),
					'value' => 'inline-grid',
				],
				[
					'label' => __( 'Contents', 'blockify' ),
					'value' => 'contents',
				],
			],
		],
		'order'               => [
			'property' => 'order',
			'label'    => __( 'Order', 'blockify' ),
		],
		'gridTemplateColumns' => [
			'property' => 'grid-template-columns',
			'label'    => __( 'Columns', 'blockify' ),
		],
		'gridTemplateRows'    => [
			'property' => 'grid-template-rows',
			'label'    => __( 'Rows', 'blockify' ),
		],
		'gridColumnStart'     => [
			'property' => 'grid-column-start',
			'label'    => __( 'Column Start', 'blockify' ),
		],
		'gridColumnEnd'       => [
			'property' => 'grid-column-end',
			'label'    => __( 'Column End', 'blockify' ),
		],
		'gridRowStart'        => [
			'property' => 'grid-row-start',
			'label'    => __( 'Row Start', 'blockify' ),
		],
		'gridRowEnd'          => [
			'property' => 'grid-row-end',
			'label'    => __( 'Row End', 'blockify' ),
		],
		'overflow'            => [
			'property' => 'overflow',
			'label'    => __( 'Overflow', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Hidden', 'blockify' ),
					'value' => 'hidden',
				],
				[
					'label' => __( 'Visible', 'blockify' ),
					'value' => 'visible',
				],
			],
		],
		'pointerEvents'       => [
			'property' => 'pointer-events',
			'label'    => __( 'Pointer Events', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'All', 'blockify' ),
					'value' => 'all',
				],
			],
		],
		'width'               => [
			'property' => 'width',
			'label'    => __( 'Width', 'blockify' ),
		],
		'minWidth'            => [
			'property' => 'min-width',
			'label'    => __( 'Min Width', 'blockify' ),
		],
		'maxWidth'            => [
			'property' => 'max-width',
			'label'    => __( 'Max Width', 'blockify' ),
		],
	];
}

/**
 * Returns the filter options.
 *
 * @since 1.3.0
 *
 * @return array
 */
function get_filter_options(): array {
	return [
		'blur'       => [
			'unit' => 'px',
			'min'  => 0,
			'max'  => 500,
		],
		'brightness' => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 360,
		],
		'contrast'   => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 200,
		],
		'grayscale'  => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 100,
		],
		'hueRotate'  => [
			'unit' => 'deg',
			'min'  => -360,
			'max'  => 360,
		],
		'invert'     => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 100,
		],
		'opacity'    => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 100,
		],
		'saturate'   => [
			'unit' => '',
			'min'  => 0,
			'max'  => 100,
			'step' => 0.1,
		],
		'sepia'      => [
			'unit' => '%',
			'min'  => 0,
			'max'  => 100,
		],
	];
}

/**
 * Get the image options.
 *
 * @since 1.3.0
 *
 * @return array
 */
function get_image_options(): array {
	return [
		'aspectRatio'    => [
			'property' => 'aspect-ratio',
			'label'    => __( 'Aspect Ratio', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => '1/1',
					'value' => '1/1',
				],
				[
					'label' => '1/2',
					'value' => '1/2',
				],
				[
					'label' => '1/3',
					'value' => '1/3',
				],
				[
					'label' => '2/1',
					'value' => '2/1',
				],
				[
					'label' => '2/3',
					'value' => '2/3',
				],
				[
					'label' => '3/1',
					'value' => '3/1',
				],
				[
					'label' => '3/2',
					'value' => '3/2',
				],
				[
					'label' => '3/4',
					'value' => '3/4',
				],
				[
					'label' => '4/3',
					'value' => '4/3',
				],
				[
					'label' => '4/5',
					'value' => '4/5',
				],
				[
					'label' => '5/4',
					'value' => '5/4',
				],
				[
					'label' => '9/16',
					'value' => '9/16',
				],
				[
					'label' => '16/9',
					'value' => '16/9',
				],
			],
		],
		'height'         => [
			'property' => 'height',
			'label'    => __( 'Height', 'blockify' ),
		],
		'objectFit'      => [
			'property' => 'object-fit',
			'label'    => __( 'Object Fit', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Fill', 'blockify' ),
					'value' => 'fill',
				],
				[
					'label' => __( 'Contain', 'blockify' ),
					'value' => 'contain',
				],
				[
					'label' => __( 'Cover', 'blockify' ),
					'value' => 'cover',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
				[
					'label' => __( 'Scale Down', 'blockify' ),
					'value' => 'scale-down',
				],
			],
		],
		'objectPosition' => [
			'property' => 'object-position',
			'label'    => __( 'Object Position', 'blockify' ),
			'options'  => [
				[
					'label' => '',
					'value' => '',
				],
				[
					'label' => __( 'Top', 'blockify' ),
					'value' => 'top',
				],
				[
					'label' => __( 'Right', 'blockify' ),
					'value' => 'right',
				],
				[
					'label' => __( 'Bottom', 'blockify' ),
					'value' => 'bottom',
				],
				[
					'label' => __( 'Left', 'blockify' ),
					'value' => 'left',
				],
				[
					'label' => __( 'Center', 'blockify' ),
					'value' => 'center',
				],
				[
					'label' => __( 'None', 'blockify' ),
					'value' => 'none',
				],
			],
		],
	];
}

add_filter( 'blockify_editor_data', NS . 'register_responsive_settings', 11 );
/**
 * Add default block supports.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_responsive_settings( array $config = [] ): array {
	$config['extensionOptions'] = get_block_extension_options();
	$config['filterOptions']    = get_filter_options();
	$config['imageOptions']     = get_image_options();

	return $config;
}

add_filter( 'blockify_inline_css', NS . 'get_block_extra_styles', 11, 3 );
/**
 * Conditionally adds CSS for utility classes
 *
 * @since 0.9.19
 *
 * @param string $css     Inline CSS.
 * @param string $content Page Content.
 * @param bool   $all     Is editor page.
 *
 * @return string
 */
function get_block_extra_styles( string $css, string $content, bool $all ): string {
	$options = array_merge(
		get_block_extension_options(),
		get_image_options(),
	);
	$both    = '';
	$mobile  = '';
	$desktop = '';

	foreach ( $options as $key => $args ) {
		$property       = _wp_to_kebab_case( $key );
		$select_options = $args['options'] ?? [];

		foreach ( $select_options as $option ) {
			$value = $option['value'] ?? '';

			if ( ! $value ) {
				continue;
			}

			$formatted_value = $value;

			if ( 'aspect-ratio' === $property ) {
				$formatted_value = str_replace( '/', '\/', $formatted_value );
			}

			if ( $all || str_contains( $content, " has-{$property}-{$value}" ) ) {
				$both .= sprintf(
					'.has-%1$s-%3$s{%1$s:%2$s !important}',
					$property,
					$value,
					$formatted_value,
				);
			}

			if ( $all || str_contains( $content, " has-{$property}-{$value}-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s-%3$s-mobile{%1$s:%2$s !important}',
					$property,
					$value,
					$formatted_value,
				);
			}

			if ( $all || str_contains( $content, " has-{$property}-{$value}-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s-%3$s-desktop{%1$s:%2$s !important}',
					$property,
					$value,
					$formatted_value,
				);
			}
		}

		// Has custom value.
		if ( ! $select_options ) {

			if ( $all || str_contains( $content, " has-$property" ) ) {
				$both .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s)}',
					$property
				);
			}

			if ( $all || str_contains( $content, "--$property-mobile" ) ) {
				$mobile .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-mobile,var(--%1$s))}',
					$property
				);
			}

			if ( $all || str_contains( $content, "--$property-desktop" ) ) {
				$desktop .= sprintf(
					'.has-%1$s{%1$s:var(--%1$s-desktop,var(--%1$s))}',
					$property
				);
			}
		}
	}

	if ( $both ) {
		$css .= $both;
	}

	if ( $mobile ) {
		$css .= sprintf( '@media(max-width:781px){%s}', $mobile );
	}

	if ( $desktop ) {
		$css .= sprintf( '@media(min-width:782px){%s}', $desktop );
	}

	return $css;
}

add_filter( 'render_block', NS . 'add_position_classes', 11, 2 );
/**
 * Adds inline block positioning classes.
 *
 * @since 1.0.0
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block content.
 *
 * @return string
 */
function add_position_classes( string $html, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $html;
	}

	return add_responsive_classes(
		$html,
		$block,
		get_block_extension_options()
	);
}

/**
 * Gets responsive classes for a given property.
 *
 * @since 1.0.0
 *
 * @param string $html    HTML content.
 * @param array  $block   Block data.
 * @param array  $options Block options.
 * @param bool   $image   Is an image block.
 *
 * @return string
 */
function add_responsive_classes( string $html, array $block, array $options, bool $image = false ): string {
	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$element = $first;

	if ( $image ) {
		$link    = get_dom_element( 'a', $first );
		$element = $link ? get_dom_element( 'img', $link ) : get_dom_element( 'img', $first );
	}

	if ( ! $element ) {
		return $html;
	}

	$style   = $block['attrs']['style'] ?? [];
	$classes = explode( ' ', $element->getAttribute( 'class' ) );

	foreach ( $options as $key => $args ) {
		if ( ! isset( $style[ $key ] ) || $style[ $key ] === '' ) {
			continue;
		}

		$property = _wp_to_kebab_case( $key );

		if ( isset( $args['options'] ) ) {
			$both    = $style[ $key ]['all'] ?? '';
			$mobile  = $style[ $key ]['mobile'] ?? '';
			$desktop = $style[ $key ]['desktop'] ?? '';

			if ( $both ) {
				$classes[] = "has-{$property}-{$both}";
			}

			if ( $mobile ) {
				$classes[] = "has-{$property}-{$mobile}-mobile";
			}

			if ( $desktop ) {
				$classes[] = "has-{$property}-{$desktop}-desktop";
			}
		} else {
			$classes[] = "has-{$property}";
		}

		$element->setAttribute( 'class', implode( ' ', $classes ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

add_filter( 'render_block', NS . 'add_position_styles', 11, 2 );
/**
 * Renders block inline positioning styles.
 *
 * @since 1.0.0
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block HTML.
 *
 * @return string
 */
function add_position_styles( string $html, array $block ): string {
	$style = $block['attrs']['style'] ?? [];

	if ( ! $style ) {
		return $html;
	}

	$options = get_block_extension_options();

	foreach ( $options as $key => $args ) {

		if ( ! isset( $style[ $key ] ) ) {
			continue;
		}

		// Has utility class.
		if ( isset( $args['options'] ) ) {
			continue;
		}

		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			continue;
		}

		$styles   = css_string_to_array( $first->getAttribute( 'style' ) );
		$property = _wp_to_kebab_case( $key );
		$both     = $style[ $key ]['all'] ?? '';
		$mobile   = $style[ $key ]['mobile'] ?? '';
		$desktop  = $style[ $key ]['desktop'] ?? '';

		if ( $both ) {
			$styles[ '--' . $property ] = $both;
		}

		if ( $mobile ) {
			$styles[ '--' . $property . '-mobile' ] = $mobile;
		}

		if ( $desktop ) {
			$styles[ '--' . $property . '-desktop' ] = $desktop;
		}

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

add_filter( 'render_block', NS . 'add_opacity_style', 12, 2 );
/**
 * Renders block opacity style.
 *
 * @since 1.0.0
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block HTML.
 *
 * @return string
 */
function add_opacity_style( string $html, array $block ): string {
	$opacity = $block['attrs']['style']['filter']['opacity'] ?? '';

	if ( $opacity ) {
		$dom   = dom( $html );
		$first = get_dom_element( '*', $dom );

		if ( ! $first ) {
			return $html;
		}

		$styles = css_string_to_array( $first->getAttribute( 'style' ) );

		//$styles['opacity'] = $opacity / 100;

		$first->setAttribute( 'style', css_array_to_string( $styles ) );

		$html = $dom->saveHTML();
	}

	return $html;
}

add_filter( 'render_block', NS . 'add_backdrop_blur', 12, 2 );
/**
 * Renders backdrop blur style.
 *
 * @since 1.0.0
 *
 * @param array  $block Block data.
 *
 * @param string $html  Block HTML.
 *
 * @return string
 */
function add_backdrop_blur( string $html, array $block ): string {
	$blur = (string) ( $block['attrs']['style']['filter']['blur'] ?? '' );

	if ( ! $blur ) {
		return $html;
	}

	$use_backdrop = (string) ( $block['attrs']['style']['filter']['backdrop'] ?? '' );

	if ( ! $use_backdrop ) {
		return $html;
	}

	$dom   = dom( $html );
	$first = get_dom_element( '*', $dom );

	if ( ! $first ) {
		return $html;
	}

	$styles = css_string_to_array( $first->getAttribute( 'style' ) );

	$backdrop_blur = 'blur(' . $blur . 'px)';

	unset( $styles['backdrop-filter'] );
	unset( $styles['-webkit-backdrop-filter'] );

	$styles['backdrop-filter']         = $backdrop_blur;
	$styles['-webkit-backdrop-filter'] = $backdrop_blur;

	$opacity = (int) ( $block['attrs']['style']['filter']['opacity'] ?? '' );

	if ( $opacity ) {
		$existing = $styles['filter'] ?? '';

		if ( $existing ) {
			$styles['filter'] = str_replace(
				' opacity(' . $opacity . '%)',
				'',
				$existing
			);
		}
	}

	$first->setAttribute( 'style', css_array_to_string( $styles ) );

	return $dom->saveHTML();
}
