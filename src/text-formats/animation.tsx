import { __ } from '@wordpress/i18n';
import {
	applyFormat,
	Format,
	FormatProps,
	registerFormatType,
	removeFormat,
	toggleFormat,
} from '@wordpress/rich-text';
import { rotateRight } from '@wordpress/icons';
import {
	BlockControls,
	RichTextShortcut,
	RichTextToolbarButton,
} from '@wordpress/block-editor';
import {
	__experimentalText as Text,
	Popover,
	SelectControl,
	Toolbar,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { ucFirst } from '../utility/string';

const name = 'blockify/animation';

const animationTypes = [
	'none',
	'typewriter',
];

const Edit = ( { isActive, value, onChange }: FormatProps ) => {
	const [ animation, setAnimation ] = useState( '' );
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

	if ( ! animation ) {
		animationTypes.forEach( ( animationType ) => {
			if ( classes.includes( 'has-text-' + animationType + '-animation' ) ) {
				setAnimation( animationType );
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

	const Settings = () => <>
		<Text>{ __( 'Animation style', 'blockify' ) }</Text>
		<br />
		<SelectControl
			onChange={ ( newAnimationType: string ): void => {
				if ( newAnimationType === 'none' ) {
					onChange( removeFormat( value, name ) );
				}

				setAnimation( newAnimationType );

				const existingClasses = classes ?? [];

				existingClasses.forEach( ( existingClass: string, index: number ): void => {
					if ( existingClass.includes( '-animation' ) ) {
						delete newAttributes.classes[ index ];
					}
				} );

				styles.forEach( ( style: string, index: number ): void => {
					if ( style.includes( '--wp--custom--animation--' ) ) {
						delete newAttributes.styles[ index ];
					}
				} );

				const newAttributes = {
					classes: [
						...existingClasses,
						'has-text-' + newAnimationType + '-animation',
					],
					styles: [
						...styles ?? [],
						'--wp--custom--animation--style:' + newAnimationType,
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
			value={ animation }
			options={ animationTypes.map( ( animationType ) => {
				return {
					label: ucFirst( animationType ),
					value: animationType,
				};
			} ) }
		>
		</SelectControl>
	</>;

	return (
		<BlockControls>
			<RichTextShortcut
				type={ 'primary' }
				character={ 'a' }
				onUse={ onToggle }
			/>
			<RichTextToolbarButton
				icon={ rotateRight }
				title={ __( 'Animation', 'blockify' ) }
				isActive={ isActive }
				shortcutType={ 'primary' }
				shortcutCharacter={ 'a' }
				onClick={ () => setIsOpen( ! isOpen ) }
			/>
			{ isOpen &&
			<Toolbar className={ 'blockify-components-toolbar' }>
				<Popover
					position={ 'bottom center' }
					className={ 'blockify-animation-format' }
					focusOnMount={ 'container' }
					onFocusOutside={ () => setIsOpen( false ) }
                	>
					<Settings />
				</Popover>
			</Toolbar>
			}
		</BlockControls>
	);
};

registerFormatType( name, {
	title: __( 'Animation', 'blockify' ),
	tagName: 'span',
	className: 'has-text-animation',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: Edit,
} );
