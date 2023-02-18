import { __ } from '@wordpress/i18n';
import { Format, FormatProps, registerFormatType, applyFormat } from '@wordpress/rich-text';
import { RichTextShortcut, BlockControls, RichTextToolbarButton } from '@wordpress/block-editor';
import { Toolbar, Popover, GradientPicker } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { SelectorMap, useSelect } from '@wordpress/data';
import { styles } from '@wordpress/icons';

const name = 'blockify/gradient';

const Edit = ( { isActive, value, onChange }: FormatProps ) => {
	const [ gradient, setGradient ] = useState( '' );
	const [ isOpen, setIsOpen ] = useState( false );

	const { gradients } = useSelect<any>( ( select: SelectorMap ) => {
		return {
			gradients: select( 'core/block-editor' ).getSettings()?.gradients,
		};
	}, [] );

	let existingStyle = '';
	let existingClass = '';

	if ( value?.formats ) {
		value.formats.forEach( ( format: Format[] | undefined ): void => {
			const currentFormat = format?.find( ( f: Format ) => f?.type === name );

			if ( currentFormat?.type === name ) {
				existingStyle += ';' + currentFormat?.attributes?.style;
				existingClass += currentFormat?.attributes?.class;
			}
		} );
	}

	return (
		<BlockControls>
			<RichTextShortcut
				type={ 'primary' }
				character={ 'g' }
				onUse={ () => setIsOpen( ! isOpen ) }
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

							let style = existingStyle;
							let className = existingClass;

							gradients.forEach( ( gradientItem: { slug: string; gradient: string } ) => {
								if ( gradientItem.gradient === newGradient ) {
									className += ( className ? ' ' : '' ) + 'has-' + gradientItem.slug + '-gradient-background';
								}
							} );

							if ( newGradient && ! className.includes( '-gradient-background' ) ) {
								style += ( style ? style + ';' : '' ) + 'background:' + newGradient;
							}

							if ( className?.includes( 'has-text-gradient' ) ) {
								className = className?.replace( 'has-text-gradient', '' )?.trim() + ' has-text-gradient';
							}

							onChange( applyFormat( value, {
								type: name,
								attributes: {
									style,
									class: className,
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

registerFormatType( name, {
	title: __( 'Gradient', 'blockify' ),
	tagName: 'span',
	className: 'has-text-gradient',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: Edit,
} );

