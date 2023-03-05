import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

domReady( () => {
	const blockStyles: blockStyles = window?.blockify?.blockStyles ?? {
		unregister: [],
		register: [],
	};

	const register = blockStyles?.register ?? [];
	const unregister = blockStyles?.unregister ?? [];

	unregister.forEach( ( blockStyle: blockStyle ) => {
		unregisterBlockStyle( blockStyle?.type, blockStyle?.name );
	} );

	register.forEach( ( blockStyle: blockStyle ) => {
		registerBlockStyle( blockStyle?.type, blockStyle );
	} );
} );
