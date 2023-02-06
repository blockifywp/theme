import domReady from "@wordpress/dom-ready";
import { registerBlockStyle } from '@wordpress/blocks';
import { unregisterBlockStyle } from '@wordpress/blocks';

domReady( () => {

	const blockStyles: blockStyles = window?.blockify?.blockStyles ?? {
		unregister: [],
		register: []
	};

	[ ...blockStyles?.unregister ].forEach( ( blockStyle: blockStyle ) => {
		unregisterBlockStyle( blockStyle?.type, blockStyle?.name );
	} );

	[ ...blockStyles?.register ].forEach( ( blockStyle: blockStyle ) => {
		registerBlockStyle( blockStyle?.type, blockStyle );
	} );
} );
