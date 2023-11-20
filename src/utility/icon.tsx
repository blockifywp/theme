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
