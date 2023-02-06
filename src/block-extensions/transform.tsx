import {
	// @ts-ignore
	__experimentalUnitControl as UnitControl,
	// @ts-ignore
	__experimentalNumberControl as NumberControl,
	Flex,
	FlexItem, PanelRow, Button, PanelBody,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Label } from "../components/label";
import { trash } from "@wordpress/icons";
import { CSSProperties } from "react";
import { InspectorControls } from "@wordpress/block-editor";

export const supportsTransform = ( name: string ): boolean => window?.blockify?.blockSupports?.[name]?.blockifyTransform ?? false;

interface transformTypes {
	[name: string]: string
}

const transformTypes: transformTypes = {
	rotate: 'deg',
	rotateX: 'deg',
	rotateY: 'deg',
	scale: '',
	scaleX: '',
	scaleY: '',
	skew: 'deg',
	skewX: 'deg',
	skewY: 'deg',
	translateX: '',
	translateY: '',
	translateZ: '',
};

addFilter(
	'blocks.registerBlockType',
	'blockify/add-css-transform-attributes',
	( props, name ) => {
		if ( ! supportsTransform( name ) ) {
			return props;
		}

		props.attributes = {
			...props.attributes,
			style: {
				...( props?.attributes?.style ?? {} ),
				transform: {
					type: 'string',
				}
			}
		}

		return props;
	}
);

const getStyles = ( transform: { [name: string]: string } ): CSSProperties => {
	let styles = '';

	Object.keys( transformTypes ).forEach( ( type: string ) => {
		if ( transform?.[type] ) {
			const amount = transform[type];
			const unit   = transformTypes[type];
			styles += ` ${ type }(${ amount }${ unit })`;
		}
	} );

	return styles ? {
		transform: styles.trim(),
	} : {};
}

addFilter(
	'editor.BlockListBlock',
	'blockify/with-css-transform',
	createHigherOrderComponent( BlockListBlock => {
			return ( props: blockProps ) => {
				const { attributes, name } = props;

				const defaultReturn = <BlockListBlock { ...props } />;

				if ( ! supportsTransform( name ) ) {
					return defaultReturn;
				}

				const { style } = attributes;
				const transform = style?.transform ?? {};

				if ( ! transform ) {
					return defaultReturn;
				}

				const styles: CSSProperties = getStyles( transform );

				if ( ! Object.keys( styles ).length ) {
					return defaultReturn;
				}

				props.style = {
					...props?.style,
					...styles
				};

				const wrapperProps = {
					...props.wrapperProps,
					style: {
						...props.wrapperProps?.style,
						...styles,
					},
				}

				return <BlockListBlock { ...props } wrapperProps={ wrapperProps }/>
			};
		},
		'withCssTransform'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/apply-css-transform-styles',
	( props, block, attributes ) => {
		const { name } = block;

		if ( ! supportsTransform( name ) ) {
			return props;
		}

		const { style } = attributes;
		const transform = style?.transform ?? {};

		if ( ! transform ) {
			return props;
		}

		const styles = getStyles( transform );

		if ( ! Object.keys( styles ).length ) {
			return props;
		}

		return {
			...props,
			style: {
				...props?.style,
				...styles,
			}
		}
	}
);

export const Transform = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const { style }                     = attributes;
	const transform                     = style?.transform ?? {};

	return (
		<>
			<PanelRow>
				<Label>
					<>
						{ __( 'Transform', 'blockify' ) }
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => {
								setAttributes( {
									style: {
										...attributes?.style,
										transform: ''
									}
								} );
							} }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Clear Transforms', 'blockify' ) }
						/>
					</>
				</Label>
			</PanelRow>
			<Flex className={ 'blockify-flex-controls' }>
				<FlexItem>
					<NumberControl
						label={ __( 'Rotate', 'blockify' ) }
						value={ transform?.rotate }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										rotate: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>

				<FlexItem>
					<NumberControl
						label={ __( 'Rotate X', 'blockify' ) }
						value={ transform?.rotateX }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										rotateX: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>
				<FlexItem>
					<NumberControl
						label={ __( 'Rotate Y', 'blockify' ) }
						value={ transform?.rotateY }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										rotateY: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>

			</Flex>


			<Flex className={ 'blockify-flex-controls' }>
				<FlexItem>
					<NumberControl
						label={ __( 'Scale', 'blockify' ) }
						value={ transform?.scale }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										scale: value,
									}
								}
							} )
						} }
						min={ 0 }
						max={ 10 }
						step={ 0.1 }
					/>
				</FlexItem>
				<FlexItem>
					<NumberControl
						label={ __( 'Scale X', 'blockify' ) }
						value={ transform?.scaleX }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										scaleX: value,
									}
								}
							} )
						} }
						min={ 0 }
						max={ 10 }
						step={ 0.1 }
					/>
				</FlexItem>
				<FlexItem>
					<NumberControl
						label={ __( 'Scale Y', 'blockify' ) }
						value={ transform?.scaleY }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										scaleY: value,
									}
								}
							} )
						} }
						min={ 0 }
						max={ 10 }
						step={ 0.1 }
					/>
				</FlexItem>

			</Flex>

			<Flex className={ 'blockify-flex-controls' }>

				<FlexItem>
					<NumberControl
						label={ __( 'Skew', 'blockify' ) }
						value={ transform?.skew }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										skew: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>

				<FlexItem>
					<NumberControl
						label={ __( 'Skew X', 'blockify' ) }
						value={ transform?.skewX }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										skewX: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>

				<FlexItem>
					<NumberControl
						label={ __( 'Skew Y', 'blockify' ) }
						value={ transform?.skewY }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										skewY: value,
									}
								}
							} )
						} }
						min={ -360 }
						max={ 360 }
						step={ 1 }
					/>
				</FlexItem>

			</Flex>

			<Flex className={ 'blockify-flex-controls' }>

				<FlexItem>
					<UnitControl
						label={ __( 'Translate X', 'blockify' ) }
						value={ transform?.translateX }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										translateX: value,
									}
								}
							} )
						} }
					/>
				</FlexItem>

				<FlexItem>
					<UnitControl
						label={ __( 'Translate Y', 'blockify' ) }
						value={ transform?.translateY }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										translateY: value,
									}
								}
							} )
						} }
					/>
				</FlexItem>

				<FlexItem>
					<UnitControl
						label={ __( 'Translate Z', 'blockify' ) }
						value={ transform?.translateZ }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									transform: {
										...transform,
										translateZ: value,
									}
								}
							} )
						} }
					/>
				</FlexItem>
			</Flex>
			<br/>
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/transform-controls',
	createHigherOrderComponent( BlockEdit => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsTransform( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected &&
					  <InspectorControls>
						  <PanelBody
							  initialOpen={ attributes?.transform ?? false }
							  title={ __( 'Transform', 'blockify' ) }
						  >
							  <Transform { ...props }/>
						  </PanelBody>
					  </InspectorControls>
					}
				</>
			);
		}
	}, 'withTransform' )
);
