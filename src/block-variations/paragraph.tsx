import { BlockVariation, registerBlockVariation } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { paragraph } from "@wordpress/icons";

const defaultParagraph: BlockVariation = {
	name: 'paragraph',
	title: __( 'Paragraph', 'blockify' ),
	icon: paragraph,
	isDefault: true,
	category: 'text',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert an image to make a visual statement.', 'blockify' ),
	attributes: {
		className: "",
	},
	isActive: ( blockAttributes ) => {
		return ! blockAttributes?.className?.includes( 'is-style-curved-text' ) && ! blockAttributes?.className?.includes( 'is-style-counter' );
	}
}

registerBlockVariation( 'core/paragraph', defaultParagraph );
