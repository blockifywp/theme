import { __ } from '@wordpress/i18n';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { Format, FormatProps, registerFormatType } from '@wordpress/rich-text';
import { applyFormat } from '@wordpress/rich-text';
import { RichTextShortcut } from '@wordpress/block-editor';
import { BlockControls } from '@wordpress/block-editor';
// @ts-ignore GradientPicker does exist.
import { Toolbar, Popover, GradientPicker } from '@wordpress/components';
import { useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { styles } from "@wordpress/icons";
import { ComponentType } from "react";

const name = 'blockify/gradient';

const Edit: ComponentType<FormatProps> = ( { isActive, value, onChange } ) => {
	const [ gradient, setGradient ] = useState( '' );
	const [ isOpen, setIsOpen ]     = useState( false );

	const { gradients } = useSelect( select => {
		return {
			// @ts-ignore gradients does exist.
			gradients: select( 'core/block-editor' ).getSettings()?.gradients
		};
	} );

	let existingStyle = '';
	let existingClass = '';

	if ( value?.formats ) {
		// @ts-ignore Format is not array.
		value.formats.map( ( value: Format | undefined ): void => {
			if ( value?.type === name ) {
				existingStyle += ';' + value?.attributes?.style;
				existingClass += value?.attributes?.class;
			}
		} );
	}

	return (
		<BlockControls>
			<RichTextShortcut
				type={ 'primary' }
				character={ 'g' }
				onUse={ () => {
				} }
			/>
			<RichTextToolbarButton
				icon={ styles }
				title={ __( 'Gradient', 'blockify' ) }
				isActive={ isActive }
				shortcutType={ 'primary' }
				shortcutCharacter={ 'g' }
				onClick={ () => setIsOpen( ! isOpen ) }
			/>
			{ isOpen &&
			  <Toolbar className={ 'blockify-components-toolbar' }>
				  <Popover
					  position={ 'bottom center' }
					  className={ 'blockify-gradient-text-control' }
					  focusOnMount={ 'container' }
					  onFocusOutside={ () => setIsOpen( false ) }
				  >
					  <GradientPicker
						  value={ gradient ?? '' }
						  gradients={ gradients }
						  onChange={ ( newGradient: string ) => {
							  setGradient( newGradient );

							  let style     = existingStyle;
							  let className = existingClass;

							  gradients.forEach( ( gradient: { slug: string; gradient: string } ) => {
								  if ( gradient.gradient === newGradient ) {
									  className += ( className ? ' ' : '' ) + 'has-' + gradient.slug + '-gradient-background';
								  }
							  } );

							  if ( newGradient && ! className.includes( '-gradient-background' ) ) {
								  style += ( style ? style + ';' : '' ) + 'background:' + newGradient;
							  }

							  if ( className?.includes( 'has-text-gradient' ) ) {
								  className = className?.replace( 'has-text-gradient', '' )?.trim() + ' has-text-gradient'
							  }

							  onChange( applyFormat( value, {
								  type: name,
								  attributes: {
									  style: style,
									  class: className
								  }
							  } ) )
						  } }
					  />
				  </Popover>
			  </Toolbar>
			}
		</BlockControls>

	);
};

registerFormatType( name, {
	title: __( 'Gradient', 'blockify' ),
	tagName: 'span',
	className: 'has-text-gradient',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: Edit
} );


