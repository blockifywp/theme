import { RichTextToolbarButton } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { applyFormat, Format, registerFormatType } from '@wordpress/rich-text';
import { RichTextShortcut } from '@wordpress/block-editor';
import { typography } from "@wordpress/icons";
import { BlockControls } from '@wordpress/block-editor';
import {
	Toolbar,
	Popover,
	// @ts-ignore.
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import { useState } from "@wordpress/element";

import { cssObjectToString, cssStringToObject } from "../utility/css";

const typographyType = 'blockify/typography';

registerFormatType( typographyType, {
	title: __( 'Text Outline', 'blockify' ),
	tagName: 'span',
	className: 'has-text-outline',
	edit( props: formatProps ) {
		const {
				  isActive,
				  value,
				  onChange,
			  } = props;

		let existingStyleString = '';
		let existingClassString = '';

		if ( value?.formats ) {
			// @ts-ignore Format is not array.
			value.formats.map( ( value: Format | undefined ): void => {
				if ( value?.type === name ) {
					existingStyleString += ';' + value?.attributes?.style;
					existingClassString += value?.attributes?.class;
				}
			} );
		}

		interface textOutline {
			style: style,
			class: string[],
			width?: string,
			color?: string,
			isOpen?: boolean,
		}

		const [ state, setState ] = useState<textOutline>( {
			style: cssStringToObject( existingStyleString ),
			class: existingClassString.split( ' ' ),
			width: '1px',
			color: '#000',
			isOpen: false,
		} );

		return (
			<BlockControls>
				<RichTextShortcut
					type={ 'primary' }
					character={ 'g' }
					onUse={ () => {
					} }
				/>
				<RichTextToolbarButton
					icon={ typography }
					title={ __( 'Typography', 'blockify' ) }
					isActive={ isActive }
					shortcutType={ 'primary' }
					shortcutCharacter={ 'f' }
					onClick={ () => setState( {
						...state,
						isOpen: ! state.isOpen
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
							  isOpen: false
						  } ) }
					  >
						  <NumberControl
							  label={ __( 'Select Font Family', 'blockify' ) }
							  value={ state?.width }
							  options={ [] }
							  onChange={ ( newWidth: string ) => {
								  setState( {
									  ...state,
									  width: newWidth
								  } )

								  onChange( applyFormat( value, {
									  type: typographyType,
									  attributes: {
										  style: cssObjectToString( state?.style )
									  }
								  } ) )
							  } }
						  />

					  </Popover>
				  </Toolbar>
				}
			</BlockControls>
		);
	}
} );


