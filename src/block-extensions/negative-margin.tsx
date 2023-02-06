import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';

const blockSupports: { [name: string]: any } = window?.blockify?.blockSupports ?? {};

const supportsNegativeMargin = ( name: string ) => blockSupports?.[name]?.blockifyNegativeMargin ?? false;

addFilter(
	'editor.BlockEdit',
	'blockify/with-negative-margin',
	createHigherOrderComponent( BlockEdit => {
		return ( props: blockProps ) => {
			if ( supportsNegativeMargin( props?.name ) ) {
				const input = document.querySelector( '.components-input-control__input[min="0"]' );

				if ( input ) {
					input.setAttribute( 'min', '-999' );
				}
			}

			return <BlockEdit { ...props } />;
		};
	}, 'withMinHeightSettings' )
);
