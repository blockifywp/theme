<?php

declare( strict_types=1 );

namespace Blockify\Theme;

$defaults = [];

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
	'core/buttons'             => [],
	'core/button'              => [
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
	],
	'core/columns'             => [
		'boxShadow'     => true,
		'typography'    => [
			'fontSize'   => true,
			'fontWeight' => true,
		],
		'reverseMobile' => true,
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
			'radius' => true,
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
			'gradient'   => true, // Doesn't work.
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
			'gradient'   => true, // Doesn't work.
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
			'gradient'   => true, // Doesn't work.
			'link'       => true,
			'text'       => true,
		],
	],
	'core/spacer'              => [
		'boxShadow' => true,
		'color'     => [
			'gradients'  => true,
			'background' => true,
			'text'       => false,
		],
		'spacing'   => [
			'margin' => true,
		],
	],
	'core/template-part'       => [
		'boxShadow' => true,
		'color'     => [
			'background' => true,
			'gradient'   => true, // Doesn't work.
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

$defaults['extensions'] = [
	// 'templateParts',
	'pageTitle',
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

$defaults['darkMode'] = [
	'neutral-900' => 'neutral-100',
	'neutral-800' => 'neutral-200',
	'neutral-700' => 'neutral-200',
	'neutral-600' => 'neutral-300',
	'neutral-500' => 'neutral-300',
	'neutral-400' => 'neutral-400',
	'neutral-300' => 'neutral-500',
	'neutral-200' => 'neutral-500',
	'neutral-100' => 'neutral-600',
	'neutral-50'  => 'neutral-700',
	'neutral-25'  => 'neutral-800',
	'white'       => 'neutral-900',
];

$defaults['recommendedPlugins'] = [
	[
		'name'     => 'Blockify',
		'slug'     => 'blockify',
		'required' => false,
	],
	[
		'name'     => 'Gutenberg',
		'slug'     => 'gutenberg',
		'required' => false,
	],
];

return $defaults;
