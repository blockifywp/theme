<?php

declare( strict_types=1 );

namespace Blockify\Theme;

use function add_filter;

add_filter( 'blockify', NS . 'add_block_supports' );
/**
 * Add block support config.
 *
 * @todo  Move to rest endpoint.
 * @since 0.4.0
 *
 * @param array $config Default config.
 *
 * @return array
 */
function add_block_supports( array $config ): array {
	$config['blockSupports'] = [
		'blockify/accordion'       => [
			'blockifyBoxShadow' => true,
		],
		'blockify/email'           => [
			'blockifyBoxShadow' => true,
		],
		'blockify/icon'            => [
			'blockifyBoxShadow' => true,
		],
		'blockify/newsletter'      => [
			'blockifyBoxShadow' => true,
		],
		'blockify/submit'          => [
			'blockifyBoxShadow' => true,
		],
		'blockify/popup'           => [
			'blockifyBoxShadow' => true,
		],
		'blockify/tabs'            => [
			'blockifyBoxShadow' => true,
		],
		'blockify/text-area'       => [
			'minHeight' => '6em',
		],
		'core/buttons'             => [
			'spacing' => [
				'padding'  => true, // Required.
				'margin'   => true,
				'blockGap' => true,
			],
		],
		'core/button'              => [
			'typography'           => [
				'fontSize'   => true,
				'fontWeight' => true,
				'fontFamily' => true,
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
		'core/code'                => [
			'blockifyBoxShadow' => true,
		],
		'core/column'              => [
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
			'blockifyPosition'       => true,
			'blockifyNegativeMargin' => true,
			'blockifyResponsive'     => true,
		],
		'core/columns'             => [
			'typography'             => [
				'fontSize'   => true,
				'fontWeight' => true,
			],
			'blockifyBoxShadow'      => true,
			'blockifyPosition'       => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
		],
		'core/cover'               => [
			'blockifyPosition' => true,
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
			'spacing'                => true,
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyPosition'       => true,
			'blockifyMinHeight'      => true,
			'blockifyNegativeMargin' => true,
			'blockifyFilter'         => true,
			'blockifyTransform'      => true,
		],
		'core/heading'             => [
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
			'blockifyWidth'          => true,
		],
		'core/image'               => [
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
			'blockifyBackground'     => true,
			'blockifyBoxShadow'      => true,
			'blockifyFilter'         => true,
			'blockifyIcon'           => true,
			'blockifyNegativeMargin' => true,
			'blockifyPosition'       => true,
			'blockifyTransform'      => true,
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
			'blockifyBoxShadow'      => true,
			'blockifyNegativeMargin' => true,
			'blockifyPosition'       => true,
			'blockifyWidth'          => true,
		],
		'core/page-list'           => [
			'spacing' => [
				'blockGap' => true,
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
			'blockifyBoxShadow' => true,
		],
		'core/search'              => [
			'blockifyBoxShadow' => true,
			'spacing'           => [
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
			'color'        => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'blockifyIcon' => true,
		],
		'core/social-links'        => [
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
			'blockifyPosition'     => true,
			'blockifyFilter'       => true,
		],
		'core/tag-cloud'           => [
			'typography' => [
				'textTransform' => true, // Doesn't work.
				'letterSpacing' => true, // Doesn't work.
			],
		],
		'core/template-part'       => [
			'blockifyBoxShadow' => true,
			'color'             => [
				'background' => true,
				'gradients'  => true,
				'link'       => true,
				'text'       => true,
			],
			'blockifyPosition'  => true,
		],
		'core/video'               => [
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
		],
	];

	return $config;
}
