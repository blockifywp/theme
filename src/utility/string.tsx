export const ucWords = ( str: string ) => {
	if ( ! str ) {
		return '';
	}

	return str?.toLowerCase()?.replace( /(?<= )[^\s]|^./g, ( a: string ) => a?.toUpperCase() );
};

export const ucFirst = ( str: string ) => str.charAt( 0 ).toUpperCase() + str.slice( 1 );

// Attempts to convert all case types to `kebab-case`.
export const toKebabCase = ( str: string ) => {
	if ( ! str ) {
		return '';
	}

	return str?.match( /[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g )?.join( '-' )?.toLowerCase() ?? '';
};

export const replaceAll = ( str = '', find: string | string[], replace: string ) => {
	if ( typeof find === 'string' ) {
		return str?.split( find )?.join( replace );
	}

	for ( const f of find ) {
		str = replaceAll( str, f, replace );
	}

	return str;
};

export const camelCaseToWords = ( slug: string ) => {
	const result = slug.replace( /([A-Z])/g, ' $1' );
	return result.charAt( 0 ).toUpperCase() + result.slice( 1 );
};

export const toTitleCase = ( str: string ): string => {
	return ucWords( replaceAll( camelCaseToWords( str ), [ '-', '_' ], ' ' ) );
};
