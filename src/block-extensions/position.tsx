import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import {
	SelectControl,
	PanelRow,
	Flex,
	FlexItem,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalBoxControl as BoxControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
	ButtonGroup,
	Button, PanelBody,
} from '@wordpress/components';
import { Label } from '../components';
import { useState } from '@wordpress/element';
import { desktop, mobile, trash } from '@wordpress/icons';
import { InspectorControls } from '@wordpress/block-editor';

const blockSupports = window?.blockify?.blockSupports ?? {};

export const supportsPosition = ( name: string ): boolean => blockSupports?.[ name ]?.blockifyPosition ?? false;

export const PositionControl = ( props: blockProps, screen: string ) => {
	const { attributes, setAttributes } = props;

	const style = attributes?.style ?? {};

	const setPosition = ( values: { [ property: string ]: string } ) => {
		const properties: { [ property: string ]: string } = {};

		Object.keys( values ).forEach( ( property: string ) => {
			properties[ property ] = {
				...style?.[ property ],
				[ screen ]: values[ property ],
			};
		} );

		setAttributes( {
			style: {
				...style,
				...properties,
			},
		} );
	};

	return (
		<>
			<PanelRow>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<SelectControl
							label={ __( 'Position', 'blockify' ) }
							value={ style?.position?.[ screen ] ?? '' }
							options={ window?.blockify?.extensionOptions?.position?.options }
							onChange={ ( value ) => {
								setPosition( { position: value } );
							} }
						/>
					</FlexItem>
					<FlexItem>
						<NumberControl
							label={ window?.blockify?.extensionOptions?.zIndex?.label }
							value={ style?.zIndex?.[ screen ] }
							onChange={ ( value: string ) => {
								setPosition( { zIndex: value } );
							} }
							min={ -100 }
							max={ 100 }
							step={ 1 }
							allowReset={ true }
						/>
					</FlexItem>
				</Flex>
			</PanelRow>

			<PanelRow>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<SelectControl
							label={ __( 'Overflow', 'blockify' ) }
							value={ style?.overflow?.[ screen ] ?? '' }
							options={ window?.blockify?.extensionOptions?.overflow?.options }
							onChange={ ( value ) => {
								setPosition( { overflow: value } );
							} }
						/>
					</FlexItem>
					<FlexItem>
						<SelectControl
							label={ __( 'Pointer Events', 'blockify' ) }
							value={ style?.pointerEvents?.[ screen ] ?? '' }
							options={ window?.blockify?.extensionOptions?.pointerEvents?.options }
							onChange={ ( value ) => {
								setPosition( { pointerEvents: value } );
							} }
						/>
					</FlexItem>
				</Flex>
			</PanelRow>

			<PanelRow>
				<BoxControl
					className={ 'blockify-box-control' }
					label={ __( 'Inset', 'blockify' ) }
					values={ {
						top: style?.top?.[ screen ] ?? '',
						right: style?.right?.[ screen ] ?? '',
						bottom: style?.bottom?.[ screen ] ?? '',
						left: style?.left?.[ screen ] ?? '',
					} }
					onChange={ ( value: {
						top: string,
						right: string,
						bottom: string,
						left: string,
					} ) => {
						setPosition( {
							top: value?.top ?? '',
							right: value?.right ?? '',
							bottom: value?.bottom ?? '',
							left: value?.left ?? '',
						} );
					} }
					inputProps={ {
						min: -999,
					} }
				/>
			</PanelRow>
		</>
	);
};

export const Position = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const [ screen, setScreen ] = useState( 'all' );

	return (
		<>
			<PanelRow>
				<Label>
					<>
						<span>{ __( 'Position', 'blockify' ) }</span>
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => {
								setAttributes( {
									style: {
										...attributes?.style,
										position: '',
										zIndex: '',
										top: '',
										right: '',
										bottom: '',
										left: '',
									},
								} );
							} }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Reset Position', 'blockify' ) }
						/>
					</>
				</Label>
				<ButtonGroup>
					<Button
						isSmall
						variant={ screen === 'all' ? 'primary' : 'tertiary' }
						onClick={ () => setScreen( 'all' ) }
					>
						{ __( 'All', 'blockify' ) }
					</Button>
					<Button
						isSmall
						variant={ screen === 'mobile' ? 'primary' : 'tertiary' }
						onClick={ () => setScreen( 'mobile' ) }
						icon={ mobile }
					/>
					<Button
						isSmall
						variant={ screen === 'desktop' ? 'primary' : 'tertiary' }
						onClick={ () => setScreen( 'desktop' ) }
						icon={ desktop }
					/>
				</ButtonGroup>
			</PanelRow>
			{ screen === 'all' && PositionControl( props, screen ) }
			{ screen === 'mobile' && PositionControl( props, screen ) }
			{ screen === 'desktop' && PositionControl( props, screen ) }
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/position-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsPosition( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected &&
					<InspectorControls>
						<PanelBody
							initialOpen={ attributes?.position ?? false }
							title={ __( 'Position', 'blockify' ) }
						>
							<Position { ...props } />
						</PanelBody>
					</InspectorControls>
					}
				</>
			);
		};
	}, 'withPosition' )
);
