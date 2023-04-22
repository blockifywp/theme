import parse from 'html-react-parser';
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import {
	__experimentalUnitControl as UnitControl,
	CustomSelectControl, Flex, FlexItem,
	PanelBody, PanelRow,
	SelectControl,
} from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import SelectOption = SelectControl.Option;
import CustomSelectOption = CustomSelectControl.Option;
import { starFilled } from '@wordpress/icons';
import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { defaultState } from '../api/icon-store';
import domReady from '@wordpress/dom-ready';

const supportsIcon = ( name: string ): boolean => name === 'core/image';

const iconAttributes = {
	iconSet: {
		type: 'string',
		default: 'wordpress',
	},
	iconName: {
		type: 'string',
		default: 'star-empty',
	},
	iconColor: {
		type: 'string',
	},
	iconGradient: {
		type: 'string',
	},
	iconSize: {
		type: 'string',
	},
	iconCustomSVG: {
		type: 'string',
	},
	iconSvgString: {
		type: 'string',
		default: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M9.706 8.646a.25.25 0 01-.188.137l-4.626.672a.25.25 0 00-.139.427l3.348 3.262a.25.25 0 01.072.222l-.79 4.607a.25.25 0 00.362.264l4.138-2.176a.25.25 0 01.233 0l4.137 2.175a.25.25 0 00.363-.263l-.79-4.607a.25.25 0 01.072-.222l3.347-3.262a.25.25 0 00-.139-.427l-4.626-.672a.25.25 0 01-.188-.137l-2.069-4.192a.25.25 0 00-.448 0L9.706 8.646zM12 7.39l-.948 1.921a1.75 1.75 0 01-1.317.957l-2.12.308 1.534 1.495c.412.402.6.982.503 1.55l-.362 2.11 1.896-.997a1.75 1.75 0 011.629 0l1.895.997-.362-2.11a1.75 1.75 0 01.504-1.55l1.533-1.495-2.12-.308a1.75 1.75 0 01-1.317-.957L12 7.39z" clip-rule="evenodd"> </path></svg>',
	},
};

const blockVariation: BlockVariation = {
	name: 'icon',
	icon: starFilled,
	title: __( 'Icon', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'media',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert a customizable SVG icon.', 'blockify' ),
	attributes: {
		className: 'is-style-icon',
		iconSet: 'wordpress',
		iconName: 'star-empty',
		iconSvgString: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M9.706 8.646a.25.25 0 01-.188.137l-4.626.672a.25.25 0 00-.139.427l3.348 3.262a.25.25 0 01.072.222l-.79 4.607a.25.25 0 00.362.264l4.138-2.176a.25.25 0 01.233 0l4.137 2.175a.25.25 0 00.363-.263l-.79-4.607a.25.25 0 01.072-.222l3.347-3.262a.25.25 0 00-.139-.427l-4.626-.672a.25.25 0 01-.188-.137l-2.069-4.192a.25.25 0 00-.448 0L9.706 8.646zM12 7.39l-.948 1.921a1.75 1.75 0 01-1.317.957l-2.12.308 1.534 1.495c.412.402.6.982.503 1.55l-.362 2.11 1.896-.997a1.75 1.75 0 011.629 0l1.895.997-.362-2.11a1.75 1.75 0 01.504-1.55l1.533-1.495-2.12-.308a1.75 1.75 0 01-1.317-.957L12 7.39z" clip-rule="evenodd"> </path></svg>',
	},
	isActive: ( blockAttributes ) => {
		return blockAttributes && blockAttributes?.className?.includes( 'is-style-icon' );
	},
};

domReady( () => {
	registerBlockVariation( 'core/image', blockVariation );
} );

addFilter(
	'blocks.registerBlockType',
	'blockify/add-icon-attributes',
	( props, name ): object => {
		if ( supportsIcon( name ) ) {
			for ( const [ key, value ] of Object.entries( iconAttributes ) ) {
				props.attributes[ key ] = {
					type: value.type,
				};
			}

			props.attributes = {
				...props.attributes,
				...iconAttributes,
			};
		}

		return props;
	},
	99
);

const addProLink = () => {
	const description = document.getElementsByClassName( 'block-editor-block-card__description' ).item( 0 );

	if ( ! description ) {
		return;
	}

	const existingLink = document.getElementsByClassName( 'blockify-pro-icon-link' ).item( 0 );

	if ( ! existingLink ) {
		description.innerHTML = description.innerHTML + __( ' Get more icons with ', 'blockify' ) + '<a href="https://blockifywp.com/pro/" class="blockify-pro-icon-link" target="_blank">Blockify Pro ↗</a>';
	}
};

addFilter(
	'editor.BlockEdit',
	'blockify/with-icon',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const {
				name,
				attributes,
				setAttributes,
				isSelected,
			} = props;

			const { className }: { [className: string]: string } = attributes;

			if ( ! className ) {
				return <BlockEdit { ...props } />;
			}

			if ( ! className?.includes( 'is-style-icon' ) ) {
				return <BlockEdit { ...props } />;
			}

			if ( ! supportsIcon( name ) ) {
				return <BlockEdit { ...props } />;
			}

			if ( isSelected ) {
				addProLink();
			}

			if ( ! attributes?.url ) {
				setAttributes( {
					url: '#',
				} );
			}

			if ( ! attributes?.iconSet ) {
				setAttributes( {
					iconSet: iconAttributes?.iconSet?.default,
				} );
			}

			if ( ! attributes?.iconName ) {
				setAttributes( {
					iconName: iconAttributes?.iconName?.default,
				} );
			}

			if ( ! attributes?.iconSvgString ) {
				setAttributes( {
					iconSvgString: iconAttributes?.iconSvgString?.default,
				} );
			}

			const setOptions: SelectOption[] = [];

			const { icons } = useSelect( ( select ) => {
				return {
					icons: select( 'blockify/icons' )?.getIcons(),
				};
			}, [] ) ?? defaultState;

			const allIconOptions: {
				[set: string]: CustomSelectOption[]
			} = {
				wordpress: [
					{
						name: iconAttributes?.iconSvgString?.default,
						key: iconAttributes?.iconName?.default,
					},
				],
			};

			Object.keys( icons ).forEach( ( iconSet: string ) => {
				let label = iconSet.split( '-' ).join( ' ' );

				label = 'wordpress' === label ? 'WordPress' : label;

				setOptions.push( {
					label,
					value: iconSet,
				} );

				allIconOptions[ iconSet ] = [];

				Object.keys( icons[ iconSet ] ).forEach( ( iconName: string ) => {
					if ( iconName !== attributes?.iconName ) {
						allIconOptions[ iconSet ].push(
							{
								name: parse( icons?.[ iconSet ]?.[ iconName ] ),
								key: iconName,
							}
						);
					}
				} );

				// Moves current icon to start of array.
				if ( icons?.[ iconSet ]?.[ attributes?.iconName ] ) {
					allIconOptions[ iconSet ].unshift( {
						name: parse( icons?.[ iconSet ]?.[ attributes?.iconName ] ),
						key: attributes?.iconName,
					} );
				}
			} );

			const IconPreview = () => {
				const currentIconSvg: string = allIconOptions[ attributes?.iconSet ]?.filter( ( option: CustomSelectOption ) => {
					return option?.key === attributes?.iconName;
				} )?.[ 0 ]?.name;

				return (
					<div className={ 'blockify-icon-preview' }>
						{ currentIconSvg && (
							<>
								{ currentIconSvg }
								<span>{ attributes?.iconName?.replace( '-', ' ' ) }</span>
							</>
						) }
					</div>
				);
			};

			return (
				<>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody
							title={ __( 'Icon Settings', 'blockify' ) }
							initialOpen={ true }
							className={ 'blockify-icon-settings' }
						>
							{ ! 1 &&
							<p>
								{ __( 'More icons available with the Blockify Pro add-on! ', 'blockify' ) }
								<a
									href="https://blockifywp.com/pro"
									target={ '_blank' } rel="noreferrer"
								>
									{ __( 'Learn more ↗', 'blockify' ) }
								</a>
							</p>
							}
							<SelectControl
								label={ __( 'Select Icon Set', 'blockify' ) }
								value={ attributes?.iconSet ?? iconAttributes?.iconSet.default }
								options={ setOptions }
								onChange={ ( value: string ) => setAttributes( {
									iconSet: value,
								} ) }
							/>
							<IconPreview />

							<CustomSelectControl
								label={ __( 'Select Icon', 'blockify' ) }
								options={ allIconOptions?.[ attributes?.iconSet ] ?? allIconOptions?.wordpress }
								value={ attributes?.iconSvgString ?? iconAttributes?.iconSvgString?.default }
								className={ 'blockify-icon-setting' }
								onChange={ ( { selectedItem } ) => {
									const key = selectedItem?.key ?? '';

									setAttributes( {
										iconName: key,
									} );

									setAttributes( {
										iconSvgString: icons?.[ attributes?.iconSet ]?.[ key ],
									} );
								} }
							/>
							<br />
							<PanelRow>
								<Flex>
									<FlexItem>
										<UnitControl
											label={ __( 'Icon Width', 'blockify' ) }
											value={ attributes?.iconSize ?? '' }
											onChange={ ( value: string ) => setAttributes( {
												iconSize: value,
											} ) }
										/>
									</FlexItem>
								</Flex>
							</PanelRow>
						</PanelBody>
					</InspectorControls>
				</>
			);
		};
	}, 'iconEdit' ),
	0
);

const getStyles = ( attributes: attributes ) => {
	const styles: style = {};
	let background = '';

	if ( attributes?.style?.color?.background ) {
		background = attributes.style.color.background;
	}

	if ( attributes?.backgroundColor ) {
		background = 'var(--wp--preset--color--' + attributes.backgroundColor + ', currentColor)';
	}

	let gradient = '';

	if ( attributes?.style?.color?.gradient ) {
		gradient = attributes?.style?.color?.gradient;
	}

	if ( attributes?.gradient ) {
		gradient = 'var(--wp--preset--gradient--' + attributes.gradient + ',currentColor)';
	}

	let text = '';

	if ( attributes?.style?.color?.text ) {
		text = attributes?.style?.color?.text;
	}

	if ( attributes?.textColor ) {
		text = 'var(--wp--preset--color--' + attributes.textColor + ',currentColor)';
	}

	if ( background !== '' ) {
		styles[ '--wp--custom--icon--background' ] = background;
	}

	if ( text ) {
		styles[ '--wp--custom--icon--color' ] = text;

		if ( gradient ) {
			styles[ '--wp--custom--icon--background' ] = gradient;
		}
	} else if ( gradient ) {
		styles[ '--wp--custom--icon--color' ] = gradient;
	}

	if ( attributes?.style?.spacing?.padding ) {
		const padding = attributes.style.spacing.padding;

		const paddingObject: { [side: string]: string } = {
			top: padding?.top ?? '0',
			right: padding?.right ?? '0',
			bottom: padding?.bottom ?? '0',
			left: padding?.left ?? '0',
		};

		// Support spacing scale.
		Object.keys( paddingObject ).forEach( ( side: string ) => {
			const value: string = paddingObject?.[ side ] ?? '';

			if ( value && value?.includes( 'var:preset' ) ) {
				paddingObject[ side ] = 'var(--wp--preset--spacing--' + value.replace( 'var:preset|spacing|', '' ) + ')';
			}
		} );

		styles[ '--wp--custom--icon--padding' ] = Object.values( paddingObject ).join( ' ' );
	}

	if ( attributes?.style?.spacing?.margin ) {
		const margin = attributes.style.spacing.margin;

		const marginObject: { [side: string]: string } = {
			top: margin?.top ?? '',
			right: margin?.right ?? '',
			bottom: margin?.bottom ?? '',
			left: margin?.left ?? '',
		};

		// Support spacing scale.
		Object.keys( marginObject ).forEach( ( side: string ) => {
			const value: string = marginObject?.[ side ] ?? '';

			if ( value?.includes( 'var:preset' ) ) {
				marginObject[ side ] = 'var(--wp--preset--spacing--' + value?.replace( 'var:preset|spacing|', '' ) + ')';
			}
		} );

		styles[ '--wp--custom--icon--margin' ] = Object.values( marginObject ).join( ' ' );
	}

	let borderColor = '';

	if ( attributes?.borderColor ) {
		borderColor = 'var(--wp--preset--color--' + attributes?.borderColor + ')';
	}

	if ( attributes?.style?.border?.width ) {
		styles[ '--wp--custom--icon--border-width' ] = attributes.style.border.width;
		styles[ '--wp--custom--icon--border-style' ] = attributes.style.border?.style ?? 'solid';
		styles[ '--wp--custom--icon--border-color' ] = attributes.style.border?.color ?? borderColor;
	}

	const size = attributes?.iconSize ?? '';

	if ( size !== '' ) {
		styles[ '--wp--custom--icon--size' ] = size;
	}

	const custom: string = ( attributes?.iconCustomSVG ?? '' )?.replace( '"', "'" );
	const svg: string = custom && custom?.includes( '<svg' ) ? custom : attributes?.iconSvgString ?? '';

	if ( svg ) {
		styles[ '--wp--custom--icon--url' ] = "url('data:image/svg+xml;utf8," + svg + "')";
	}

	return styles;
};

addFilter(
	'editor.BlockListBlock',
	'blockify/edit-icon-styles',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			let { attributes, wrapperProps, name } = props;

			if ( ! attributes?.className ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! attributes?.className?.includes( 'is-style-icon' ) ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! supportsIcon( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! wrapperProps ) {
				wrapperProps = {
					style: {},
				};
			}

			wrapperProps.style = {
				...wrapperProps?.style,
				...getStyles( attributes ),
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		};
	}, 'withIcon' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-icon-styles',
	( props, block, attributes ): object => {
		if ( ! attributes?.className ) {
			return props;
		}

		const { name } = block;

		if ( ! attributes?.className?.includes( 'is-style-icon' ) ) {
			return props;
		}

		if ( ! supportsIcon( name ) ) {
			return props;
		}

		props.style = {
			...props?.style,
			...getStyles( attributes ),
		};

		return props;
	}
);
