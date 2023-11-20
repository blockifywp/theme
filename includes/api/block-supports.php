<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'blockify_editor_data', NS . 'register_block_supports' );
/**
 * Add default block supports.
 *
 * @todo  Move to rest settings.
 *
 * @since 0.9.10
 *
 * @param array $data Blockify editor config.
 *
 * @return array
 */
function register_block_supports( array $data = [] ): array {
	$data['blockSupports'] = [
		'blockify/accordion'        => [
			'blockifyBoxShadow' => true,
		],
		'blockify/edd-image-slider' => [
			'blockifyPosition' => true,
		],
		'core/block'                => [
			'className'       => true,
			'customClassName' => true,
		],
		'core/buttons'              => [
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
				'text'       => true,
				'background' => true,
				'gradients'  => true,
			],
			'spacing'              => [
				'padding'  => true,
				'margin'   => true,
				'blockGap' => true,
			],
			'blockifyBoxShadow'    => true,
			'blockifyPosition'     => true,
		],
		'core/button'               => [
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
			'__experimentalLayout' => [
				'allowSwitching'         => true,
				'allowEditing'           => true,
				'allowInheriting'        => true,
				'allowSizingOnChildren'  => true,
				'allowVerticalAlignment' => true,
				'allowJustification'     => true,
				'allowOrientation'       => true,
			],
			'blockifyBoxShadow'    => true,
			'blockifyOnclick'      => true,
			'blockifyPosition'     => true,
			'blockifySize'         => true,
			'blockifyIcon'         => true,
		],
		'core/code'                 => [
			'blockifyBoxShadow' => true,
			'blockifyPosition'  => true,
			'blockifyTransform' => true,
		],
		'core/column'               => [
			'spacing'                => [
				'padding' => true,
				'margin'  => true,
			],
			'__experimentalBorder'   => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'blockifyAnimation'      => true,
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyFilter'         => true,
			'blockifyTransform'      => true,
			'blockifyPosition'       => true,
			'blockifyNegativeMargin' => true,
		],
		'core/columns'              => [
			'typography'             => [
				'fontSize'   => true,
				'fontWeight' => true,
			],
			'blockifyAnimation'      => true,
			'blockifyBoxShadow'      => true,
			'blockifyPosition'       => true,
			'blockifyTransform'      => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
			'blockifyOnclick'        => true,
		],
		'core/cover'                => [
			'color'                => [
				'background' => true,
				'gradients'  => true,
				'text'       => true,
				'link'       => true,
				'overlay'    => true,
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
			'blockifyPosition'     => true,
		],
		'core/details'              => [
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
		'core/embed'                => [
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
		'core/gallery'              => [
			'spacing' => [
				'margin' => true,
			],
		],
		'core/group'                => [
			'blockifyAnimation'      => true,
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyOnclick'        => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
			'blockifyTransform'      => true,
			'blockifyDarkMode'       => true,
			'blockifyPosition'       => true,
		],
		'core/heading'              => [
			'align'                  => [
				'full',
				'wide',
				'none',
			],
			'alignWide'              => true,
			'color'                  => [
				'gradients'  => true,
				'background' => true,
				'text'       => true, // For SVG currentColor.
			],
			'spacing'                => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
			'blockifyNegativeMargin' => true,
			'blockifyAnimation'      => true,
			'blockifyBoxShadow'      => true,
			'blockifyPosition'       => true,
			'blockifyTransform'      => true,
			'blockifyFilter'         => true,
		],
		'core/html'                 => [
			'color'             => [
				'background' => true,
				'text'       => true,
				'link'       => true,
				'gradient'   => true,
			],
			'blockifyPosition'  => true,
			'blockifyTransform' => true,
			'blockifyFilter'    => true,
		],
		'core/image'                => [
			'__experimentalBorder'   => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'color'                  => [
				'gradients'  => true,
				'background' => true,
				'text'       => true, // For SVG currentColor.
			],
			'spacing'                => [
				'margin'  => true,
				'padding' => true,
			],
			'typography'             => [
				'fontSize' => true, // Used by icons.
			],
			'blockifyAnimation'      => true,
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyFilter'         => true,
			'blockifyIcon'           => true,
			'blockifyNegativeMargin' => true,
			'blockifyPosition'       => true,
			'blockifyTransform'      => true,
			'blockifyOnclick'        => true,
		],
		'core/list'                 => [
			'spacing'              => [
				'padding'  => true,
				'margin'   => true,
				'blockGap' => true,
			],
			'__experimentalLayout' => [
				'allowSwitching'  => false,
				'allowInheriting' => false,
				'default'         => [
					'type'        => 'flex',
					'orientation' => 'vertical',
				],
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
			'blockifyPosition'     => true,
			'blockifyShadow'       => true,
		],
		'core/list-item'            => [
			'color'                => [
				'text'       => true,
				'background' => true,
				'link'       => true,
				'gradient'   => true,
			],
			'spacing'              => [
				'padding' => true,
				'margin'  => true,
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
			'blockifyBoxShadow'    => true,
		],
		'core/media-text'           => [
			'__experimentalBorder' => [
				'radius' => true,
			],
			'spacing'              => [
				'margin' => true,
			],
			'blockifyPosition'     => true,
		],
		'core/navigation'           => [
			'spacing'              => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
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
			'blockifyPosition'     => true,
			'blockifyFilter'       => true,
		],
		'core/navigation-submenu'   => [
			'spacing'              => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
			'color'                => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
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
		'core/page-list'            => [
			'spacing' => [
				'blockGap' => true,
			],
		],
		'core/paragraph'            => [
			'align'                  => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide'              => true,
			'color'                  => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'__experimentalBorder'   => [
				'radius'                        => true,
				'width'                         => true,
				'color'                         => true,
				'style'                         => true,
				'__experimentalDefaultControls' => [
					'width' => true,
					'color' => true,
				],
			],
			'spacing'                => [
				'margin'  => true,
				'padding' => true,
			],
			'blockifyAnimation'      => true,
			'blockifyBoxShadow'      => true,
			'blockifyNegativeMargin' => true,
			'blockifyPosition'       => true,
			'blockifyTransform'      => true,
			'blockifyFilter'         => true,
		],
		'core/pattern'              => [
			'className' => true,
		],
		'core/post-content'         => [
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
		'core/post-author'          => [
			'spacing'              => [
				'blockGap' => true,
			],
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
		'core/post-excerpt'         => [
			'__experimentalLayout' => [
				'allowSwitching'  => false,
				'allowInheriting' => false,
				'default'         => [
					'type' => 'flex',
				],
			],
		],
		'core/post-date'            => [
			'spacing' => [
				'margin' => true,
			],
		],
		'core/post-featured-image'  => [
			'align'             => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide'         => true,
			'color'             => [
				'background' => true,
			],
			'spacing'           => [
				'margin'  => true,
				'padding' => true,
			],
			'blockifyBoxShadow' => true,
			'blockifyPosition'  => true,
		],
		'core/post-terms'           => [
			'align'                => [
				'full',
				'wide',
				'left',
				'center',
				'right',
				'none',
			],
			'alignWide'            => true,
			'spacing'              => [
				'padding'  => true,
				'margin'   => true,
				'blockGap' => true,
			],
			'__experimentalBorder' => [
				'radius' => true,
			],
		],
		'core/post-title'           => [
			'spacing'              => [
				'padding' => true,
				'margin'  => true,
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
		'core/pullquote'            => [
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
		'core/query'                => [
			'spacing'          => [
				'padding' => true,
				'margin'  => true,
			],
			'blockifyPosition' => true,
		],
		'core/query-pagination'     => [
			'spacing' => [
				'margin'  => true,
				'padding' => true,
			],
		],
		'core/quote'                => [
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
		'core/row'                  => [
			'blockifyBoxShadow' => true,
			'blockifyPosition'  => true,
		],
		'core/search'               => [
			'spacing'           => [
				'padding'  => true,
				'margin'   => true,
				'blockGap' => true,
			],
			'blockifyBoxShadow' => true,
			'blockifyPosition'  => true,
		],
		'core/separator'            => [
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
		'core/site-logo'            => [
			'color'                => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'__experimentalBorder' => [
				'radius'                        => true,
				'width'                         => false,
				'color'                         => false,
				'style'                         => false,
				'__experimentalDefaultControls' => [
					'width' => false,
					'color' => false,
				],
			],
		],
		'core/stack'                => [
			'blockifyPosition' => true,
		],
		'core/social-links'         => [
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
			'__experimentalLayout' => [
				'allowSwitching'  => false,
				'allowInheriting' => true,
				'default'         => [
					'type'           => 'flex',
					'justifyContent' => 'space-between',
					'orientation'    => 'horizontal',
				],
			],
			'blockifyPosition'     => true,
		],
		'core/social-link'          => [
			'color' => [
				'background' => false,
				'text'       => true,
			],
		],
		'core/spacer'               => [
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
				'gradients'  => true,
				'background' => true,
				'text'       => true,
			],
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
			'blockifyAnimation'    => true,
			'blockifyBoxShadow'    => true,
			'blockifyPosition'     => true,
			'blockifyFilter'       => true,
			'blockifyTransform'    => true,
			'blockifyOnclick'      => true,
		],
		'core/table-of-contents'    => [
			'spacing' => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
		],
		'core/tag-cloud'            => [
			'typography' => [
				'textTransform' => true, // Doesn't work.
				'letterSpacing' => true, // Doesn't work.
			],
		],
		'core/template-part'        => [
			'blockifyBoxShadow' => true,
			'color'             => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'blockifyPosition'  => true,
		],
		'core/video'                => [
			'color'                => [
				'gradients'  => true,
				'background' => true,
				'text'       => true,
			],
			'spacing'              => [
				'margin' => true, // Doesn't work.
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
			'blockifyBoxShadow'    => true,
			'blockifyFilter'       => true,
			'blockifyPosition'     => true,
			'blockifyTransform'    => true,
		],
	];

	return $data;
}
