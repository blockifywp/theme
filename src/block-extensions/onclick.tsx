import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorAdvancedControls } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { CodeEditorModal } from '../components';

const supportsOnclick = ( name: string ): boolean => window?.blockify?.blockSupports?.[ name ]?.blockifyOnclick ?? false;

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
			},
		};

		return props;
	},
	0
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-onclick-attribute',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, setAttributes, name } = props;

			if ( ! supportsOnclick( name ) ) {
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
							code={ attributes?.onclick ?? '' }
							language={ 'js' }
							onChange={ ( value: string ) => {
								setAttributes( {
									onclick: value,
								} );
							} }
							title={ __( 'Edit On-Click Event', 'blockify' ) }
							description={ __( 'Add custom JavaScript to the onclick event for this block.', 'blockify' ) }
						/>
					</InspectorAdvancedControls>
				</>
			);
		};
	}, 'onclickAttribute' ),
	99
);
