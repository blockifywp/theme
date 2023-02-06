import { __ } from '@wordpress/i18n';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { applyFormat, Format, registerFormatType, removeFormat } from '@wordpress/rich-text';
import { toggleFormat } from '@wordpress/rich-text';
import { RichTextShortcut } from '@wordpress/block-editor';
import { formatUnderline } from "@wordpress/icons";
import { BlockControls } from '@wordpress/block-editor';
import {
	Toolbar,
	Popover,
	SelectControl,
	// @ts-ignore Text does exist.
	__experimentalText as Text,
} from '@wordpress/components';
import { useState } from "@wordpress/element";
import { ucFirst } from "../utility/string";

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

registerFormatType( name, {
	title: __( 'Underline', 'blockify' ),
	tagName: 'u',
	className: 'has-text-underline',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: ( { isActive, value, onChange } ) => {
		const [ underline, setUnderline ] = useState( '' );
		const [ isOpen, setIsOpen ]       = useState( false );

		let styles: string[]  = [];
		let classes: string[] = [];

		if ( value?.formats ) {
			// @ts-ignore Format is not array.
			value.formats.map( ( value: Format | undefined ): void => {
				if ( value?.type === name ) {
					const attributes = value?.attributes;

					styles  = ( attributes?.style ?? '' ).split( ';' );
					classes = ( attributes?.classes ?? '' ).split( ' ' );
				}
			} );
		}

		const onToggle = () => {
			onChange(
				toggleFormat( value, {
					type: name
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
						  <br/>
						  <SelectControl
							  onChange={ ( newUnderlineType: string ): void => {

								  if ( newUnderlineType === 'none' ) {
									  onChange( removeFormat( value, name ) );
								  }

								  setUnderline( newUnderlineType );

								  let existingClasses = classes ?? [];

								  existingClasses.forEach( ( value: string, index: number ): void => {
									  if ( value.includes( 'is-underline-' ) ) {
										  delete newAttributes.classes[index];
									  }
								  } );

								  const newAttributes = {
									  classes: [
										  ...existingClasses,
										  'is-underline-' + newUnderlineType
									  ],
									  styles: [
										  ...styles ?? [],
										  '--wp--custom--underline--style:' + newUnderlineType
									  ],
								  };

								  onChange( applyFormat( ( value ), {
									  type: name,
									  attributes: {
										  class: newAttributes.classes.join( ' ' ),
										  style: newAttributes.styles.join( ';' ),
									  }
								  } ) )
							  } }
							  value={ underline }
							  options={ underlineTypes.map( ( underlineType ) => {
								  return {
									  label: ucFirst( underlineType ),
									  value: underlineType
								  }
							  } ) }
						  >
						  </SelectControl>
					  </Popover>
				  </Toolbar>
				}
			</BlockControls>
		);
	}
} );
