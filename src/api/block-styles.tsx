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
		register[ blockName ].forEach( ( style ) => {
			registerBlockStyle( blockName, {
				name: style,
				label: ucWords( replaceAll( style, '-', ' ' ) ),
			} );
		} );
	} );
} );
