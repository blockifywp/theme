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

const BoxShadowSettings = ( props: blockProps, tab: string ): JSX.Element => {
	const {
		attributes,
		setAttributes,
	} = props;
	const { style } = attributes;

	const boxShadow = style?.boxShadow ?? {};

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
										shadowPresetHover: preset.slug,
									} );
								} else {
									setAttributes( {
										shadowPreset: preset.slug,
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
				<ToggleControl
					label={ __( 'Custom box shadow', 'blockify' ) }
					checked={ attributes?.useCustomBoxShadow }
					onChange={ ( value: boolean ) => {
						setAttributes( {
							useCustomBoxShadow: value,
						} );
					} }
				/>
			</PanelRow>
		</>;
	};

	const CustomControls = () => {
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

	return <>
		<PresetControls />
		{ attributes?.useCustomBoxShadow && <CustomControls /> }
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
		{ tab === 'default' && BoxShadowSettings( props, tab ) }
		{ tab === 'hover' && BoxShadowSettings( props, tab ) }
	</>;
};
