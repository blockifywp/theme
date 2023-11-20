import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { plus } from '@wordpress/icons';

registerBlockVariation( 'core/list', {
	name: 'accordion',
	title: __( 'Accordion', 'blockify' ),
	description: __( 'Add a collapsible accordion list.', 'blockify' ),
	category: window?.blockify?.isPlugin ? 'blockify' : 'text',
	scope: [],
	icon: plus,
	attributes: {
		className: 'is-style-accordion',
	},
	isDefault: false,
	isActive: ( blockAttributes, variationAttributes ) => {
		return blockAttributes && blockAttributes?.className?.includes( variationAttributes.className );
	},
} );
