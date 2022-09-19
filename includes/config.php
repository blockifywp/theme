<?php

declare( strict_types=1 );

namespace Blockify\Theme;

/**
 * Returns the final merged config.
 *
 * @since 0.0.9
 *
 * @param string $sub_config The sub config to return. (Optional).
 *
 * @return mixed
 */
function get_config( string $sub_config = '' ) {
	$defaults['blockSupports'] = [
		'blockify/accordion'       => [
			'boxShadow' => true,
		],
		'blockify/email'           => [
			'boxShadow' => true,
		],
		'blockify/icon'            => [
			'boxShadow' => true,
		],
		'blockify/newsletter'      => [
			'boxShadow' => true,
		],
		'blockify/submit'          => [
			'boxShadow' => true,
		],
		'blockify/popup'           => [
			'boxShadow' => true,
		],
		'blockify/tabs'            => [
			'boxShadow' => true,
		],
		'blockify/text-area'       => [
			'minHeight' => '6em',
		],
		'core/buttons'             => [],
		'core/button'              => [
			'typography'           => [
				'fontSize'   => true,
				'fontWeight' => true,
				'fontFamily' => true,
			],
			'boxShadow'            => true,
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
		],
		'core/code'                => [
			'boxShadow' => true,
		],
		'core/column'              => [
			'boxShadow'            => true,
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'position'             => true,
		],
		'core/columns'             => [
			'boxShadow'     => true,
			'typography'    => [
				'fontSize'   => true,
				'fontWeight' => true,
			],
			'reverseMobile' => true,
			'position'      => true,
		],
		'core/cover'               => [
			'position' => true,
		],
		'core/embed'               => [
			'spacing'              => [
				'margin' => true,
			],
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
		],
		'core/gallery'             => [
			'spacing' => [
				'margin' => true,
			],
		],
		'core/group'               => [
			'boxShadow' => true,
			'position'  => true,
			'spacing'   => true,
			'minHeight' => true,
		],
		'core/heading'             => [
			'align'     => [
				'full',
				'wide',
				'none',
			],
			'alignWide' => true,
			'color'     => [
				'gradients'  => true,
				'background' => true,
				'text'       => true, // For SVG currentColor.
			],
			'spacing'   => [
				'margin'  => true,
				'padding' => true,
			],
		],
		'core/image'               => [
			'boxShadow'            => true,
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'color'                => [
				'gradients'  => true,
				'background' => true,
				'text'       => true, // For SVG currentColor.
			],
			'spacing'              => [
				'margin'  => true,
				'padding' => true,
			],
			'transform'            => true,
			'filter'               => true,
			'position'             => true,
		],
		'core/list'                => [
			'__experimentalLayout' => [
				'allowSwitching'  => false,
				'allowInheriting' => false,
				'default'         => [
					'type'        => 'flex',
					'orientation' => 'vertical',
				],
			],
			'spacing'              => [
				'padding'  => true,
				'margin'   => true,
				'blockGap' => true,
			],
		],
		'core/media-text'          => [
			'__experimentalBorder' => [
				'radius' => true,
			],
			'spacing'              => [
				'margin' => true,
			],
		],
		'core/navigation'          => [
			'spacing' => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
		],
		'core/navigation-submenu'  => [
			'spacing' => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
			'color'   => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
		],
		'core/paragraph'           => [
			'align'                => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide'            => true,
			'color'                => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'spacing'              => [
				'margin'  => true,
				'padding' => true,
			],
		],
		'core/post-content'        => [
			'align'     => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide' => true,
			'spacing'   => [
				'margin'  => true,
				'padding' => true,
			],
		],
		'core/post-author'         => [
			// Border applied to avatar.
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => false,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
		],
		'core/post-excerpt'        => [
			'__experimentalLayout' => [
				'allowSwitching'  => false,
				'allowInheriting' => false,
				'default'         => [
					'type' => 'flex',
				],
			],
		],
		'core/post-date'           => [
			'spacing' => [
				'margin' => true,
			],
		],
		'core/post-featured-image' => [
			'align'     => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide' => true,
			'color'     => [
				'background' => true,
			],
		],
		'core/post-terms'          => [
			'align'     => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide' => true,
			'spacing'   => [
				'padding' => true,
				'margin'  => true,
			],
		],
		'core/post-title'          => [
			'spacing' => [
				'padding' => true,
				'margin'  => true,
			],
		],
		'core/query'               => [
			'spacing' => [
				'padding'  => true,
				'blockGap' => true,
			],
		],
		'core/quote'               => [
			'spacing'              => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
		],
		'core/row'                 => [
			'boxShadow' => true,
		],
		'core/search'              => [
			'boxShadow' => true,
			'spacing'   => [
				'padding' => true,
				'margin'  => true,
			],
		],
		'core/separator'           => [
			'align'                => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'color'                => [
				'text'       => true,
				'background' => false,
			],
			'alignWide'            => true,
			'__experimentalBorder' => [
				'radius'                        => false,
				'width'                         => true,
				'color'                         => false,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'spacing'              => [
				'margin'  => true,
				'padding' => false,
			],
		],
		'core/site-logo'           => [
			'color' => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
		],
		'core/social-link'         => [
			'color' => [
				'background' => false,
				'text'       => true,
			],
		],
		'core/spacer'              => [
			'align'                => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide'            => true,
			'boxShadow'            => true,
			'color'                => [
				'gradients'  => true,
				'background' => true,
				'text'       => true,
			],
			'spacing'              => [
				'margin' => true,
			],
			'position'             => true,
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
		],
		'core/tag-cloud'           => [
			'typography' => [
				'textTransform' => true, // Doesn't work.
				'letterSpacing' => true, // Doesn't work.
			],
		],
		'core/template-part'       => [
			'boxShadow' => true,
			'color'     => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'position'  => true,
		],
		'core/video'               => [
			'boxShadow' => true,
			'color'     => [
				'gradients'  => true,
				'background' => true,
				'text'       => true,
			],
			'spacing'   => [
				'margin' => true, // Doesn't work.
			],
		],
	];

	$defaults['blockStyles']['register'] = [
		[
			'type'  => 'core/image',
			'name'  => 'icon',
			'label' => __( 'SVG Icon', 'blockify' ),
		],
		[
			'type'  => 'core/image',
			'name'  => 'placeholder',
			'label' => __( 'Placeholder', 'blockify' ),
		],
		[
			'type'  => 'core/site-logo',
			'name'  => 'icon',
			'label' => __( 'Icon', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'numbered',
			'label' => __( 'Numbered', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'checklist',
			'label' => __( 'Checklist', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'checklist',
			'label' => __( 'Check Circle', 'blockify' ),
		],
		[
			'type'  => 'core/list',
			'name'  => 'square',
			'label' => __( 'Square', 'blockify' ),
		],
		[
			'type'  => 'core/navigation-submenu',
			'name'  => 'mega-menu',
			'label' => __( 'Mega Menu', 'blockify' ),
		],
		[
			'type'  => 'core/search',
			'name'  => 'toggle',
			'label' => __( 'Toggle', 'blockify' ),
		],
		[
			'type'  => 'core/spacer',
			'name'  => 'angle',
			'label' => __( 'Angle', 'blockify' ),
		],
		[
			'type'  => 'core/spacer',
			'name'  => 'curve',
			'label' => __( 'Curve', 'blockify' ),
		],
		[
			'type'  => 'core/spacer',
			'name'  => 'round',
			'label' => __( 'Round', 'blockify' ),
		],
		[
			'type'  => 'core/spacer',
			'name'  => 'wave',
			'label' => __( 'Wave', 'blockify' ),
		],
		[
			'type'  => 'core/spacer',
			'name'  => 'fade',
			'label' => __( 'Fade', 'blockify' ),
		],
	];

	$defaults['blockStyles']['unregister'] = [
		[
			'type' => 'core/image',
			'name' => [ 'rounded' ],
		],
		[
			'type' => 'core/site-logo',
			'name' => [ 'rounded' ],
		],
		[
			'type' => 'core/separator',
			'name' => [ 'wide', 'dots' ],
		],
	];

	$defaults['icons'] = [
		'dashicons' => DIR . 'assets/svg/dashicons',
		'wordpress' => DIR . 'assets/svg/wordpress',
		'social'    => DIR . 'assets/svg/social',
	];

	$defaults['themeSupports'] = [
		'add'    => [
			'responsive-embeds',
		],
		'remove' => [
			'core-block-patterns',
		],
	];

	$defaults['postTypeSupports'] = [
		'add' => [
			'page'          => [ 'excerpt' ],
			'block_pattern' => [ 'excerpt' ],
		],
	];

	$defaults['darkModeColorPalette'] = [
		'neutral-900' => 'neutral-100',
		'neutral-800' => 'neutral-200',
		'neutral-700' => 'neutral-200',
		'neutral-600' => 'neutral-300',
		'neutral-500' => 'neutral-300',
		'neutral-400' => 'neutral-400',
		'neutral-300' => 'neutral-500',
		'neutral-200' => 'neutral-500',
		'neutral-100' => 'neutral-600',
		'neutral-50'  => 'neutral-800',
		'neutral-25'  => 'neutral-800',
		'white'       => 'neutral-900',
	];

	$config = apply_filters( SLUG, $defaults );

	return $config[ $sub_config ] ?? $config;
}
