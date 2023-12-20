import {
	__experimentalNumberControl as NumberControl,
	__experimentalUnitControl as UnitControl,
	Button,
	ButtonGroup,
	Flex,
	FlexItem,
	PanelBody,
	PanelRow,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Label } from '../components/label';
import { trash } from '@wordpress/icons';
import { InspectorControls } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { addClassName } from '../utility/css.tsx';

export const supportsTransform = ( name: string ): boolean => window?.blockify?.blockSupports?.[ name ]?.blockifyTransform ?? false;

interface transformTypes {
	[name: string]: string;
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
				},
				transformHover: {
					type: 'string',
				},
			},
		};

		return props;
	}
);

const getStyles = (
	transformDefault: {
		[name: string]: string;
	},
	transformHover: {
		[name: string]: string;
	}
): {
	[name: string]: string;
} => {
	const styles: {
		[name: string]: string;
	} = {};
	let defaultStyles = '';
	let hoverStyles = '';

	Object.keys( transformTypes ).forEach( ( type: string ) => {
		if ( transformDefault?.[ type ] ) {
			const amount = transformDefault[ type ];
			const unit = transformTypes[ type ];
			defaultStyles += ` ${ type }(${ amount }${ unit })`;
		}

		if ( transformHover?.[ type ] ) {
			const amount = transformHover[ type ];
			const unit = transformTypes[ type ];
			hoverStyles += ` ${ type }(${ amount }${ unit })`;
		}
	} );

	if ( defaultStyles ) {
		styles[ '--transform' ] = defaultStyles.trim();
	}

	if ( hoverStyles ) {
		styles[ '--transform-hover' ] = hoverStyles.trim();
	}

	return styles ?? {};
};

addFilter(
	'editor.BlockListBlock',
	'blockify/with-css-transform',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { attributes, name } = props;

			const defaultReturn = <BlockListBlock { ...props } />;

			if ( ! supportsTransform( name ) ) {
				return defaultReturn;
			}

			const { style } = attributes;
			const transformDefault = style?.transform ?? {};
			const transformHover = style?.transformHover ?? {};

			if ( ! transformDefault && ! transformHover ) {
				return defaultReturn;
			}

			const styles = getStyles( transformDefault, transformHover );

			if ( ! Object.keys( styles ).length ) {
				return defaultReturn;
			}

			props = {
				...props,
				style: {
					...props?.style,
					...styles,
				},
				className: addClassName( props?.className, 'has-transform' ),
			};

			const wrapperProps = {
				...props.wrapperProps,
				style: {
					...props.wrapperProps?.style,
					...styles,
				},
				className: addClassName( props?.wrapperProps?.className, 'has-transform' ),
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
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
		const transformDefault = style?.transform ?? {};
		const transformHover = style?.transformHover ?? {};

		if ( ! transformDefault && ! transformHover ) {
			return props;
		}

		const styles = getStyles( transformDefault, transformHover );

		if ( ! Object.keys( styles ).length ) {
			return props;
		}

		return {
			...props,
			style: {
				...props?.style,
				...styles,
			},
			className: addClassName( props?.className, 'has-transform' ),
		};
	}
);

export const Transform = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;
	const transform = style?.transform ?? {};
	const transformHover = style?.transformHover ?? {};

	const [ tab, setTab ] = useState<string>( 'default' );

	const activeTransform = tab === 'default' ? transform : transformHover;

	const setTransform = ( value: { [name: string]: string | undefined } ) => {
		const styleKey = tab === 'default' ? 'transform' : 'transformHover';

		setAttributes( {
			style: {
				...style,
				[ styleKey ]: {
					...( activeTransform ),
					...value,
				},
			},
		} );
	};

	return <>
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
									transform: '',
									transformHover: '',
								},
							} );
						} }
						icon={ trash }
						iconSize={ 16 }
						aria-label={ __( 'Clear Transforms', 'blockify' ) }
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
		<br />
		<Flex className={ 'blockify-flex-controls' }>
			<FlexItem>
				<NumberControl
					label={ __( 'Rotate', 'blockify' ) }
					value={ activeTransform?.rotate }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							rotate: value,
						} );
					} }
					min={ -360 }
					max={ 360 }
					step={ 1 }
				/>
			</FlexItem>

			<FlexItem>
				<NumberControl
					label={ __( 'Rotate X', 'blockify' ) }
					value={ activeTransform?.rotateX }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							rotateX: value,
						} );
					} }
					min={ -360 }
					max={ 360 }
					step={ 1 }
				/>
			</FlexItem>
			<FlexItem>
				<NumberControl
					label={ __( 'Rotate Y', 'blockify' ) }
					value={ activeTransform?.rotateY }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							rotateY: value,
						} );
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
					value={ activeTransform?.scale }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							scale: value,
						} );
					} }
					min={ 0 }
					max={ 10 }
					step={ 0.01 }
				/>
			</FlexItem>
			<FlexItem>
				<NumberControl
					label={ __( 'Scale X', 'blockify' ) }
					value={ activeTransform?.scaleX }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							scaleX: value,
						} );
					} }
					min={ 0 }
					max={ 10 }
					step={ 0.01 }
				/>
			</FlexItem>
			<FlexItem>
				<NumberControl
					label={ __( 'Scale Y', 'blockify' ) }
					value={ activeTransform?.scaleY }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							scaleY: value,
						} );
					} }
					min={ 0 }
					max={ 10 }
					step={ 0.01 }
				/>
			</FlexItem>

		</Flex>

		<Flex className={ 'blockify-flex-controls' }>

			<FlexItem>
				<NumberControl
					label={ __( 'Skew', 'blockify' ) }
					value={ activeTransform?.skew }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							skew: value,
						} );
					} }
					min={ -360 }
					max={ 360 }
					step={ 1 }
				/>
			</FlexItem>

			<FlexItem>
				<NumberControl
					label={ __( 'Skew X', 'blockify' ) }
					value={ activeTransform?.skewX }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							skewX: value,
						} );
					} }
					min={ -360 }
					max={ 360 }
					step={ 1 }
				/>
			</FlexItem>

			<FlexItem>
				<NumberControl
					label={ __( 'Skew Y', 'blockify' ) }
					value={ activeTransform?.skewY }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							skewY: value,
						} );
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
					value={ activeTransform?.translateX }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							translateX: value,
						} );
					} }
				/>
			</FlexItem>

			<FlexItem>
				<UnitControl
					label={ __( 'Translate Y', 'blockify' ) }
					value={ activeTransform?.translateY }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							translateY: value,
						} );
					} }
				/>
			</FlexItem>

			<FlexItem>
				<UnitControl
					label={ __( 'Translate Z', 'blockify' ) }
					value={ activeTransform?.translateZ }
					onChange={ ( value: string | undefined ) => {
						setTransform( {
							translateZ: value,
						} );
					} }
				/>
			</FlexItem>
		</Flex>
		<br />
	</>;
};

addFilter(
	'editor.BlockEdit',
	'blockify/transform-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
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
							<Transform { ...props } />
						</PanelBody>
					</InspectorControls>
					}
				</>
			);
		};
	}, 'withTransform' )
);
