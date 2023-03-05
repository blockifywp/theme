import { __ } from '@wordpress/i18n';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { SelectorMap, useSelect } from '@wordpress/data';
import { removeFormat, registerFormatType, FormatProps } from '@wordpress/rich-text';

const name = 'blockify/clear-formatting';

const Edit = ( props : FormatProps ) => {
	const { value, isActive, onChange } = props;

	const { formatTypes } = useSelect<any>( ( select : SelectorMap ) => {
		return {
			formatTypes: select( 'core/rich-text' ).getFormatTypes(),
		};
	}, [] );

	const onToggle = () => {
		if ( formatTypes.length > 0 ) {
			let newValue = value;

			formatTypes.map( ( activeFormat: { name: string } ) => {
				newValue = removeFormat( newValue, activeFormat.name );
			} );

			onChange( { ...newValue } );
		}
	};

	return (
		<RichTextToolbarButton
			icon="editor-removeformatting"
			title={ __( 'Clear Formatting', 'blockify' ) }
			onClick={ onToggle }
			isActive={ isActive }
		/>
	);
};

registerFormatType(
	name,
	{
		title: __( 'Clear', 'blockify' ),
		tagName: 'span',
		className: 'clear',
		edit: Edit,
	}
);
