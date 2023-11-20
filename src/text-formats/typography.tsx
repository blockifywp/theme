import { RichTextToolbarButton } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { applyFormat, Format, registerFormatType } from '@wordpress/rich-text';
import { RichTextShortcut } from '@wordpress/block-editor';
import { typography } from '@wordpress/icons';
import { BlockControls } from '@wordpress/block-editor';
import {
	Toolbar,
	Popover,
	SelectControl,
	FontSizePicker,
	CustomSelectControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { ucWords } from '../utility/string';
import { SelectorMap, useSelect } from '@wordpress/data';
import { cssObjectToString, cssStringToObject } from '../utility/css';
import Option = CustomSelectControl.Option;

const typographyType = 'blockify/typography';

const appearanceOptions: customSelectOptions = [
	{
		key: 'Default',
		name: 'Default',
		style: {},
	},
	{
		key: 'Thin',
		name: 'Thin',
		style: { fontStyle: 'normal', fontWeight: 100 },
	},
	{
		key: 'Extra Light',
		name: 'Extra Light',
		style: { fontStyle: 'normal', fontWeight: 200 },
	},
	{
		key: 'Light',
		name: 'Light',
		style: { fontStyle: 'normal', fontWeight: 300 },
	},
	{
		key: 'Regular',
		name: 'Regular',
		style: { fontStyle: 'normal', fontWeight: 400 },
	},
	{
		key: 'Medium',
		name: 'Medium',
		style: { fontStyle: 'normal', fontWeight: 500 },
	},
	{
		key: 'Semi Bold',
		name: 'Semi Bold',
		style: { fontStyle: 'normal', fontWeight: 600 },
	},
	{
		key: 'Bold',
		name: 'Bold',
		style: { fontStyle: 'normal', fontWeight: 700 },
	},
	{
		key: 'Extra Bold',
		name: 'Extra Bold',
		style: { fontStyle: 'normal', fontWeight: 800 },
	},
	{
		key: 'Black',
		name: 'Black',
		style: { fontStyle: 'normal', fontWeight: 900 },
	},
	{
		key: 'Thin Italic',
		name: 'Thin Italic',
		style: { fontStyle: 'italic', fontWeight: 100 },
	},
	{
		key: 'Extra Light Italic',
		name: 'Extra Light Italic',
		style: { fontStyle: 'italic', fontWeight: 200 },
	},
	{
		key: 'Light Italic',
		name: 'Light Italic',
		style: { fontStyle: 'italic', fontWeight: 300 },
	},
	{
		key: 'Regular Italic',
		name: 'Regular Italic',
		style: { fontStyle: 'italic', fontWeight: 400 },
	},
	{
		key: 'Medium Italic',
		name: 'Medium Italic',
		style: { fontStyle: 'italic', fontWeight: 500 },
	},
	{
		key: 'Semi Bold Italic',
		name: 'Semi Bold Italic',
		style: { fontStyle: 'italic', fontWeight: 600 },
	},
	{
		key: 'Bold Italic',
		name: 'Bold Italic',
		style: { fontStyle: 'italic', fontWeight: 700 },
	},
	{
		key: 'Extra Bold Italic',
		name: 'Extra Bold Italic',
		style: { fontStyle: 'italic', fontWeight: 800 },
	},
	{
		key: 'Black Italic',
		name: 'Black Italic',
		style: { fontStyle: 'italic', fontWeight: 900 },
	},
];

const Edit = ( props: formatProps ) => {
	const {
		isActive,
		value,
		onChange,
	} = props;

	const { fontSizes } = useSelect<any>( ( select : SelectorMap ) => {
		return {
			fontSizes: select( 'core/block-editor' )?.getSettings()?.fontSizes,
		};
	}, [] );

	const allFontFamilies = window?.blockify?.selectedFonts ?? [];

	const fontFamilyOptions = allFontFamilies?.map( ( slug: string ) => (
		{
			label: ucWords( slug?.replace( '-', ' ' ) ),
			value: slug,
		}
	) );

	let existingStyleString = '';
	let existingClassString = '';

	if ( value?.formats ) {
		value.formats.forEach( ( formats: Format[] | undefined ): void => {
			if ( formats ) {
				formats.forEach( ( format: Format ): void => {
					if ( format?.type === typographyType ) {
						existingStyleString = format?.attributes?.style ?? '';
						existingClassString = format?.attributes?.class ?? '';
					}
				} );
			}
		} );
	}

	interface Typography {
		style: style,
		class: string[],
		fontSize: string,
		fontFamily: string,
		fontAppearance: Option
		isOpen: boolean
	}

	const [ state, setState ] = useState<Typography>( {
		style: cssStringToObject( existingStyleString ),
		class: existingClassString.split( ' ' ),
		fontFamily: '',
		fontSize: '',
		fontAppearance: appearanceOptions[ 0 ],
		isOpen: false,
	} );

	return (
		<BlockControls>
			<RichTextToolbarButton
				icon={ typography }
				title={ __( 'Typography', 'blockify' ) }
				isActive={ isActive }
				shortcutType={ 'primary' }
				shortcutCharacter={ 'f' }
				onClick={ () => setState( {
					...state,
					isOpen: ! state.isOpen,
				} ) }
			/>
			{ state?.isOpen &&
			<Toolbar className={ 'blockify-components-toolbar' }>
				<Popover
					position={ 'bottom center' }
					className={ 'blockify-font-family-control' }
					focusOnMount={ 'container' }
					onFocusOutside={ () => setState( {
						...state,
						isOpen: false,
					} ) }
				>
					<SelectControl
						label={ __( 'Select Font Family', 'blockify' ) }
						value={ state?.fontFamily }
						options={ fontFamilyOptions }
						onChange={ ( newFontFamily ) => {
							setState( {
								...state,
								fontFamily: newFontFamily,
							} );

							const newClass = 'has-' + newFontFamily + '-font-family';

							if ( ! state?.class?.includes( newClass ) ) {
								state?.class?.push( newClass );
							}

							onChange( applyFormat( value, {
								type: typographyType,
								attributes: {
									style: cssObjectToString( state?.style ),
									class: state?.class?.join( ' ' ),
								},
							} ) );
						} }
					/>

					<FontSizePicker
						fontSizes={ fontSizes }
						fallbackFontSize={ 20 }
						value={ parseInt( state?.fontSize ) }
						withSlider={ true }
						onChange={ ( newFontSize: number ) => {
							setState( {
								...state,
								fontSize: newFontSize.toString(),
							} );

							if ( newFontSize ) {
								state.style[ '--wp--custom--font-size' ] = newFontSize.toString();
							}

							if ( ! state?.class?.includes( 'has-inline-font-size' ) ) {
								state.class.push( 'has-inline-font-size' );
							}

							onChange( applyFormat( value, {
								type: typographyType,
								attributes: {
									style: cssObjectToString( state?.style ),
									class: state?.class?.join( ' ' ),
								},
							} ) );
						} }
					/>

					<CustomSelectControl
						label={ __( 'Appearance', 'blockify' ) }
						value={ appearanceOptions.find( ( option ) => option.key === state?.fontAppearance?.key ) }
						options={ appearanceOptions ?? [] }
						onChange={ ( { selectedItem } ) => {
							if ( selectedItem ) {
								setState( {
									...state,
									fontAppearance: selectedItem,
								} );
							}

							if ( selectedItem?.style?.fontStyle ) {
								state.style[ 'font-style' ] = selectedItem?.style?.fontStyle;
							}

							if ( selectedItem?.style?.fontWeight ) {
								state.style[ 'font-weight' ] = selectedItem?.style?.fontWeight?.toString();
							}

							onChange( applyFormat( value, {
								type: typographyType,
								attributes: {
									style: cssObjectToString( state?.style ),
									class: state?.class?.join( ' ' ),
								},
							} ) );
						} }
					/>
				</Popover>
			</Toolbar>
			}
		</BlockControls>
	);
};

registerFormatType( typographyType, {
	title: __( 'Typography', 'blockify' ),
	tagName: 'span',
	className: 'has-font',
	edit: Edit,
} );
