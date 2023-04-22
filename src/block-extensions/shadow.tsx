import { __ } from '@wordpress/i18n';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import {
	PanelRow,
	Flex,
	FlexItem,
	ToggleControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
	ButtonGroup,
	Button,
	PanelBody,
} from '@wordpress/components';

import {
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalPanelColorGradientSettings as PanelColorGradientSettings,
	InspectorControls,
} from '@wordpress/block-editor';
import { ucWords } from '../utility/string';
import { Label } from '../components/label';
import { useState } from '@wordpress/element';
import { trash } from '@wordpress/icons';

export const supportsShadow = ( name: string ): boolean =>
	window?.blockify?.blockSupports?.[ name ]?.blockifyBoxShadow ?? false;

addFilter(
	'blocks.registerBlockType',
	'blockify/add-box-shadow-attributes',
	( props, name ): object => {
		if ( supportsShadow( name ) ) {
			props.attributes = {
				...props.attributes,
				style: {
					...( props?.attributes?.style ?? {} ),
					boxShadow: {
						type: 'object',
					},
				},
			};
		}

		return props;
	},
	0
);

const getStyles = ( attributes: attributes ): style => {
	const boxShadow = attributes?.style?.boxShadow ?? {};

	const style: { [key: string]: string } = {};

	const units: { [property: string]: string } = {
		inset: '',
		x: 'px',
		y: 'px',
		blur: 'px',
		spread: 'px',
		color: '',
	};

	Object.keys( units ).map( ( key: string ) => {
		if ( boxShadow?.[ key ] || boxShadow?.[ key ]?.toString() === '0' ) {
			style[ '--wp--custom--box-shadow--' + key ] =
				boxShadow?.[ key ] + units?.[ key ];
		}

		if (
			boxShadow?.hover?.[ key ] ||
			boxShadow?.hover?.[ key ]?.toString() === '0'
		) {
			style[ '--wp--custom--box-shadow--hover--' + key ] =
				boxShadow?.hover?.[ key ] + units?.[ key ];
		}

		return true;
	} );

	return style;
};

const BoxShadowControl = ( props: blockProps, tab: string ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;

	const boxShadow = style?.boxShadow ?? {};

	const setBoxShadow = ( values: { [property: string]: string | boolean } ) => {
		let newAttributes;

		if ( tab === 'default' ) {
			newAttributes = {
				...values,
			};
		} else {
			newAttributes = {
				hover: {
					...boxShadow?.hover,
					...values,
				},
			};
		}

		setAttributes( {
			style: {
				...style,
				boxShadow: {
					...boxShadow,
					...newAttributes,
				},
			},
		} );
	};

	return (
		<>
			<PanelRow>
				<Flex>
					{ [ 'x', 'y', 'blur', 'spread' ].map( ( key ) => (
						<FlexItem key={ key }>
							<NumberControl
								label={ ucWords( key ) }
								value={
									tab === 'default'
										? boxShadow[ key ]
										: boxShadow?.hover?.[ key ]
								}
								step={ 1 }
								shiftStep={ 10 }
								onChange={ ( value: string ) => {
									setBoxShadow( {
										[ key ]: value,
									} );
								} }
							/>
						</FlexItem>
					) ) }
				</Flex>
			</PanelRow>

			<br />
			<PanelRow>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem
						style={ {
							flex: 1.5,
						} }
					>
						<PanelColorGradientSettings
							title={ __( 'Color', 'blockify' ) }
							showTitle={ false }
							enableAlpha={ true }
							settings={ [
								{
									enableAlpha: true,
									colorValue:
										tab === 'default'
											? boxShadow?.color
											: boxShadow?.[ tab ]?.color,
									label:
										__( 'Color ', 'blockify' ) +
										( tab === 'hover'
											? __( ' Hover', 'blockify' )
											: '' ),
									onColorChange: ( value: string ) => {
										setBoxShadow( {
											color: value,
										} );
									},
								},
							] }
						/>
					</FlexItem>
					<FlexItem>
						<ToggleControl
							label={ __( 'Inset', 'blockify' ) }
							checked={
								tab === 'default'
									? boxShadow?.inset
									: boxShadow?.[ tab ]?.inset
							}
							onChange={ ( value ) => {
								setBoxShadow( {
									inset: value ? 'inset' : '',
								} );
							} }
						/>
					</FlexItem>
				</Flex>
			</PanelRow>
		</>
	);
};

addFilter(
	'editor.BlockListBlock',
	'blockify/edit-box-shadow-styles',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { attributes, name } = props;

			if ( ! supportsShadow( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			const styles = getStyles( attributes );

			if ( ! Object.keys( styles ).length ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = { ...props.wrapperProps };

			const className = ( props?.className ?? '' ) + ' has-box-shadow';

			props = {
				...props,
				className,
				style: {
					...props.style,
					...styles,
				},
			};

			wrapperProps.className += ' has-box-shadow';

			wrapperProps.style = {
				...wrapperProps.style,
				...styles,
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		};
	}, 'withBoxShadow' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-box-shadow-styles',
	( props, block, attributes ): blockProps => {
		const { name } = block;

		if ( ! supportsShadow( name ) ) {
			return props;
		}

		const styles = getStyles( attributes );

		if ( ! Object.keys( styles ).length ) {
			return props;
		}

		props.className += ' has-box-shadow';

		props.style = {
			...props.style,
			...styles,
		};

		return props;
	}
);

export const Shadow = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const [ tab, setTab ] = useState( 'default' );

	return (
		<>
			<PanelRow>
				<Label>
					<>
						{ __( 'Shadow', 'blockify' ) }
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => {
								setAttributes( {
									style: {
										...attributes?.style,
										boxShadow: '',
									},
								} );
							} }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Clear Shadow', 'blockify' ) }
						/>
					</>
				</Label>
				<ButtonGroup>
					<Button
						isSmall
						variant={ tab === 'default' ? 'primary' : 'secondary' }
						onClick={ () => setTab( 'default' ) }
					>
						{ __( 'Default', 'blockify' ) }
					</Button>
					<Button
						isSmall
						variant={ tab === 'hover' ? 'primary' : 'secondary' }
						onClick={ () => setTab( 'hover' ) }
					>
						{ __( 'Hover', 'blockify' ) }
					</Button>
				</ButtonGroup>
			</PanelRow>
			{ tab === 'default' && BoxShadowControl( props, tab ) }
			{ tab === 'hover' && BoxShadowControl( props, tab ) }
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/shadow-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsShadow( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && (
						<InspectorControls>
							<PanelBody
								initialOpen={ attributes?.shadow ?? false }
								title={ __( 'Shadow', 'blockify' ) }
							>
								<Shadow { ...props } />
							</PanelBody>
						</InspectorControls>
					) }
				</>
			);
		};
	}, 'withShadow' )
);
