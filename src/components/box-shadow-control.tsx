import { select } from '@wordpress/data';
import {
	__experimentalNumberControl as NumberControl,
	Button,
	ButtonGroup,
	Flex,
	FlexItem,
	PanelRow,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { ucWords } from '../utility';
import {
	__experimentalPanelColorGradientSettings as PanelColorGradientSettings,
	EditorColor,
} from '@wordpress/block-editor';
import { Label } from './label';
import { trash } from '@wordpress/icons';
import { useState } from '@wordpress/element';

interface PresetProps {
	name: string;
	slug: string;
	shadow: string;
}

const TextControls = ( props: {
	attributes: {
		style: {
			textShadow: {
				x?: string | undefined;
				y?: string | undefined;
				blur?: string | undefined;
				color?: string | undefined;
			};
		};
	};
	setAttributes: ( values: {
		style: {
			textShadow: {
				[property: string]: string | undefined;
			};
		};
	} ) => void;
	colorPalette: EditorColor[];
} ) => {
	const { attributes, setAttributes, colorPalette } = props;
	const { style } = attributes;
	const { textShadow } = style;

	return <>
		<PanelRow>
			<Flex>
				{ [ 'x', 'y', 'blur' ].map( ( key ) => (
					<FlexItem
						key={ key }
					>
						<NumberControl
							label={ ucWords( key ) }
							value={ textShadow?.[ key ?? '' ] ?? '' }
							onChange={ ( value: string | undefined ) => {
								setAttributes( {
									style: {
										...style,
										textShadow: {
											...textShadow,
											[ key ]: value,
										},
									},
								} );
							} }
						/>
					</FlexItem>
				) ) }
			</Flex>
		</PanelRow>
		<PanelColorGradientSettings
			title={ __( 'Color', 'blockify' ) }
			showTitle={ false }
			enableAlpha={ true }
			settings={ [
				{
					enableAlpha: true,
					colorValue: textShadow?.color,
					label: __( 'Color', 'blockify' ),
					onColorChange: ( value: string ) => {
						for ( const color of colorPalette ) {
							if ( color.color === value ) {
								value = 'var(--wp--preset--color--' + color.slug + ')';
							}
						}

						setAttributes( {
							style: {
								...style,
								textShadow: {
									...textShadow,
									color: value,
								},
							},
						} );
					},
				},
			] }
		/>
	</>;
};

const CustomControls = ( props: {
	boxShadow: { [property: string]: string | boolean };
	setBoxShadow: ( values: { [property: string]: string | boolean } ) => void;
	changeColor: ( value: string ) => void;
	tab: string;
} ) => {
	const {
		boxShadow,
		setBoxShadow,
		changeColor,
		tab,
	} = props;

	return <>
		<PanelRow>
			<Flex>
				{ [ 'x', 'y', 'blur', 'spread' ].map( ( key ) => (
					<FlexItem
						key={ key }
					>
						<NumberControl
							label={ ucWords( key ) }
							value={
								tab === 'default'
									? boxShadow[ key ]
									: boxShadow?.hover?.[ key ]
							}
							onChange={ ( value: string | undefined ) => {
								if ( ! value ) {
									return;
								}

								setBoxShadow( {
									[ key ]: value,
								} );
							} }
						/>
					</FlexItem>
				) ) }
				<FlexItem>
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
								onColorChange: changeColor,
							},
						] }
					/>
				</FlexItem>
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
								onColorChange: changeColor,
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
	</>;
};

const BoxShadowSettings = ( props: blockProps, tab: string ): JSX.Element => {
	const {
		attributes,
		setAttributes,
	} = props;
	const { style } = attributes;

	const boxShadow = style?.boxShadow ?? {};
	const textShadow = style?.textShadow ?? {};

	const colorPalette: EditorColor[] = select( 'core/block-editor' ).getSettings().colors ?? [];

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
			...attributes,
			style: {
				...style,
				boxShadow: {
					...boxShadow,
					...newAttributes,
				},
			},
		} );
	};

	const changeColor = ( value: string ) => {
		for ( const color of colorPalette ) {
			if ( color.color === value ) {
				value = 'var(--wp--preset--color--' + color.slug + ')';
			}
		}

		setBoxShadow( {
			color: value,
		} );
	};

	const presets: PresetProps[] = select( 'core/block-editor' )?.getSettings()?.__experimentalFeatures?.shadow?.presets?.theme;

	const PresetControls = () => {
		const hoverSuffix = tab === 'hover' ? '-hover' : '';

		return <>
			<PanelRow>
				<ButtonGroup
					className={ 'blockify-shadow-presets' }
				>
					{ presets.map( ( preset: PresetProps ) => {
						const isPrimary = ( tab === 'default' && attributes.shadowPreset === preset.slug ) || ( tab === 'hover' && attributes.shadowPresetHover === preset.slug );

						return <Button
							key={ preset.slug + hoverSuffix }
							className={ `has-${ preset.slug }-shadow` + hoverSuffix }
							isSmall
							variant={ isPrimary ? 'primary' : 'secondary' }
							onClick={ () => {
								if ( tab === 'hover' ) {
									setAttributes( {
										shadowPresetHover: preset.slug === 'none' ? '' : preset.slug,
									} );
								} else {
									setAttributes( {
										shadowPreset: preset.slug === 'none' ? '' : preset.slug,
									} );
								}
							} }
						>
							{ preset.name }
						</Button>;
					} ) }
				</ButtonGroup>
			</PanelRow>
			<br />
			<PanelRow>
				{ tab !== 'text' && <ToggleControl
					label={ __( 'Custom box shadow', 'blockify' ) }
					checked={ attributes?.useCustomBoxShadow }
					onChange={ ( value: boolean ) => {
						setAttributes( {
							useCustomBoxShadow: value,
						} );
					} }
				/> }
			</PanelRow>
		</>;
	};

	return <>
		{ tab === 'text' && <TextControls
			attributes={ attributes }
			setAttributes={ setAttributes }
			colorPalette={ colorPalette }
		/> }
		{ tab !== 'text' && <PresetControls /> }
		{ ( attributes?.useCustomBoxShadow && tab !== 'text' ) && <CustomControls
			boxShadow={ boxShadow }
			setBoxShadow={ setBoxShadow }
			changeColor={ changeColor }
			tab={ tab }
		/> }
	</>;
};

export const BoxShadowControl = (
	props: blockProps
): JSX.Element => {
	const { attributes, setAttributes } = props;

	const [ tab, setTab ] = useState<string>( 'default' );

	return <>
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
								shadowPreset: '',
								shadowPresetHover: '',
								useCustomBoxShadow: false,
								style: {
									...attributes?.style,
									boxShadow: '',
									textShadow: '',
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
				<Button
					isSmall
					variant={ tab === 'text' ? 'primary' : 'secondary' }
					onClick={ () => setTab( 'text' ) }
				>
					{ __( 'Text', 'blockify' ) }
				</Button>
			</ButtonGroup>
		</PanelRow>
		{ tab === 'default' && BoxShadowSettings( props, tab ) }
		{ tab === 'hover' && BoxShadowSettings( props, tab ) }
		{ tab === 'text' && BoxShadowSettings( props, tab ) }
	</>;
};
