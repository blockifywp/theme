import { __ } from "@wordpress/i18n";
import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";
import {
	InspectorAdvancedControls
} from "@wordpress/block-editor";
import { TextareaControl } from "@wordpress/components";
import { useSelect } from "@wordpress/data";

const supportsOnclick = ( name: string ): boolean => window?.blockify?.blockSupports?.[name]?.blockifyOnclick ?? false;

addFilter(
	'blocks.registerBlockType',
	'blockify/add-onclick-attribute',
	( props, name ): object => {
		if ( ! supportsOnclick( name ) ) {
			return props;
		}

		props.attributes = {
			...props.attributes,
			onclick: {
				type: 'string',
			}
		}

		return props;
	},
	0
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-onclick-attribute',
	createHigherOrderComponent( BlockEdit => {
		return ( props: blockProps ) => {
			const { attributes, setAttributes, name } = props;

			if ( ! supportsOnclick( name ) ) {
				return <BlockEdit { ...props } />
			}

			const userRoles = useSelect( select => {
				const currentUser: { id: number } = select( 'core' ).getCurrentUser();
				const user: { roles: string[] }   = select( 'core' ).getUser( currentUser?.id );

				return user?.roles;
			} );

			if ( ! userRoles?.includes( 'administrator' ) ) {
				return <BlockEdit { ...props } />
			}

			return (
				<>
					<BlockEdit { ...props } />
					<InspectorAdvancedControls>
						<TextareaControl
							label={ __( 'On-click event', 'blockify' ) }
							help={ __( 'Enter a JavaScript function to be called when the button is clicked.', 'blockify' ) }
							rows={ 4 }
							value={ attributes?.onclick?.replace( '"', "'" ) }
							onChange={ ( value: string ) => setAttributes( {
								onclick: value?.replace( '"', "'" )
							} ) }
							style={ {
								fontFamily: 'ui-monospace,Menlo,Monaco,Cascadia Code,Segoe UI Mono,Roboto Mono,Oxygen Mono,Ubuntu Monospace,Source Code Pro,Fira Code,Droid Sans Mono,DejaVu Sans Mono,Courier New,monospace',
								fontSize: '14px',
								tabSize: '1em',
								lineHeight: '1.5'
							} }
						/>

					</InspectorAdvancedControls>
				</>
			);
		};
	}, 'onclickAttribute' ),
	99
);
