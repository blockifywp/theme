import { replaceAll, toKebabCase } from './string';

export const cssObjectToString = ( css: genericStrings ): string => {
	return Object.keys( css ).map( ( key ) => {
		const property = key?.includes( '-' ) ? key : toKebabCase( key );

		return `${ property }:${ css[ key ] };`;
	} ).join( ' ' );
};

export const cssStringToObject = ( css: string ): genericStrings => {
	const cssObject: genericStrings = {};

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

export const addClassName = ( classes = '', newClasses = '' ): string => {
	if ( ! newClasses ) {
		return classes;
	}

	const classList = classes.trim().split( ' ' );
	const newClassList = newClasses.trim().split( ' ' );

	newClassList.forEach( ( className ) => {
		if ( ! classList.includes( className ) ) {
			classList.push( className );
		}
	} );

	return classList.join( ' ' );
};

export const unitsWithAuto = [
	{ value: 'px', label: 'px' },
	{ value: '%', label: '%' },
	{ value: 'em', label: 'em' },
	{ value: 'rem', label: 'rem' },
	{ value: 'vw', label: 'vw' },
	{ value: 'vh', label: 'vh' },
	{ value: 'auto', label: 'auto' },
];
