<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( SLUG . '_editor_script', NS . 'register_block_supports' );
/**
 * Add default block supports.
 *
 * @todo: Move to rest endpoint.
 *
 * @since 0.9.10
 *
 * @param array $config Blockify editor config.
 *
 * @return array
 */
function register_block_supports( array $config = [] ): array {
	$config['blockSupports'] = [
		'blockify/accordion'        => [
			'blockifyBoxShadow' => true,
		],
		'blockify/tabs'             => [
			'blockifyBoxShadow' => true,
		],
		'blockify/dark-mode-toggle' => [
			'color' => [
				'background' => true,
				'text'       => true,
				'link'       => true,
				'gradient'   => true,
			],
		],
		'core/buttons'              => [
			'spacing'            => [
				'padding'  => true, // Required.
				'margin'   => true,
				'blockGap' => true,
			],
			'blockifyResponsive' => true,
		],
		'core/button'               => [
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
			'blockifyOnclick'      => true,
			'blockifyResponsive'   => true,
		],
		'core/code'                 => [
			'blockifyBoxShadow' => true,
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
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyFilter'         => true,
			'blockifyTransform'      => true,
			'blockifyResponsive'     => true,
			'blockifyNegativeMargin' => true,
		],
		'core/columns'              => [
			'typography'             => [
				'fontSize'   => true,
				'fontWeight' => true,
			],
			'blockifyBoxShadow'      => true,
			'blockifyResponsive'     => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
		],
		'core/cover'                => [
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
			'blockifyResponsive'   => true,
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
			'spacing'                => true,
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyMinHeight'      => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
			'blockifyTransform'      => true,
			'blockifyDarkMode'       => true,
			'blockifyResponsive'     => true,
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
				'margin'  => true,
				'padding' => true,
			],
			'blockifyNegativeMargin' => true,
			'blockifyAnimation'      => true,
			'blockifyBoxShadow'      => true,
			'blockifyResponsive'     => true,
			'blockifyTransform'      => true,
			'blockifyFilter'         => true,
		],
		'core/html'                 => [
			'color'              => [
				'background' => true,
				'text'       => true,
				'link'       => true,
				'gradient'   => true,
			],
			'blockifyResponsive' => true,
			'blockifyTransform'  => true,
			'blockifyFilter'     => true,
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
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyFilter'         => true,
			'blockifyIcon'           => true,
			'blockifyNegativeMargin' => true,
			'blockifyResponsive'     => true,
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
		],
		'core/list-item'            => [
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
		'core/media-text'           => [
			'__experimentalBorder' => [
				'radius' => true,
			],
			'spacing'              => [
				'margin' => true,
			],
			'blockifyResponsive'   => true,
		],
		'core/navigation'           => [
			'spacing'            => [
				'margin'   => true,
				'padding'  => true,
				'blockGap' => true,
			],
			'blockifyResponsive' => true,
		],
		'core/navigation-submenu'   => [
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
			'blockifyResponsive'     => true,
			'blockifyTransform'      => true,
			'blockifyFilter'         => true,
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
			'blockifyBoxShadow' => true,
		],
		'core/post-terms'           => [
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
		'core/post-title'           => [
			'spacing' => [
				'padding' => true,
				'margin'  => true,
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
			'spacing' => [
				'padding'  => true,
				'blockGap' => true,
			],
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
			'blockifyBoxShadow'  => true,
			'blockifyResponsive' => true,
		],
		'core/search'               => [
			'blockifyBoxShadow'  => true,
			'blockifyResponsive' => true,
			'spacing'            => [
				'padding' => true,
				'margin'  => true,
			],
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
			'blockifyResponsive' => true,
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
			'blockifyBoxShadow'    => true,
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
			'blockifyResponsive'   => true,
			'blockifyFilter'       => true,
		],
		'core/tag-cloud'            => [
			'typography' => [
				'textTransform' => true, // Doesn't work.
				'letterSpacing' => true, // Doesn't work.
			],
		],
		'core/template-part'        => [
			'blockifyBoxShadow'  => true,
			'color'              => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'blockifyResponsive' => true,
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
			'blockifyResponsive'   => true,
			'blockifyTransform'    => true,
		],
	];

	return $config;
}
