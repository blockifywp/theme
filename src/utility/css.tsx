import { toKebabCase, replaceAll } from './string';

export const cssObjectToString = ( css: style ): string => {
	return Object.keys( css ).map( ( key ) => {
		const property = key?.includes( '-' ) ? key : toKebabCase( key );

		return `${ property }:${ css[ key ] };`;
	} ).join( ' ' );
};

export const cssStringToObject = ( css: string ): style => {
	const cssObject: style = {};

	css.split( ';' ).map( ( rule: string ) => {
		const [ key, value ] = rule.split( ':' );

		if ( key ) {
			cssObject[ key ] = value;
		}

		return null;
	} );

	return cssObject;
};

export const formatCustomProperty = ( property: string ): string => {
	if ( ! property.includes( 'var:' ) ) {
		return property;
	}

	property = property.replace( 'var:', 'var(--wp--' );
	property = replaceAll( property, '|', '--' );

	return property + ')';
};
