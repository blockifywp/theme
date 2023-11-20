import { CSSProperties } from 'react';

export const Description = ( { children, style }: {
	children:string | JSX.Element,
	style?: CSSProperties
} ) => {
	return <p style={ {
		fontSize: '12px',
		color: 'rgb(117, 117, 117)',
		...style,
	} }>
		{ children }
	</p>;
};
