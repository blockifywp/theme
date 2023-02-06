import { addFilter } from "@wordpress/hooks";
import { createHigherOrderComponent } from "@wordpress/compose";

addFilter(
	'editor.BlockEdit',
	'blockify/with-client-id',
	createHigherOrderComponent( BlockEdit => {
		return ( props: blockProps ) => {

			if ( props?.name === 'core/navigation-submenu' ) {
				props.setAttributes( {
					clientId: props?.clientId
				} );
			}

			return <BlockEdit { ...props } />;
		};
	}, 'withClientId' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-mega-menu',
	createHigherOrderComponent( BlockListBlock => {
			return ( props: blockProps ) => {
				const { attributes, name, clientId } = props;

				if ( name !== 'core/navigation-submenu' ) {
					return <BlockListBlock { ...props }/>;
				}

				let styles: { [property: string]: any } = {};

				if ( attributes?.backgroundColor ) {
					styles['--wp--custom--submenu--background'] = 'var(--wp--preset--color--' + attributes?.backgroundColor + ')';
				}

				if ( attributes?.style?.color?.background ) {
					styles['--wp--custom--submenu--background'] = attributes?.style?.color?.background;
				}

				let wrapperProps: { [property: string]: any } = { ...props?.wrapperProps };

				wrapperProps['data-id'] = clientId;

				if ( styles ) {
					wrapperProps.style = { ...wrapperProps?.style, ...styles };
				}

				return <BlockListBlock { ...props } wrapperProps={ wrapperProps }/>;
			};
		},
		'withMegaMenu'
	)
);
