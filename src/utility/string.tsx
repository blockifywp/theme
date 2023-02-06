export const ucWords = ( str: string ) => {
	if ( ! str ) {
		return '';
	}

	return str?.toLowerCase()?.replace( /(?<= )[^\s]|^./g, ( a: string ) => a?.toUpperCase() );
};

export const ucFirst = ( str: string ) => str.charAt( 0 ).toUpperCase() + str.slice( 1 );

export const toKebabCase = ( str: string ) => {
	if ( ! str ) {
		return '';
	}

	return str?.match( /[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g )?.join( '-' )?.toLowerCase() ?? ''
};

export default { ucWords, ucFirst, toKebabCase };
