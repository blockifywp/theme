import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { reusableBlock } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { CSSProperties } from 'react';
import {
	InspectorControls,
} from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	ToggleControl,
	// @ts-ignore
	__experimentalNumberControl as NumberControl, Flex, FlexItem, FlexBlock, RangeControl,
} from '@wordpress/components';

const blockVariation: BlockVariation = {
	name: 'marquee',
	icon: reusableBlock,
	title: __( 'Marquee', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'design',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Adds a horizontal infinite scrolling marquee banner.', 'blockify' ),
	innerBlocks: [
		[
			'core/group',
			{
				layout: {
					type: 'flex',
					flexWrap: 'nowrap',
					orientation: 'horizontal',
					justifyContent: 'center',
				},
			},
			[
				[
					'core/paragraph',
				],
			],
		],
	],
	attributes: {
		marquee: {},
		align: 'full',
		speedMobile: 60,
		speedDesktop: 90,
		pauseOnHover: true,
		reverse: false,
		spacing: {
			padding: {
				right: '0',
				left: '0',
			},
		},
		layout: {
			type: 'flex',
			flexWrap: 'nowrap',
			orientation: 'marquee',
			justifyContent: 'center',
		},
	},
	isActive: ( blockAttributes, variationAttributes ) => {
		return blockAttributes.layout?.orientation === variationAttributes.layout?.orientation;
	},
};

registerBlockVariation( 'core/group', blockVariation );

const getStyles = ( attributes: attributes ): CSSProperties => {
	return {
		'--marquee-speed-mobile': ( attributes?.speedMobile ?? 20 ) + 's',
		'--marquee-speed-desktop': ( attributes?.speedDesktop ?? 30 ) + 's',
		'--marquee-direction': attributes?.reverse ? 'reverse' : 'forwards',
		'--marquee-pause': attributes?.pauseOnHover ? 'paused' : 'running',
	} as CSSProperties;
};

addFilter(
	'blocks.registerBlockType',
	'blockify/marquee-attributes',
	( props, name: string ): void => {
		if ( name === 'core/group' ) {
			props = {
				...props,
				attributes: {
					...props.attributes,
					speedMobile: {
						type: 'string',
					},
					speedDesktop: {
						type: 'string',
					},
					reverse: {
						type: 'boolean',
					},
					pauseOnHover: {
						type: 'boolean',
					},
					repeatItems: {
						type: 'number',
					},
				},
			};
		}

		return props;
	},
	0
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-marquee-controls',
	createHigherOrderComponent( ( BlockEdit: any ) => ( props: blockProps ) => {
		const { attributes, setAttributes } = props;

		if ( attributes?.layout?.orientation !== 'marquee' ) {
			return <BlockEdit { ...props } />;
		}

		return (
			<>
				<InspectorControls>
					<PanelBody
						title={ __( 'Marquee Settings', 'blockify-pro' ) }
						className={ __( 'blockify-width-control', 'blockify-pro' ) }
					>
						<p>{ __( 'Scroll Speed (seconds)', 'blockify' ) }</p>
						<PanelRow>
							<br />
							<Flex>
								<FlexItem style={ { width: '50%' } }>
									<NumberControl
										isShiftStepEnabled={ true }
										label={ __( 'Mobile', 'blockify' ) }
										onChange={ ( value: string ) => {
											setAttributes( { speedMobile: value } );
										} }
										value={ attributes?.speedMobile }
									/>
								</FlexItem>
								<FlexBlock>
									<NumberControl
										isShiftStepEnabled={ true }
										label={ __( 'Desktop', 'blockify-pro' ) }
										onChange={ ( value: string ) => {
											setAttributes( {
												speedDesktop: value,
											} );
										} }
										value={ attributes?.speedDesktop }
									/>
								</FlexBlock>
							</Flex>
						</PanelRow>
						<br />
						<PanelRow>
							<RangeControl
								label={ __( 'Repeat Items', 'blockify' ) }
								help={ __( 'How many times should the items be duplicated and cloned.', 'blockify' ) }
								value={ attributes?.repeatItems ?? 2 }
								onChange={ ( value: number ) => {
									setAttributes( {
										repeatItems: value,
									} );
								} }
								min={ 0 }
								max={ 10 }
								step={ 1 }
								allowReset={ true }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __( 'Pause on hover', 'blockify-pro' ) }
								checked={ attributes?.pauseOnHover }
								onChange={ () => setAttributes( {
									pauseOnHover: ! attributes?.pauseOnHover,
								} ) }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __( 'Reverse direction', 'blockify-pro' ) }
								checked={ attributes?.reverse }
								onChange={ () => setAttributes( {
									reverse: ! attributes?.reverse,
								} ) }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<BlockEdit { ...props } />
			</>
		);
	}, 'withInspectorControl' ),
	9
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-marquee',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			const { attributes } = props;

			if ( attributes?.layout?.orientation !== 'marquee' ) {
				return <BlockListBlock { ...props } />;
			}

			props.attributes.style = {
				...attributes.style ?? {},
				...getStyles( attributes ),
			};

			const wrapperProps = {
				...props.wrapperProps,
				style: {
					...props.wrapperProps?.style ?? {},
					...getStyles( attributes ),
				},
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		},
		'withMarquee'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-marquee-styles',
	( props, block, attributes ): object => {
		if ( attributes?.layout?.orientation !== 'marquee' ) {
			return props;
		}

		props.style = {
			...props?.style,
			...getStyles( attributes ),
		};

		return props;
	}
);
