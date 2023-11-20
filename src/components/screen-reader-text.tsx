import { CSSProperties } from 'react';

export const ScreenReaderText = ( { children, style }: {
	children: string | JSX.Element,
	style?: CSSProperties
} ) => {
	return <span
		className={ 'screen-reader-text' }
		style={ style }
	>
		{ children }
	</span>;
};
