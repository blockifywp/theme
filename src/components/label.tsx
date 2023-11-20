import { CSSProperties } from 'react';

export const Label = ( { children, style }: {
	children: string | JSX.Element,
	style?: CSSProperties
} ) => {
	return <p
		className={ 'blockify-control-label' }
		style={ {
			margin: '8px 0',
			...style,
		} }
	>
		{ children }
	</p>;
};
