import { BlockAttributes } from '@wordpress/blocks';

export interface IconAttributes extends BlockAttributes {
	iconSet?: string;
	iconName?: string;
	iconColor?: string;
	iconGradient?: string;
	iconSize?: string;
	iconPosition?: string;
	iconCustomSVG?: string;
	iconSvgString?: string;
}

export const blockifyIcon = <svg
	xmlns="http://www.w3.org/2000/svg"
	viewBox="0 0 2000 2000"
>
	<path
		fill="currentColor"
		d="m1729.66 534.39-691.26-399.1a76.814 76.814 0 0 0-76.81 0l-691.26 399.1a76.818 76.818 0 0 0-38.4 66.52v798.19c0 27.44 14.64 52.8 38.4 66.52l691.26 399.1c11.88 6.86 25.14 10.29 38.4 10.29s26.52-3.43 38.4-10.29l691.26-399.1a76.818 76.818 0 0 0 38.4-66.52V600.9c.01-27.44-14.63-52.79-38.39-66.51zm-115.21 820.36-539.18 311.3V998.46c0-27.45-14.65-52.81-38.43-66.53l-574.18-331.2L1000 290.49l614.45 354.75v709.51z"
	/>
</svg>;

export const defaultIcon = window?.blockify?.defaultIcon ?? {
	set: 'wordpress',
	name: 'star-empty',
	string: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M9.706 8.646a.25.25 0 01-.188.137l-4.626.672a.25.25 0 00-.139.427l3.348 3.262a.25.25 0 01.072.222l-.79 4.607a.25.25 0 00.362.264l4.138-2.176a.25.25 0 01.233 0l4.137 2.175a.25.25 0 00.363-.263l-.79-4.607a.25.25 0 01.072-.222l3.347-3.262a.25.25 0 00-.139-.427l-4.626-.672a.25.25 0 01-.188-.137l-2.069-4.192a.25.25 0 00-.448 0L9.706 8.646zM12 7.39l-.948 1.921a1.75 1.75 0 01-1.317.957l-2.12.308 1.534 1.495c.412.402.6.982.503 1.55l-.362 2.11 1.896-.997a1.75 1.75 0 011.629 0l1.895.997-.362-2.11a1.75 1.75 0 01.504-1.55l1.533-1.495-2.12-.308a1.75 1.75 0 01-1.317-.957L12 7.39z" clip-rule="evenodd"> </path></svg>',
};

export const getIconStyles = ( attributes: IconAttributes ) => {
	const styles: genericStrings = {};
	let background = '';

	const isIcon = attributes?.className?.includes( 'is-style-icon' );

	if ( ( ! attributes?.iconSet || ! attributes?.iconName ) && ! isIcon ) {
		return styles;
	}

	if ( attributes?.style?.color?.background ) {
		background = attributes.style.color.background;
	}

	if ( attributes?.backgroundColor ) {
		background = 'var(--wp--preset--color--' + attributes.backgroundColor + ', currentColor)';
	}

	if ( attributes?.iconPosition === 'start' ) {
		styles[ '--wp--custom--icon--order' ] = '-1';
	}

	let gradient = '';

	if ( attributes?.style?.color?.gradient ) {
		gradient = attributes?.style?.color?.gradient;
	}

	if ( attributes?.gradient ) {
		gradient = 'var(--wp--preset--gradient--' + attributes.gradient + ',currentColor)';
	}

	let text = '';

	if ( attributes?.style?.color?.text ) {
		text = attributes?.style?.color?.text;
	}

	if ( attributes?.textColor ) {
		text = 'var(--wp--preset--color--' + attributes.textColor + ',currentColor)';
	}

	if ( background !== '' ) {
		styles[ '--wp--custom--icon--background' ] = background;
	}

	if ( text ) {
		styles[ '--wp--custom--icon--color' ] = text;

		if ( gradient ) {
			styles[ '--wp--custom--icon--background' ] = gradient;
		}
	} else if ( gradient ) {
		styles[ '--wp--custom--icon--color' ] = gradient;
	}

	if ( attributes?.style?.spacing?.padding ) {
		const padding = attributes.style.spacing.padding;

		const paddingObject: { [side: string]: string } = {
			top: padding?.top ?? '0',
			right: padding?.right ?? '0',
			bottom: padding?.bottom ?? '0',
			left: padding?.left ?? '0',
		};

		// Support spacing scale.
		Object.keys( paddingObject ).forEach( ( side: string ) => {
			const value: string = paddingObject?.[ side ] ?? '';

			if ( value && value?.includes( 'var:preset' ) ) {
				paddingObject[ side ] = 'var(--wp--preset--spacing--' + value.replace( 'var:preset|spacing|', '' ) + ')';
			}
		} );

		styles[ '--wp--custom--icon--padding' ] = Object.values( paddingObject ).join( ' ' );
	}

	if ( attributes?.style?.spacing?.margin ) {
		const margin = attributes.style.spacing.margin;

		const marginObject: { [side: string]: string } = {
			top: margin?.top ?? '',
			right: margin?.right ?? '',
			bottom: margin?.bottom ?? '',
			left: margin?.left ?? '',
		};

		// Support spacing scale.
		Object.keys( marginObject ).forEach( ( side: string ) => {
			const value: string = marginObject?.[ side ] ?? '';

			if ( value?.includes( 'var:preset' ) ) {
				marginObject[ side ] = 'var(--wp--preset--spacing--' + value?.replace( 'var:preset|spacing|', '' ) + ')';
			}
		} );

		styles[ '--wp--custom--icon--margin' ] = Object.values( marginObject ).join( ' ' );
	}

	let borderColor = '';

	if ( attributes?.borderColor ) {
		borderColor = 'var(--wp--preset--color--' + attributes?.borderColor + ')';
	}

	if ( attributes?.style?.border?.width ) {
		styles[ '--wp--custom--icon--border-width' ] = attributes.style.border.width;
		styles[ '--wp--custom--icon--border-style' ] = attributes.style.border?.style ?? 'solid';
		styles[ '--wp--custom--icon--border-color' ] = attributes.style.border?.color ?? borderColor;
	}

	let size = attributes?.iconSize ?? '';

	if ( size !== '' ) {
		const hasUnit = [ 'px', 'em', 'rem', 'vh', 'vw', '%' ].some( ( unit ) => size?.includes( unit ) );

		size = hasUnit ? size : size + 'px';
		styles[ '--wp--custom--icon--size' ] = size;
	}

	const custom: string = ( attributes?.iconCustomSVG ?? '' )?.replace( '"', "'" );
	const svg: string = custom && custom?.includes( '<svg' ) ? custom : attributes?.iconSvgString ?? '';

	if ( svg ) {
		styles[ '--wp--custom--icon--url' ] = "url('data:image/svg+xml;utf8," + svg + "')";
	}

	return styles;
};
