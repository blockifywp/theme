import { RichTextToolbarButton } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { registerFormatType, insertObject } from '@wordpress/rich-text';
import { RichTextShortcut } from '@wordpress/block-editor';
import { symbol } from "@wordpress/icons";
import { BlockControls } from '@wordpress/block-editor';
import {
	Toolbar,
	Popover, TextareaControl, Button, Flex, FlexItem,
	// @ts-ignore
	__experimentalUnitControl as UnitControl,
	PanelRow
} from '@wordpress/components';
import { useState } from "@wordpress/element";

const name = 'blockify/inline-svg';

registerFormatType( name, {
	title: __( 'Inline SVG', 'blockify' ),
	object: true,
	tagName: 'img',
	className: 'has-inline-svg',
	edit( props: formatProps ) {
		const {
				  isActive,
				  onChange,
				  value
			  } = props;

		const [ isOpen, setIsOpen ] = useState( false );
		const [ svg, setSvg ]       = useState( {
			string: '',
			width: '1em',
			widthDesktop: '1em',
			alt: '',
			src: ''
		} );

		const insertSvg = ( svg: {
			string: string,
			alt: string,
			width: string,
			widthDesktop: string,
			src: string
		} ) => {
			let src           = "data:image/svg+xml;utf8," + encodeURIComponent( svg?.string );
			let style: string = `-webkit-mask-image:url(${ src })`;

			if ( svg?.width ) {
				style += `;--width: ${ svg?.width }`;
			}

			if ( svg?.widthDesktop ) {
				style += `;--width-desktop: ${ svg?.widthDesktop }`;
			}

			onChange(
				insertObject( value, {
					type: name,
					attributes: {
						style: style,
						alt: svg?.alt,
						role: 'presentation',
						src: ''
					}
				} )
			);

			setIsOpen( false );
		};

		return (
			<BlockControls>
				<RichTextShortcut
					type={ 'primary' }
					character={ 'v' }
					onUse={ () => {
					} }
				/>
				<RichTextToolbarButton
					icon={ symbol }
					title={ __( 'Inline SVG', 'blockify' ) }
					isActive={ isActive }
					shortcutType={ 'primary' }
					shortcutCharacter={ 'v' }
					onClick={ () => setIsOpen( ! isOpen ) }
				/>
				{ isOpen &&
				  <Toolbar className={ 'blockify-components-toolbar' }>
					  <Popover
						  position={ 'bottom center' }
						  className={ 'blockify-svg-control' }
						  focusOnMount={ 'container' }
						  onFocusOutside={ () => setIsOpen( false ) }
					  >
						  <TextareaControl
							  label={ __( 'SVG String', 'blockify' ) }
							  help={ __( 'Paste your SVG string in the field above and then click the button below to insert your image.', 'blockify' ) }
							  value={ svg?.string }
							  placeholder={ __( 'Paste your SVG string here', 'blockify' ) }
							  rows={ 20 }
							  onChange={ ( newSvgString: string ) => {
								  setSvg( {
									  ...svg,
									  string: newSvgString.replace( /'/g, '"' )
								  } );
							  } }
							  style={ {
								  fontFamily: 'var(--wp--preset--font-family--monospace, monospace)',
								  width: '300px',
							  } }
						  />
						  <br/>
						  <TextareaControl
							  label={ __( 'Alt Text', 'blockify' ) }
							  placeholder={ __( 'SVG description', 'blockify' ) }
							  value={ svg?.alt }
							  rows={ 2 }
							  onChange={ ( newAlt: string ) => {
								  setSvg( {
									  ...svg,
									  alt: newAlt
								  } );
							  } }
							  style={ {
								  width: '300px',
							  } }
						  />
						  <PanelRow>
							  <Flex>
								  <FlexItem>
									  <UnitControl
										  label={ __( 'Width Mobile', 'blockify' ) }
										  value={ svg?.width }
										  onChange={ ( value: string ) => {
											  setSvg( {
												  ...svg,
												  width: value
											  } )
										  } }
									  />
								  </FlexItem>
								  <FlexItem>
									  <UnitControl
										  label={ __( 'Width Desktop', 'blockify' ) }
										  value={ svg?.widthDesktop }
										  onChange={ ( value: string ) => {
											  setSvg( {
												  ...svg,
												  widthDesktop: value
											  } )
										  } }
									  />
								  </FlexItem>
							  </Flex>
						  </PanelRow>
						  <br/>
						  <Button
							  isPrimary
							  text={ __( 'Insert SVG', 'blockify' ) }
							  onClick={ () => insertSvg( svg ) }
						  />
					  </Popover>
				  </Toolbar>
				}
			</BlockControls>
		);
	}
} );
