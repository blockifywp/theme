import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { grid } from '@wordpress/icons';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import domReady from '@wordpress/dom-ready';

const blockVariation: BlockVariation = {
	name: 'group-grid',
	icon: grid,
	title: __( 'Grid', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'design',
	scope: [ 'inserter', 'transform' ],
	description: __( 'Arrange blocks in CSS grid', 'blockify' ),
	attributes: {
		layout: {
			type: 'flex',
			orientation: 'grid',
		},
	},
	isActive: ( blockAttributes ) => blockAttributes?.layout?.orientation === 'grid',
};

domReady(
	() => registerBlockVariation( 'core/group', blockVariation )
);

addFilter(
	'blocks.registerBlockType',
	'blockify/grid-attributes',
	( props, name: string ): void => {
		if ( name === 'core/group' ) {
			props = {
				...props,
				attributes: {
					...props.attributes,
					grid: {
						type: 'object',
					},
				},
			};
		}

		return props;
	},
	0
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-grid',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			if ( props?.name !== 'core/group' ) {
				return <BlockListBlock { ...props } />;
			}

			if ( props?.attributes?.layout?.orientation !== 'grid' ) {
				return <BlockListBlock { ...props } />;
			}

			return <BlockListBlock { ...props } />;
		},
		'withWidth'
	)
);
