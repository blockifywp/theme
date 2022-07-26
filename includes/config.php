<?php

declare( strict_types=1 );

namespace Blockify;

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
		'spacing' => [
			'margin' => true,
		],
	],
	'core/gallery'             => [
		'spacing' => [
			'margin' => true,
		],
	],
	'core/group'               => [
		'boxShadow' => true,
	],
	'core/heading'             => [
		'spacing' => [
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
	'core/query'               => [
		'spacing' => [
			'padding'  => true,
			'blockGap' => true,
		],
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
	'core/row'                 => [
		'boxShadow' => true,
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
		'type'      => 'core/button',
		'name'      => 'primary',
		'label'     => __( 'Primary', 'blockify' ),
		'isDefault' => true,
	],
	[
		'type'  => 'core/button',
		'name'  => 'secondary',
		'label' => __( 'Secondary', 'blockify' ),
	],
	[
		'type'  => 'core/button',
		'name'  => 'outline',
		'label' => __( 'Outline', 'blockify' ),
	],
	[
		'type'  => 'core/button',
		'name'  => 'transparent',
		'label' => __( 'Transparent', 'blockify' ),
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
];

$defaults['blockStyles']['unregister'] = [
	[
		'type' => 'core/button',
		'name' => [ 'fill', 'outline' ],
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

$defaults['recommendedPlugins'] = [
	[
		'name'     => 'Gutenberg',
		'slug'     => 'gutenberg',
		'required' => false,
	],
];

$defaults['themeSupports'] = [
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

// Dark mode (pro).
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

// Theme blocks (pro).
$defaults['blocks'] = [
	'accordion',
	'breadcrumbs',
	'counter',
	'divider',
	'google-map',
	'icon',
	'newsletter',
	'slider',
	'tabs',
];

return $defaults;
