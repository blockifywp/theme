import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';
import { replaceAll, ucWords } from '../utility/string';

domReady( () => {
	const blockStyles: BlockStyles = window?.blockify?.blockStyles ?? {
		unregister: {},
		register: {},
	};

	const unregister = blockStyles?.unregister ?? {};
	const register = blockStyles?.register ?? {};

	Object.keys( unregister ).forEach( ( blockName ) => {
		unregister[ blockName ].forEach( ( style ) => {
			unregisterBlockStyle( blockName, style );
		} );
	} );

	Object.keys( register ).forEach( ( blockName ) => {
		register[ blockName ].forEach( ( blockStyle ) => {
			let name = '';
			let label = '';

			if ( typeof blockStyle === 'string' ) {
				name = blockStyle;
				label = ucWords( replaceAll( blockStyle, '-', ' ' ) );
			} else {
				name = Object.keys( blockStyle )[ 0 ];
				label = Object.values( blockStyle )[ 0 ];
			}

			registerBlockStyle( blockName, {
				name,
				label,
			} );
		} );
	} );
} );
