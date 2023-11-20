import { __ } from '@wordpress/i18n';
import {
	__experimentalUnitControl as UnitControl,
	Button,
	ButtonGroup,
	Flex,
	FlexItem,
	PanelBody,
	PanelRow,
	SelectControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { desktop, mobile, trash } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { Label } from '../components';
import { unitsWithAuto } from '../utility';

export const supportsImage = ( name: string ): boolean => [ 'core/image', 'core/post-featured-image', [ 'blockify/image-compare' ] ].includes( name );
export const supportsAspectRatio = ( name: string ): boolean => [ 'core/image', 'blockify/image-compare' ].includes( name );

addFilter(
	'blocks.registerBlockType',
	'blockify/image-attributes',
	( settings: any, name: string ) => {
		if ( ! supportsImage( name ) ) {
			return settings;
		}

		settings.attributes = {
			...settings.attributes,
			...{
				usePlaceholder: {
					type: 'string',
				},
			},
		};

		if ( ! settings?.attributes?.style ) {
			settings.attributes.style = {
				type: 'object',
			};
		}

		return settings;
	}
);

const ImageControl = ( props: blockProps, screen: string ) => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;
	const [ height, setHeight ] = useState( style?.height?.[ screen ] ?? '' );

	const AspectRatio = () => {
		return <>
			<Flex className={ 'blockify-flex-controls' }>
				<FlexItem>
					<SelectControl
						label={ __( 'Aspect Ratio', 'blockify' ) }
						value={ style?.aspectRatio?.[ screen ] ?? '' }
						options={ window?.blockify?.imageOptions?.aspectRatio?.options ?? [] }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									aspectRatio: {
										...style?.aspectRatio,
										[ screen ]: value,
									},
								},
							} );
						} }
					/>
				</FlexItem>
				<FlexItem>
					<UnitControl
						label={ __( 'Height', 'blockify' ) }
						value={ height?.[ screen ]?.includes( 'auto' ) ? '' : height[ screen ] }
						onChange={ ( value: string ) => {
							setHeight( {
								...height,
								[ screen ]: value?.includes( 'auto' ) ? '' : value,
							} );

							setAttributes( {
								style: {
									...style,
									height: {
										...style?.height,
										[ screen ]: value?.includes( 'auto' ) ? 'auto' : value,
									},
								},
							} );
						} }
						min={ 0 }
						step={ 1 }
						units={ unitsWithAuto }
						allowReset={ true }
					/>
				</FlexItem>
			</Flex>
			<Flex className={ 'blockify-flex-controls' }>
				<FlexItem>
					<SelectControl
						label={ __( 'Object Fit', 'blockify' ) }
						value={ style?.objectFit?.[ screen ] ?? '' }
						placeholder={ __( '', 'blockify' ) }
						options={ window?.blockify?.imageOptions?.objectFit?.options ?? [] }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									objectFit: {
										...style?.objectFit,
										[ screen ]: value,
									},
								},
							} );
						} }
					/>
				</FlexItem>
				<FlexItem>
					<SelectControl
						label={ __( 'Object Position', 'blockify' ) }
						value={ style?.objectPosition?.[ screen ] ?? '' }
						placeholder={ __( '', 'blockify' ) }
						options={ window?.blockify?.imageOptions?.objectPosition?.options ?? [] }
						onChange={ ( value: string ) => {
							setAttributes( {
								style: {
									...style,
									objectPosition: {
										...style?.objectPosition,
										[ screen ]: value,
									},
								},
							} );
						} }
					/>
				</FlexItem>
			</Flex>
		</>;
	};

	return <PanelRow
		className={ 'blockify-image-controls blockify-display-controls' }
	>
		{ supportsAspectRatio( props?.name ) &&
			<AspectRatio />
		}
		<Label
			style={ { marginTop: '0' } }
		>
			{ __( 'Placeholder Image', 'blockify' ) }
		</Label>
		<Flex className={ 'blockify-flex-controls' }>
			<ButtonGroup>
				<Button
					isSmall={ true }
					variant={ ( ! attributes?.usePlaceholder || attributes?.usePlaceholder === 'default' ) ? 'primary' : 'secondary' }
					onClick={ () => {
						setAttributes( {
							usePlaceholder: 'default',
						} );
					} }
				>
					{ __( 'Default', 'blockify' ) }
				</Button>
				<Button
					isSmall={ true }
					variant={ attributes?.usePlaceholder === 'none' ? 'primary' : 'secondary' }
					onClick={ () => {
						setAttributes( {
							usePlaceholder: 'none',
						} );
					} }
				>
					{ __( 'None', 'blockify' ) }
				</Button>
			</ButtonGroup>
		</Flex>
	</PanelRow>;
};

const ImageControls = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes, name } = props;

	if ( ! supportsImage( name ) ) {
		return <></>;
	}

	const [ screen, setScreen ] = useState( 'all' );

	return (
		<>
			<PanelRow>
				<Label>
					<>
						{ __( 'Image', 'blockify' ) }
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => {
								setAttributes( {
									style: {
										...attributes?.style,
										objectFit: null,
										objectPosition: null,
										aspectRatio: null,
										height: null,
									},
								} );
							} }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Reset Image', 'blockify' ) }
						/>
					</>
				</Label>
				<ButtonGroup>
					<Button
						isSmall
						variant={ screen === 'all' ? 'primary' : 'secondary' }
						onClick={ () => setScreen( 'all' ) }
					>
						{ __( 'All', 'blockify' ) }
					</Button>
					<Button
						isSmall
						variant={ screen === 'mobile' ? 'primary' : 'secondary' }
						onClick={ () => setScreen( 'mobile' ) }
						icon={ mobile }
					/>
					<Button
						isSmall
						variant={ screen === 'desktop' ? 'primary' : 'secondary' }
						onClick={ () => setScreen( 'desktop' ) }
						icon={ desktop }
					/>
				</ButtonGroup>
			</PanelRow>
			{ screen === 'all' && ImageControl( props, screen ) }
			{ screen === 'mobile' && ImageControl( props, screen ) }
			{ screen === 'desktop' && ImageControl( props, screen ) }
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/image-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsImage( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected &&
						<InspectorControls>
							<PanelBody
								initialOpen={ attributes?.image ?? false }
								title={ __( 'Image', 'blockify' ) }
							>
								<ImageControls { ...props } />
							</PanelBody>
						</InspectorControls>
					}
				</>
			);
		};
	}, 'withImage' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/image-attribute',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { attributes, name } = props;

			if ( ! supportsImage( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			const classes = props?.className?.split( ' ' ) ?? [];

			classes.push( 'is-placeholder' );
			classes.push( 'has-placeholder-icon' );

			const aspectRatio = attributes?.style?.aspectRatio ?? {};
			const aspectRatioScreen = aspectRatio?.all ?? aspectRatio?.desktop ?? aspectRatio?.mobile ?? '';

			if ( aspectRatioScreen ) {
				classes.forEach( ( className: string ) => {
					if ( className.includes( 'has-aspect-ratio-' ) ) {
						classes.splice( classes.indexOf( className ), 1 );
					}
				} );

				classes.push( `has-aspect-ratio-${ aspectRatioScreen }` );

				props = {
					...props,
					className: classes?.join( ' ' ) ?? '',
				};
			}

			const objectFit = attributes?.style?.objectFit ?? {};
			const objectFitScreen = objectFit?.all ?? objectFit?.desktop ?? objectFit?.mobile ?? '';

			if ( objectFitScreen ) {
				classes.forEach( ( className: string ) => {
					if ( className.includes( 'has-object-fit-' ) ) {
						classes.splice( classes.indexOf( className ), 1 );
					}
				} );

				classes.push( `has-object-fit-${ objectFitScreen }` );

				props = {
					...props,
					className: classes?.join( ' ' ) ?? '',
				};
			}

			const objectPosition = attributes?.style?.objectPosition ?? {};
			const objectPositionScreen = objectPosition?.all ?? objectPosition?.desktop ?? objectPosition?.mobile ?? '';

			if ( objectPositionScreen ) {
				classes.forEach( ( className: string ) => {
					if ( className.includes( 'has-object-position-' ) ) {
						classes.splice( classes.indexOf( className ), 1 );
					}
				} );

				classes.push( `has-object-position-${ objectPositionScreen }` );

				props = {
					...props,
					className: classes?.join( ' ' ) ?? '',
				};
			}

			const height = attributes?.style?.height ?? {};
			const heightScreen = height?.all ?? height?.desktop ?? height?.mobile ?? '';

			if ( heightScreen ) {
				const styles = props?.style ?? {};

				styles.height = heightScreen;

				props = {
					...props,
					style: styles,
				};
			}

			return (
				<BlockListBlock { ...props } />
			);
		};
	}, 'withImageAttribute' )
);
