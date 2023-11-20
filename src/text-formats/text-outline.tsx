import { __ } from '@wordpress/i18n';
import { applyFormat, Format, registerFormatType } from '@wordpress/rich-text';
import { RichTextShortcut, RichTextToolbarButton, BlockControls } from '@wordpress/block-editor';
import { typography } from '@wordpress/icons';
import {
	Toolbar,
	Popover,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';

import { cssObjectToString, cssStringToObject } from '../utility/css';

const typographyType = 'blockify/typography';

const Edit = ( props: formatProps ) => {
	const {
		isActive,
		value,
		onChange,
	} = props;

	let existingStyleString = '';
	let existingClassString = '';

	if ( value?.formats ) {
		value.formats.forEach( ( formats: Format[] | undefined ): void => {
			formats?.forEach( ( format: Format ): void => {
				if ( format?.type === typographyType ) {
					existingStyleString = format?.attributes?.style ?? '';
					existingClassString = format?.attributes?.class ?? '';
				}
			} );
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
					<NumberControl
						label={ __( 'Select Font Family', 'blockify' ) }
						value={ state?.width }
						options={ [] }
						onChange={ ( newWidth: string ) => {
							setState( {
								...state,
								width: newWidth,
							} );

							onChange( applyFormat( value, {
								type: typographyType,
								attributes: {
									style: cssObjectToString( state?.style ),
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
	title: __( 'Text Outline', 'blockify' ),
	tagName: 'span',
	className: 'has-text-outline',
	edit: Edit,
} );

