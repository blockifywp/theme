import { __ } from '@wordpress/i18n';
import { applyFormat, Format, FormatProps, registerFormatType, removeFormat, toggleFormat } from '@wordpress/rich-text';
import { formatUnderline } from '@wordpress/icons';
import { BlockControls, RichTextShortcut, RichTextToolbarButton } from '@wordpress/block-editor';
import {
	Toolbar,
	Popover,
	SelectControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalText as Text,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { ucFirst } from '../utility/string';

const name = 'blockify/underline';

const underlineTypes = [
	'none',
	'solid',
	'wavy',
	'dashed',
	'dotted',
	'double',
	'brush',
	'circle',
];

const Edit = ( { isActive, value, onChange } : FormatProps ) => {
	const [ underline, setUnderline ] = useState( '' );
	const [ isOpen, setIsOpen ] = useState( false );

	let styles: string[] = [];
	let classes: string[] = [];

	if ( value?.formats ) {
		value.formats.forEach( ( formats: Format[] | undefined ): void => {
			if ( formats ) {
				formats.forEach( ( format: Format ): void => {
					if ( format.type === name ) {
						if ( format.attributes?.style ) {
							styles = format.attributes.style.split( ';' );
						}

						if ( format.attributes?.class ) {
							classes = format.attributes.class.split( ' ' );
						}
					}
				} );
			}
		} );
	}

	const onToggle = () => {
		onChange(
			toggleFormat( value, {
				type: name,
			} )
		);
	};

	return (
		<BlockControls>
			<RichTextShortcut
				type={ 'primary' }
				character={ 'u' }
				onUse={ onToggle }
			/>
			<RichTextToolbarButton
				icon={ formatUnderline }
				title={ __( 'Underline', 'blockify' ) }
				isActive={ isActive }
				shortcutType={ 'primary' }
				shortcutCharacter={ 'u' }
				onClick={ () => setIsOpen( ! isOpen ) }
			/>
			{ isOpen &&
			<Toolbar className={ 'blockify-components-toolbar' }>
				<Popover
					position={ 'bottom center' }
					className={ 'blockify-underline-format' }
					focusOnMount={ 'container' }
					onFocusOutside={ () => setIsOpen( false ) }
				>
					<Text>{ __( 'Underline style', 'blockify' ) }</Text>
					<br />
					<SelectControl
						onChange={ ( newUnderlineType: string ): void => {
							if ( newUnderlineType === 'none' ) {
								onChange( removeFormat( value, name ) );
							}

							setUnderline( newUnderlineType );

							const existingClasses = classes ?? [];

							existingClasses.forEach( ( existingClass: string, index: number ): void => {
								if ( existingClass.includes( 'is-underline-' ) ) {
									delete newAttributes.classes[ index ];
								}
							} );

							const newAttributes = {
								classes: [
									...existingClasses,
									'is-underline-' + newUnderlineType,
								],
								styles: [
									...styles ?? [],
									'--wp--custom--underline--style:' + newUnderlineType,
								],
							};

							onChange( applyFormat( ( value ), {
								type: name,
								attributes: {
									class: newAttributes.classes.join( ' ' ),
									style: newAttributes.styles.join( ';' ),
								},
							} ) );
						} }
						value={ underline }
						options={ underlineTypes.map( ( underlineType ) => {
							return {
								label: ucFirst( underlineType ),
								value: underlineType,
							};
						} ) }
					>
					</SelectControl>
				</Popover>
			</Toolbar>
			}
		</BlockControls>
	);
};

registerFormatType( name, {
	title: __( 'Underline', 'blockify' ),
	tagName: 'u',
	className: 'has-text-underline',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: Edit,
} );
