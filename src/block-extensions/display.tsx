import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	PanelRow,
	Flex,
	FlexItem,
	// @ts-ignore
	__experimentalUnitControl as UnitControl,
	// @ts-ignore
	__experimentalNumberControl as NumberControl,
	ButtonGroup,
	Button, PanelBody,
} from '@wordpress/components';
import { Label } from '../components/label';
import { useState } from '@wordpress/element';
import { desktop, mobile, trash } from '@wordpress/icons';
import { ucWords } from '../utility/string';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

const blockSupports = window?.blockify?.blockSupports ?? {};

export const supportsDisplay = ( name: string ): boolean => blockSupports?.[ name ]?.blockifyPosition ?? false;

const displayOptions = [
	'',
	'none',
	'block',
	'inline-block',
	'inline',
	'flex',
	'inline-flex',
	'grid',
	'inline-grid',
	'contents',
];

const DisplayControl = ( props: blockProps, screen: string ) => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;

	return (
		<>
			<PanelRow
				className={ 'blockify-display-controls' }
			>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<SelectControl
							label={ __( 'Display', 'blockify' ) }
							value={ style?.display?.[ screen ] ?? '' }
							options={ displayOptions.map( ( option ) => {
								return {
									label: ucWords( option?.replace( '-', ' ' ) ),
									value: option,
								};
							} ) }
							onChange={ ( value: string ) => {
								setAttributes( {
									style: {
										...style,
										display: {
											...style?.display,
											[ screen ]: value,
										},
									},
								} );
							} }
						/>
					</FlexItem>
					<FlexItem>
						<NumberControl
							label={ __( 'Order', 'blockify' ) }
							value={ style?.order?.[ screen ] ?? '' }
							onChange={ ( value: string ) => {
								setAttributes( {
									style: {
										...style,
										order: {
											...style?.order,
											[ screen ]: value,
										},
									},
								} );
							} }
							min={ -10 }
							max={ 10 }
							step={ 1 }
							allowReset={ true }
						/>
					</FlexItem>
				</Flex>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<UnitControl
							label={ __( 'Width', 'blockify' ) }
							value={ style?.width?.[ screen ] }
							onChange={ ( value: string ) => {
								setAttributes( {
									style: {
										...style,
										width: {
											...style?.width,
											[ screen ]: value,
										},
									},
								} );
							} }
						/>
					</FlexItem>
					<FlexItem>
						<UnitControl
							label={ __( 'Max Width', 'blockify' ) }
							value={ style?.maxWidth?.[ screen ] }
							onChange={ ( value: string ) => {
								setAttributes( {
									style: {
										...style,
										maxWidth: {
											...style?.maxWidth,
											[ screen ]: value,
										},
									},
								} );
							} }
						/>
					</FlexItem>
				</Flex>
			</PanelRow>

		</>
	);
};

const Display = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const [ screen, setScreen ] = useState( 'all' );

	return (
		<>
			<PanelRow>
				<Label>
					<>
						{ __( 'Display', 'blockify' ) }
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => {
								setAttributes( {
									style: {
										...attributes?.style,
										display: '',
										order: '',
										width: '',
										maxWidth: '',
									},
								} );
							} }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Reset Display', 'blockify' ) }
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
			{ screen === 'all' && DisplayControl( props, screen ) }
			{ screen === 'mobile' && DisplayControl( props, screen ) }
			{ screen === 'desktop' && DisplayControl( props, screen ) }
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/display-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsDisplay( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected &&
					<InspectorControls>
						<PanelBody
							initialOpen={ attributes?.display ?? false }
							title={ __( 'Display', 'blockify' ) }
						>
							<Display { ...props } />
						</PanelBody>
					</InspectorControls>
					}
				</>
			);
		};
	}, 'withDisplay' )
);
