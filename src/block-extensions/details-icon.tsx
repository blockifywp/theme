import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { Button, ButtonGroup, PanelBody, PanelRow } from '@wordpress/components';
import { ucWords } from '../utility';
import { addClassName } from '../utility/css';

const iconTypes = [
	'chevron',
	'plus',
];

addFilter(
	'blocks.registerBlockType',
	'blockify/details-icon-attributes',
	( props, name: string ): void => {
		if ( name === 'core/details' ) {
			props = {
				...props,
				attributes: {
					...props.attributes,
					expandIcon: {
						type: 'string',
					},
					closeOthers: {
						type: 'boolean',
					},
				},
			};
		}

		return props;
	}
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-details-icon-controls',
	createHigherOrderComponent( ( BlockEdit: any ) => ( props: any ) => {
		const { attributes, setAttributes, name } = props;

		if ( name !== 'core/details' ) {
			return <BlockEdit { ...props } />;
		}

		if ( ! attributes?.expandIcon ) {
			attributes.expandIcon = 'chevron';
		}

		return <>
			<InspectorControls>
				<PanelBody title={ __( 'Expand Icon', 'blockify' ) }>
					<PanelRow>
						<ButtonGroup>
							{ iconTypes.map( ( iconType ) => (
								<Button
									key={ iconType }
									variant={ attributes.expandIcon === iconType ? 'primary' : 'secondary' }
									onClick={ () => setAttributes( { expandIcon: iconType } ) }
								>
									{ ucWords( iconType ) }
								</Button>
							) ) }
						</ButtonGroup>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<BlockEdit { ...props } />
		</>;
	}, 'withExpandIconControls' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-details-icon',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			const { name, attributes } = props;

			if ( name !== 'core/details' || ! attributes?.expandIcon ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = props.wrapperProps ?? {};
			const className = 'is-style-' + attributes.expandIcon;

			wrapperProps.className = addClassName( wrapperProps?.className, className );

			return <BlockListBlock
				{ ...props }
				wrapperProps={ wrapperProps }
			/>;
		},
		'withExpandIcon'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/details-icon-save',
	( props: blockProps ) => {
		const { name, attributes } = props;

		if ( name !== 'core/details' || ! attributes?.expandIcon ) {
			return props;
		}

		const className = 'is-style-' + attributes.expandIcon;

		props.className = addClassName( props?.className, className );

		return props;
	}
);
