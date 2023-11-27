import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	__experimentalUnitControl as UnitControl,
	Button,
	ButtonGroup,
	CustomSelectControl,
	Flex,
	FlexItem,
	PanelBody,
	PanelRow,
	SelectControl,
} from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { starEmpty } from '@wordpress/icons';
import {
	BlockAttributes,
	BlockVariation,
	registerBlockVariation,
} from '@wordpress/blocks';
import { defaultIconState, Icons, iconStoreName } from '../api/icon-store';
import domReady from '@wordpress/dom-ready';
import { Label } from '../components';
import React from 'react';
import { defaultIcon, getIconStyles, IconAttributes } from '../utility/icon';
import { useSelect } from '@wordpress/data';
import HTMLReactParser from 'html-react-parser';
import Select, { components, SingleValue } from 'react-select';
import {
	OptionProps,
} from 'react-select/dist/declarations/src/components/Option';
import { toTitleCase } from '../utility';
import CustomSelectOption = CustomSelectControl.Option;

const { Option } = components;

const supportsIcon = ( name: string ): boolean => [ 'core/image', 'core/button', 'blockify/tab' ].includes( name );

const iconAttributes: { [key: string]: { [key: string]: any } } = {
	iconSet: {
		type: 'string',
		default: defaultIcon.set,
	},
	iconName: {
		type: 'string',
		default: defaultIcon.name,
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
	iconPosition: {
		type: 'string',
	},
	iconCustomSVG: {
		type: 'string',
	},
	iconSvgString: {
		type: 'string',
		default: defaultIcon.string,
	},
};

const blockVariation: BlockVariation = {
	name: 'icon',
	icon: starEmpty,
	title: __( 'Icon', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'media',
	scope: [ 'inserter' ],
	description: __( 'Insert a customizable SVG icon.', 'blockify' ),
	attributes: {
		className: 'is-style-icon',
		iconSet: defaultIcon.set,
		iconName: defaultIcon.name,
		iconSvgString: defaultIcon.string,
	},
	isActive: ( blockAttributes: BlockAttributes ) => {
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

			if ( name === 'core/button' ) {
				delete iconAttributes?.iconSet?.default;
				delete iconAttributes?.iconName?.default;
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
	const pluginActive = window?.blockify?.isPlugin ?? false;

	if ( pluginActive ) {
		return;
	}

	const description = document.getElementsByClassName( 'block-editor-block-card__description' ).item( 0 );

	if ( ! description ) {
		return;
	}

	const existingLink = document.getElementsByClassName( 'blockify-pro-icon-link' ).item( 0 );

	if ( ! existingLink ) {
		description.innerHTML = description.innerHTML + __( ' Get more icons with ', 'blockify' ) + '<a href="https://blockifywp.com/pro/" class="blockify-pro-icon-link" target="_blank">Blockify Pro ↗</a>';
	}
};

interface IconEditProps {
	name: string;
	attributes: IconAttributes;
	setAttributes: ( attributes: IconAttributes ) => void;
	isSelected: boolean;
	isButton: boolean;
}

interface IconSelectOption {
	value: string;
	label: string;
	icon: JSX.Element | JSX.Element[] | string;
}

const IconSettings = ( props: IconEditProps ) => {
	const {
		attributes,
		setAttributes,
		isButton,
	} = props;

	const iconSetOptions: CustomSelectOption[] = [
		{
			label: __( 'None', 'blockify' ),
			value: '',
		},
	];

	const icons: Icons = useSelect(
		( select ) => select( iconStoreName )?.getIcons(),
		[]
	) ?? defaultIconState;

	const allIconOptions: {
		[set: string]: IconSelectOption[];
	} = {
		wordpress: [
			{
				value: iconAttributes?.iconName?.default,
				label: toTitleCase( iconAttributes?.iconName?.default ?? '' ),
				icon: HTMLReactParser( iconAttributes?.iconSvgString?.default ?? '' ),
			},
		],
	};

	Object.keys( icons ).forEach( ( iconSet: string ) => {
		let label = iconSet.split( '-' ).join( ' ' );

		label = 'wordpress' === label ? 'WordPress' : label;

		iconSetOptions.push( {
			label,
			value: iconSet,
		} );

		allIconOptions[ iconSet ] = [];

		Object.keys( icons[ iconSet ] ).forEach( ( iconName: string ) => {
			if ( iconName !== attributes?.iconName ) {
				allIconOptions[ iconSet ].push(
					{
						icon: HTMLReactParser( icons?.[ iconSet ]?.[ iconName ] ),
						value: iconName,
						label: toTitleCase( iconName ?? '' ),
					}
				);
			}
		} );

		// Moves current icon to start of array.
		if ( icons?.[ iconSet ]?.[ attributes?.iconName ?? '' ] ) {
			allIconOptions[ iconSet ].unshift( {
				icon: HTMLReactParser( icons?.[ iconSet ]?.[ attributes?.iconName ?? '' ] ),
				value: attributes?.iconName ?? '',
				label: toTitleCase( attributes?.iconName ?? '' ),
			} );
		}
	} );

	const PositionControls = () => {
		const buttonStyle = {
			height: '30px',
		};

		return <FlexItem
			style={ {
				flexBasis: '100%',
			} }
		>
			<Label
				style={ {
					margin: '0 0 5px',
				} }
			>
				{ __( 'Icon Position', 'blockify' ) }
			</Label>
			<ButtonGroup>
				<Button
					variant={ attributes?.iconPosition === 'start' ? 'primary' : 'secondary' }
					onClick={ () => {
						setAttributes( {
							iconPosition: 'start',
						} );
					} }
					style={ buttonStyle }
				>
					{ __( 'Start', 'blockify' ) }
				</Button>
				<Button
					variant={ attributes?.iconPosition === 'end' ? 'primary' : 'secondary' }
					onClick={ () => {
						setAttributes( {
							iconPosition: 'end',
						} );
					} }
					style={ buttonStyle }
				>
					{ __( 'End', 'blockify' ) }
				</Button>
			</ButtonGroup>
		</FlexItem>;
	};

	const IconOption = ( optionProps: OptionProps ) => {
		return <Option
			{ ...optionProps }
			key={ optionProps.data?.value }
			label={ optionProps.data?.label }
		>
			{ optionProps.data?.icon ?? '' }
			<span className={ 'screen-reader-text' }>
				{ optionProps.data?.value ?? '' }
			</span>
		</Option>;
	};

	const Settings = () => <>
		<Label
			style={ {
				marginTop: 0,
			} }
		>
			{ __( 'Select Icon', 'blockify' ) }
		</Label>
		<Select
			isMulti={ false }
			isSearchable
			placeholder={ __( 'Select icon', 'blockify-pro' ) }
			options={ allIconOptions?.[ attributes?.iconSet ?? '' ] ?? allIconOptions?.wordpress }
			value={ {
				value: attributes?.iconName,
				label: toTitleCase( attributes?.iconName ?? '' ),
				icon: attributes?.iconSvgString ?? '',
			} }
			onChange={ ( value: SingleValue<IconSelectOption> ) => {
				setAttributes( {
					iconName: value?.value,
				} );

				setAttributes( {
					iconSvgString: icons?.[ attributes?.iconSet ?? '' ]?.[ value?.value ?? '' ] ?? '',
				} );
			} }
			components={ {
				Option: IconOption,
			} }
			isClearable={ true }
		/>

		<br />
		<PanelRow>
			<Flex
				align={ 'top' }
				justify={ 'space-between' }
				wrap={ false }
				style={ {
					alignItems: 'top',
					alignContent: 'top',
				} }
			>
				<FlexItem
					style={ {
						flexBasis: '100%',
					} }
				>
					<UnitControl
						label={ __( 'Icon Width', 'blockify' ) }
						value={ attributes?.iconSize }
						onChange={ ( value: string | undefined ) => {
							if ( value ) {
								setAttributes( {
									iconSize: value,
								} );
							}
						} }
					/>
				</FlexItem>
				{ isButton &&
				<PositionControls />
				}
			</Flex>
		</PanelRow>
	</>;

	return <>
		<SelectControl
			label={ __( 'Select Icon Set', 'blockify' ) }
			value={ attributes?.iconSet ?? iconAttributes?.iconSet.default }
			options={ iconSetOptions }
			onChange={ ( value: string ) => setAttributes( {
				iconSet: value,
			} ) }
		/>
		{ attributes?.iconSet &&
		<Settings />
		}
	</>;
};

addFilter(
	'editor.BlockEdit',
	'blockify/with-icon',
	createHigherOrderComponent(
		( BlockEdit ) => {
			return (
				props: IconEditProps
			) => {
				const {
					name,
					attributes,
					isSelected,
				} = props;

				const { className } = attributes;

				const isButton = [ 'core/button', 'blockify/tab' ].includes( name );

				if ( ! className?.includes( 'is-style-icon' ) && ! isButton ) {
					return <BlockEdit { ...props } />;
				}

				if ( ! supportsIcon( name ) ) {
					return <BlockEdit { ...props } />;
				}

				if ( isSelected ) {
					addProLink();
				}

				return <>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody
							title={ __( 'Icon Settings', 'blockify' ) }
							initialOpen={ true }
							className={ 'blockify-icon-settings' }
						>
							{ ! window?.blockify?.isPlugin &&
							<p>
								{ __( 'More icons available with Blockify Pro! ', 'blockify' ) }
								<a
									href="https://blockifywp.com/pro"
									target={ '_blank' }
									rel={ 'noreferrer' }
                                	>
									{ __( 'Learn more ↗', 'blockify' ) }
								</a>
							</p>
							}

							<IconSettings
								{ ...{ ...props, isButton } }
							/>
						</PanelBody>
					</InspectorControls>
				</>;
			};
		}, 'iconEdit' ),
	0
);

addFilter(
	'editor.BlockListBlock',
	'blockify/edit-icon-styles',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			let { attributes, wrapperProps, name } = props;

			const isButton = [ 'core/button' ].includes( name );

			if ( ! attributes?.className && ! isButton ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! attributes?.className?.includes( 'is-style-icon' ) && ! isButton ) {
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
				...getIconStyles( attributes ),
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		};
	}, 'withIcon' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-icon-styles',
	( props, block, attributes: IconAttributes ): object => {
		if ( ! attributes?.className ) {
			return props;
		}

		const { name } = block;

		const isButton = name === 'core/button';

		if ( ! attributes?.className?.includes( 'is-style-icon' ) && ! isButton ) {
			return props;
		}

		if ( ! supportsIcon( name ) ) {
			return props;
		}

		props = {
			...props,
			style: {
				...props?.style,
				...getIconStyles( attributes ),
			},
		};

		return props;
	}
);
