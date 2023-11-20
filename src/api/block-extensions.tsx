import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { toKebabCase } from '../utility/string';

const blockSupports = window?.blockify?.blockSupports ?? {};

export const supportsPosition = ( name: string ): boolean => blockSupports?.[ name ]?.blockifyPosition ?? false;

const config: extensionOptions = window?.blockify?.extensionOptions ?? {};

addFilter(
	'blocks.registerBlockType',
	'blockify/add-position-attributes',
	( props, name ): object => {
		if ( supportsPosition( name ) ) {
			const newAttributes: { [key: string]: object } = {};

			Object.keys( config ).forEach( ( key ) => {
				newAttributes[ key ] = {
					type: 'object',
				};
			} );

			props.attributes = {
				...props.attributes,
				style: {
					...newAttributes,
					...( props?.attributes?.style ?? {} ),
				},
			};
		}

		return props;
	},
	0
);

const getClasses = ( attributes: attributes ): string[] => {
	const classes: string[] = [];
	const style = attributes?.style ?? {};

	Object.keys( config ).forEach( ( key: string ) => {
		const property = toKebabCase( key );

		if ( config?.[ key ]?.options ) {
			if ( style?.[ key ]?.all ?? null ) {
				classes.push( `has-${ property }-${ toKebabCase( style?.[ key ]?.all ) }` );
			}

			if ( style?.[ key ]?.mobile ?? null ) {
				classes.push( `has-${ property }-${ toKebabCase( style?.[ key ]?.mobile ) }-mobile` );
			}

			if ( style?.[ key ]?.desktop ?? null ) {
				classes.push( `has-${ property }-${ toKebabCase( style?.[ key ]?.desktop ) }-desktop` );
			}
		} else if ( style?.[ key ] ) {
			classes.push( `has-${ property }` );
		}
	} );

	return classes;
};

const getStyles = ( attributes: attributes ): object => {
	const styles: { [name: string]: string } = {};

	const style = attributes?.style ?? {};

	Object.keys( config ).forEach( ( key: string ) => {
		if ( config?.[ key ]?.options ) {
			return;
		}

		const property = toKebabCase( key );

		if ( style?.[ key ]?.all ?? null ) {
			styles[ `--${ property }` ] = style?.[ key ]?.all;
		}

		if ( style?.[ key ]?.mobile ?? null ) {
			styles[ `--${ property }-mobile` ] = style?.[ key ]?.mobile;
		}

		if ( style?.[ key ]?.desktop ?? null ) {
			styles[ `--${ property }-desktop` ] = style?.[ key ]?.desktop;
		}
	} );

	return styles;
};

addFilter(
	'editor.BlockListBlock',
	'blockify/with-position-style',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { name, attributes } = props;

			if ( ! supportsPosition( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			const classes = getClasses( attributes );
			const styles = getStyles( attributes );
			const wrapperProps = props?.wrapperProps ?? {};

			props = {
				...props,
				style: { ...props?.style, ...styles },
			};

			if ( wrapperProps ) {
				wrapperProps.style = { ...wrapperProps?.style, ...styles };
			}

			classes.forEach( ( className: string ) => {
				if ( ! props?.className?.includes( className ) ) {
					props.className = props?.className + ' ' + className;
				}
			} );

			props.wrapperProps = wrapperProps;

			return <BlockListBlock { ...props } />;
		};
	}, 'withPositionStyle' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-position-style',
	( props: blockProps ) => {
		const { name, attributes } = props;

		if ( ! blockSupports?.[ name ]?.blockifyPosition ) {
			return props;
		}

		const classes = getClasses( attributes );
		const styles = getStyles( attributes );

		classes.forEach( ( className: string ) => {
			if ( ! props?.className?.includes( className ) ) {
				props.className = props?.className + ' ' + className;
			}
		} );

		props = {
			...props,
			style: {
				...props?.style,
				...styles,
			},
		};

		return props;
	},
	11
);
