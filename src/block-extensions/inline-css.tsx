import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorAdvancedControls } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { CodeEditorModal } from '../components';

const supportsInlineCss = ( name: string ): boolean => window?.blockify?.blockSupports?.[ name ]?.blockifyInlineCss ?? false;

addFilter(
	'blocks.registerBlockType',
	'blockify/add-inline-css-attribute',
	( props, name ): object => {
		if ( ! supportsInlineCss( name ) ) {
			return props;
		}

		props.attributes = {
			...props.attributes,
			inlineCss: {
				type: 'string',
			},
		};

		return props;
	},
	0
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-inline-css-attribute',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, setAttributes, name } = props;

			if ( ! supportsInlineCss( name ) ) {
				return <BlockEdit { ...props } />;
			}

			const userRoles = useSelect<any>( ( select: any ) => {
				const currentUser: { id: number } = select( 'core' )?.getCurrentUser();
				const user: { roles: string[] } = select( 'core' )?.getUser( currentUser?.id );

				return user?.roles;
			}, [] );

			if ( ! userRoles?.includes( 'administrator' ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					<InspectorAdvancedControls>
						<CodeEditorModal
							code={ attributes?.inlineCss ?? '' }
							language={ 'css' }
							onChange={ ( value: string ) : void => {
								setAttributes( {
									inlineCss: value,
								} );
							} }
							title={ __( 'Edit Inline CSS', 'blockify' ) }
							description={ __( 'Add custom CSS to this block.', 'blockify' ) }
						/>
					</InspectorAdvancedControls>
				</>
			);
		};
	}, 'inlineCssAttribute' ),
	99
);
