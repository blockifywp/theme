import { __ } from '@wordpress/i18n';
import { Format, insertObject, registerFormatType } from '@wordpress/rich-text';
import { symbol } from '@wordpress/icons';
import { BlockControls, RichTextToolbarButton } from '@wordpress/block-editor';
import {
	__experimentalUnitControl as UnitControl,
	Button,
	Flex,
	FlexItem,
	PanelRow,
	Popover,
	TextareaControl,
	Toolbar,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { cssObjectToString } from '../utility';

const name = 'blockify/inline-svg';

const Edit = ( props: formatProps ) => {
	const {
		isActive,
		onChange,
		value,
	} = props;

	const [ isOpen, setIsOpen ] = useState( false );
	const [ svg, setSvg ] = useState( {
		string: '',
		width: '1em',
		widthDesktop: '1em',
		alt: '',
		src: '',
	} );

	const insertSvg = ( svgValue: {
		string: string;
		alt: string;
		width: string;
		widthDesktop: string;
		src: string;
	} ) => {
		const styles: genericStrings = {};

		if ( svgValue?.string ) {
			styles[ '-webkit-mask-image' ] = 'url(data:image/svg+xml;utf8,' + encodeURIComponent( svgValue.string ) + ')';
		}

		if ( svgValue?.width ) {
			styles[ '--width' ] = svgValue.width;
		}

		if ( svgValue?.widthDesktop ) {
			styles[ '--width-desktop' ] = svgValue.widthDesktop;
		}

		const format: Format = {
			type: name,
			attributes: {
				alt: svgValue?.alt,
				role: 'presentation',
				src: '',
				style: cssObjectToString( styles ),
			},
		};

		onChange(
			insertObject( value, format )
		);

		setIsOpen( false );
	};

	const ModalContent = () => <>
		<TextareaControl
			label={ __( 'SVG String', 'blockify' ) }
			help={ __( 'Paste your SVG string in the field above and then click the button below to insert your image.', 'blockify' ) }
			value={ svg?.string }
			placeholder={ __( 'Paste your SVG string here', 'blockify' ) }
			rows={ 20 }
			onChange={ ( newSvgString: string ) => {
				setSvg( {
					...svg,
					string: newSvgString.replace( /'/g, '"' ),
				} );
			} }
			style={ {
				fontFamily: 'var(--wp--preset--font-family--monospace, monospace)',
				width: '300px',
			} }
		/>
		<br />
		<TextareaControl
			label={ __( 'Alt Text', 'blockify' ) }
			placeholder={ __( 'SVG description', 'blockify' ) }
			value={ svg?.alt }
			rows={ 2 }
			onChange={ ( newAlt: string ) => {
				setSvg( {
					...svg,
					alt: newAlt,
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
						onChange={ ( widthMobile: string ) => {
							setSvg( {
								...svg,
								width: widthMobile,
							} );
						} }
					/>
				</FlexItem>
				<FlexItem>
					<UnitControl
						label={ __( 'Width Desktop', 'blockify' ) }
						value={ svg?.widthDesktop }
						onChange={ ( widthDesktop: string ) => {
							setSvg( {
								...svg,
								widthDesktop,
							} );
						} }
					/>
				</FlexItem>
			</Flex>
		</PanelRow>
		<br />
		<Button
			variant={ 'primary' }
			text={ __( 'Insert SVG', 'blockify' ) }
			onClick={ () => insertSvg( svg ) }
		/>
	</>;

	return (
		<BlockControls>
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
					<ModalContent />
				</Popover>
			</Toolbar>
			}
		</BlockControls>
	);
};

registerFormatType( name, {
	title: __( 'Inline SVG', 'blockify' ),
	object: true,
	tagName: 'img',
	className: 'has-inline-svg',
	edit: Edit,
} );
