import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { reusableBlock } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { CSSProperties, SyntheticEvent } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import {
	__experimentalNumberControl as NumberControl,
	__experimentalVStack as VStack,
	Flex,
	FlexBlock,
	FlexItem,
	PanelBody,
	PanelRow,
	RangeControl,
	ToggleControl,
} from '@wordpress/components';
import { Label } from '../components';
import { addClassName } from '../utility/css.tsx';

const defaultMobileSpeed = '60';
const defaultDesktopSpeed = '90';

const blockVariation: BlockVariation = {
	name: 'marquee',
	icon: reusableBlock,
	title: __( 'Marquee', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'design',
	scope: [ 'inserter' ],
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
		speedMobile: defaultMobileSpeed,
		speedDesktop: defaultDesktopSpeed,
		pauseOnHover: true,
		reverse: false,
		fadeEdges: false,
		direction: 'horizontal',
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
		'--marquee-speed-mobile': ( attributes?.speedMobile ?? defaultMobileSpeed ) + 's',
		'--marquee-speed-desktop': ( attributes?.speedDesktop ?? defaultDesktopSpeed ) + 's',
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
					fadeEdges: {
						type: 'boolean',
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

		const Settings = () => <>
			<PanelRow>
				<VStack>
					<Label>
						{ __( 'Scroll Speed (seconds)', 'blockify' ) }
					</Label>
					<Flex>
						<FlexItem style={ { width: '50%' } }>
							<NumberControl
								isShiftStepEnabled={ true }
								label={ __( 'Mobile', 'blockify' ) }
								onChange={ ( value: string | undefined, extra: {
									event: SyntheticEvent;
								} ) => {
									if ( ! extra?.event?.target?.validity?.valid ) {
										return;
									}

									setAttributes( {
										speedMobile: value,
									} );
								} }
								value={ attributes?.speedMobile ?? defaultMobileSpeed }
							/>
						</FlexItem>
						<FlexBlock>
							<NumberControl
								isShiftStepEnabled={ true }
								label={ __( 'Desktop', 'blockify-pro' ) }
								onChange={ ( value: string | undefined, extra: {
									event: SyntheticEvent;
								} ) => {
									if ( ! extra?.event?.target?.validity?.valid ) {
										return;
									}

									setAttributes( {
										speedDesktop: value,
									} );
								} }
								value={ attributes?.speedDesktop ?? defaultDesktopSpeed }
							/>
						</FlexBlock>
					</Flex>
				</VStack>
			</PanelRow>
			<PanelRow>
				<RangeControl
					label={ __( 'Repeat Items', 'blockify' ) }
					help={ __( 'How many times should the items be duplicated/cloned.', 'blockify' ) }
					value={ attributes?.repeatItems ?? 2 }
					onChange={ ( value: number | undefined ) => {
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
			<PanelRow>
				<ToggleControl
					label={ __( 'Fade Edges', 'blockify-pro' ) }
					checked={ attributes?.fadeEdges }
					onChange={ () => setAttributes( {
						fadeEdges: ! attributes?.fadeEdges,
					} ) }
				/>
			</PanelRow>
		</>;

		return <>
			<InspectorControls>
				<PanelBody
					title={ __( 'Marquee Settings', 'blockify-pro' ) }
					className={ 'blockify-width-control' }
				>
					<Settings />
				</PanelBody>
			</InspectorControls>
			<BlockEdit { ...props } />
		</>;
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

			let className = props.className;

			if ( attributes?.fadeEdges ) {
				className = addClassName( className, 'fade-edges' );

				wrapperProps.className = className;
			}

			return <BlockListBlock { ...{
				...props,
				className,
			} } wrapperProps={ wrapperProps } />;
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

		props = {
			...props,
			style: {
				...props?.style,
				...getStyles( attributes ),
			},
		};

		if ( attributes?.fadeEdges ) {
			props.className = addClassName( props.className, 'fade-edges' );
		}

		return props;
	}
);
