import { select } from '@wordpress/data';
import { ColorPalette } from '@wordpress/components';
import Color = ColorPalette.Color;

export const getColorSlugFromValue = ( value: string ): string => {
	const colors: Color[] = select( 'core/block-editor' )?.getSettings().colors ?? [];

	const color = colors.find( ( color ) => color.color === value );

	return color?.slug ?? '';
};

export const getColorValueFromSlug = ( slug: string ): string => {
	const colors: Color[] = select( 'core/block-editor' )?.getSettings().colors ?? [];

	const color = colors.find( ( color ) => color.slug === slug );

	return color?.color ?? '';
};
