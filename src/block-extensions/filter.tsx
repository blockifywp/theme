import {
	__experimentalNumberControl as NumberControl,
	Button,
	PanelBody,
	PanelRow,
	ToggleControl,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { toKebabCase, ucWords } from '../utility/string';
import { Label } from '../components/label';
import { trash } from '@wordpress/icons';
import { InspectorControls } from '@wordpress/block-editor';

const supportsFilter = ( name: string ): boolean => window?.blockify?.blockSupports?.[ name ]?.blockifyFilter ?? false;

const config: filterOptions | null = window?.blockify?.filterOptions ?? {};

addFilter(
	'blocks.registerBlockType',
	'blockify/add-css-transform-attributes',
	( props, name ) => {
		if ( ! supportsFilter( name ) ) {
			return props;
		}

		props.attributes = {
			...props.attributes,
			style: {
				...( props?.attributes?.style ?? {} ),
				filter: {
					type: 'string',
				},
			},
		};

		return props;
	}
);

const getStyles = ( filter: cssFilters ): object => {
	let styles = '';

	if ( ! config?.blur ) {
		return {};
	}

	Object.keys( config ).forEach( ( key ) => {
		if ( Object.prototype.hasOwnProperty.call( filter, key ) && typeof filter[ key ] !== 'undefined' ) {
			styles += ' ' + toKebabCase( key ) + '(' + filter[ key ] + config?.[ key ]?.unit + ')';
		}
	} );

	if ( ! styles ) {
		return {};
	}

	return {
		[ filter?.backdrop ? 'backdropFilter' : 'filter' ]: styles.trim(),
	};
};

addFilter(
	'editor.BlockListBlock',
	'blockify/with-css-filter',
	createHigherOrderComponent(
		( BlockListBlock ) => {
			return ( props: blockProps ) => {
				const filter = props?.attributes?.style?.filter ?? {};
				const defaultReturn = <BlockListBlock { ...props } />;

				if ( Object.getOwnPropertyNames( filter ).length === 0 ) {
					return defaultReturn;
				}

				const styles = getStyles( filter );

				if ( ! Object.keys( styles ).length ) {
					return defaultReturn;
				}

				props = {
					...props,
					style: {
						...props?.style ?? {},
						...styles,
					},
				};

				const wrapperProps = {
					...props.wrapperProps,
					style: {
						...props.wrapperProps?.style,
						...styles,
					},
				};

				return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
			};
		},
		'withCssFilter'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/apply-filter-styles',
	( props, block, attributes ) => {
		const filter = attributes?.style?.filter ?? {};

		if ( Object.getOwnPropertyNames( filter ).length === 0 ) {
			return props;
		}

		const styles = getStyles( filter );

		if ( ! Object.keys( styles ).length ) {
			return props;
		}

		return {
			...props,
			style: {
				...props?.style,
				...styles,
			},
		};
	}
);

const Filter = ( props: blockProps ) => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;

	return (
		<>
			<Label>
				<>
					{ __( 'Filter', 'blockify' ) }
					<Button
						isSmall
						isDestructive
						variant={ 'tertiary' }
						onClick={ () => {
							setAttributes( {
								style: {
									...attributes?.style,
									filter: {},
								},
							} );
						} }
						icon={ trash }
						iconSize={ 16 }
						aria-label={ __( 'Clear Filters', 'blockify' ) }
					/>
				</>
			</Label>

			<PanelRow
				className={ 'blockify-filter-settings' }
			>
				{ Object.keys( config ).map( ( key: string ) => {
					return <NumberControl
						key={ key }
						label={ ( 'hueRotate' === key ? __( 'Hue Rotate', 'blockify' ) : ucWords( key ) ) }
						value={ style?.filter?.[ key ] }
						onChange={ ( value: number ) => {
							setAttributes( {
								style: {
									...style,
									filter: {
										...style?.filter ?? {},
										[ key ]: value,
									},
								},
							} );
						} }
						min={ config?.[ key ]?.min ?? 0 }
						max={ config?.[ key ]?.max }
						step={ config?.[ key ]?.step ?? 1 }
						allowReset={ true }
					/>;
				} ) }
			</PanelRow>

			<PanelRow>
				<ToggleControl
					label={ __( 'Use as backdrop filter', 'blockify' ) }
					checked={ style?.filter?.backdrop }
					onChange={ ( value ) => {
						setAttributes( {
							style: {
								...style,
								filter: {
									...style?.filter,
									backdrop: value,
								},
							},
						} );
					} }
				/>
			</PanelRow>
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/filter-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsFilter( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected &&
						<InspectorControls>
							<PanelBody
								initialOpen={ attributes?.filter ?? false }
								title={ __( 'Filter', 'blockify' ) }
							>
								<Filter { ...props } />
							</PanelBody>
						</InspectorControls>
					}
				</>
			);
		};
	}, 'withFilter' )
);
