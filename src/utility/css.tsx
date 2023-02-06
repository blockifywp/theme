import { toKebabCase } from "./string";

export const cssObjectToString = ( css: style ): string => {
	return Object.keys( css ).map( ( key ) => {
		const property = key?.includes( '-' ) ? key : toKebabCase( key );

		return `${ property }:${ css[key] };`;
	} ).join( ' ' );
}

export const cssStringToObject = ( css: string ): style => {
	const cssObject: style = {};

	css.split( ';' ).map( ( rule: string ) => {
		const [ key, value ] = rule.split( ':' );

		if ( key ) {
			cssObject[key] = value;
		}
	} );

	return cssObject;
}
