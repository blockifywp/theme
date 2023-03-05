import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { image } from '@wordpress/icons';

const defaultImage: BlockVariation = {
	name: 'image',
	title: __( 'Image', 'blockify' ),
	icon: image,
	isDefault: true,
	category: 'media',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert an image to make a visual statement.', 'blockify' ),
	attributes: {
		className: '',
	},
	isActive: ( blockAttributes ) => {
		if ( ! blockAttributes?.className ) {
			return true;
		}

		return ! blockAttributes?.className?.includes( 'is-style-icon' ) && ! blockAttributes?.className?.includes( 'is-style-svg' );
	},
};

registerBlockVariation( 'core/image', defaultImage );
