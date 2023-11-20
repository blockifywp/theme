import { __ } from '@wordpress/i18n';
import {
	__experimentalNumberControl as NumberControl,
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
import { gridTypes } from '../block-variations/grid';

const blockSupports = window?.blockify?.blockSupports ?? {};

export const supportsDisplay = ( name: string ): boolean => blockSupports?.[ name ]?.blockifyPosition ?? false;

interface DisplayAttributes {
	style?: {
		display?: responsiveStyles;
		order?: responsiveStyles;
		width?: responsiveStyles;
		minWidth?: responsiveStyles;
		maxWidth?: responsiveStyles;
	};
	layout?: {
		type?: string;
		flexWrap?: string;
		orientation?: string;
	};
}

interface DisplayProps {
	attributes: DisplayAttributes;
	setAttributes: ( attributes: DisplayAttributes ) => void;
	screen: 'all' | 'mobile' | 'desktop';
	isSelected: boolean;
	name: string;
}

const DisplayControl = ( props: DisplayProps ) => {
	const { attributes, setAttributes, screen } = props;
	const { style } = attributes;

	return <>
		<PanelRow
			className={ 'blockify-display-controls' }
		>
			<Flex className={ 'blockify-flex-controls' }>
				<FlexItem>
					<SelectControl
						label={ __( 'Display', 'blockify' ) }
						value={ style?.display?.[ screen ] ?? '' }
						options={ window?.blockify?.extensionOptions?.display?.options ?? [] }
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

							if ( gridTypes.includes( value ) ) {
								setAttributes( {
									layout: {
										...attributes?.layout,
										type: 'flex',
										flexWrap: 'nowrap',
										orientation: 'grid',
									},
								} );
							} else {
								setAttributes( {
									layout: {
										...attributes?.layout,
										orientation: 'horizontal',
									},
								} );
							}
						} }
					/>
				</FlexItem>
				<FlexItem>
					<NumberControl
						label={ __( 'Order', 'blockify' ) }
						value={ style?.order?.[ screen ] ?? '' }
						onChange={ ( value: string | undefined ) => {
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
						value={ style?.width?.[ screen ]?.includes( 'auto' ) ? '' : style?.width?.[ screen ] }
						units={ unitsWithAuto }
						onChange={ ( value: string | undefined ) => {
							setAttributes( {
								style: {
									...style,
									width: {
										...style?.width,
										[ screen ]: value?.includes( 'auto' ) ? 'auto' : value,
									},
								},
							} );
						} }
					/>
				</FlexItem>
				<FlexItem>
					<UnitControl
						label={ __( 'Min Width', 'blockify' ) }
						value={ style?.minWidth?.[ screen ] }
						onChange={ ( value: string | undefined ) => {
							setAttributes( {
								style: {
									...style,
									minWidth: {
										...style?.minWidth,
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
						onChange={ ( value: string | undefined ) => {
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
	</>;
};

const DisplayControls = ( props: DisplayProps ): JSX.Element => {
	const { attributes, setAttributes } = props;
	const [ screen, setScreen ] = useState( 'all' );

	return <>
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
									display: {},
									order: {},
									width: {},
									maxWidth: {},
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
		{ screen === 'all' && <DisplayControl { ...{ ...props, screen } } /> }
		{ screen === 'mobile' && <DisplayControl { ...{ ...props, screen } } /> }
		{ screen === 'desktop' && <DisplayControl { ...{ ...props, screen } } /> }
	</>;
};

addFilter(
	'editor.BlockEdit',
	'blockify/display-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: DisplayProps ) => {
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
								initialOpen={ ( attributes?.style?.display?.all?.length ?? {} ) > 0 ?? false }
								title={ __( 'Display', 'blockify' ) }
							>
								<DisplayControls { ...props } />
							</PanelBody>
						</InspectorControls>
					}
				</>
			);
		};
	}, 'withDisplay' )
);
