import { __ } from '@wordpress/i18n';
import { RichTextToolbarButton } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { removeFormat } from '@wordpress/rich-text';
import { registerFormatType } from '@wordpress/rich-text';

const name = 'blockify/clear-formatting';

registerFormatType(
	name,
	{
		title: __( 'Clear', 'blockify' ),
		tagName: 'span',
		className: 'clear',
		edit: props => {

			const { value, isActive, onChange } = props;

			const { formatTypes } = useSelect( select => {
				return {
					formatTypes: select( 'core/rich-text' ).getFormatTypes(),
				};
			} );

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
		}
	}
);
