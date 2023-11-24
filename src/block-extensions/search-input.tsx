import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import {
	__experimentalColorGradientSettingsDropdown as ColorGradientSettingsDropdown,
	__experimentalUseMultipleOriginColorsAndGradients as useMultipleOriginColorsAndGradients,
	InspectorControls,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import {
	getColorSlugFromValue,
	getColorValueFromSlug,
} from '../utility/color.tsx';

addFilter(
	'blocks.registerBlockType',
	'blockify/search-input-colors',
	( settings, name ) => {
		if ( name !== 'core/search' ) {
			return settings;
		}

		settings.attributes = {
			...settings.attributes,
			inputBackgroundColor: {
				type: 'string',
			},
		};

		return settings;
	}
);

addFilter(
	'editor.BlockEdit',
	'blockify/search-input-colors',
	createHigherOrderComponent(
		( BlockEdit ) => {
			return ( props: any ) => {
				const defaultReturn = <BlockEdit { ...props } />;

				const colorGradientSettings = useMultipleOriginColorsAndGradients();

				if ( props.name !== 'core/search' ) {
					return defaultReturn;
				}

				const {
					attributes,
					setAttributes,
					clientId,
				} = props;

				const {
					inputBackgroundColor,
				} = attributes;

				const settings = [ {
					label: __( 'Input Background', 'blockify' ),
					colorValue: ( typeof inputBackgroundColor === 'string' && inputBackgroundColor?.includes( '-' ) ) ? getColorValueFromSlug( inputBackgroundColor ) : inputBackgroundColor,
					onColorChange: ( value: string ) => {
						const slug = getColorSlugFromValue( value );

						setAttributes( {
							inputBackgroundColor: slug ? slug : value,
						} );
					},
				} ];

				return (
					<>
						<BlockEdit { ...props } />
						<InspectorControls
							group={ 'color' }
						>
							<ColorGradientSettingsDropdown
								settings={ settings }
								panelId={ clientId }
								hasColorsOrGradients={ true }
								disableCustomColors={ false }
								__experimentalIsRenderedInSidebar
								{ ...colorGradientSettings }
							/>
						</InspectorControls>
					</>
				);
			};
		},
		'withSearchInputColors'
	)
);

addFilter(
	'editor.BlockListBlock',
	'blockify/search-input-colors',
	createHigherOrderComponent(
		( BlockListBlock ) => {
			return ( props: any ) => {
				const defaultReturn = <BlockListBlock { ...props } />;

				if ( props.name !== 'core/search' ) {
					return defaultReturn;
				}

				const {
					attributes,
					wrapperProps = {},
				} = props;

				const {
					inputBackgroundColor,
					borderColor,
				} = attributes;

				if ( inputBackgroundColor ) {
					const colorValue = inputBackgroundColor?.includes( '-' ) ? `var(--wp--preset--color--${ inputBackgroundColor })` : inputBackgroundColor;

					wrapperProps.style = {
						...wrapperProps.style,
						'--wp--custom--input--background': colorValue,
					};
				}

				if ( borderColor ) {
					const colorValue = borderColor?.includes( '-' ) ? `var(--wp--preset--color--${ borderColor })` : borderColor;

					wrapperProps.style = {
						...wrapperProps.style,
						'--wp--custom--input--border': `var(--wp--custom--border--width,1px) var(--wp--custom--border--style,solid) ${ colorValue }`,
					};
				}

				return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
			};
		},
		'withSearchInputColors'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/search-input-colors',
	( extraProps, blockType, attributes ) => {
		if ( blockType.name !== 'core/search' ) {
			return extraProps;
		}

		const {
			inputBackgroundColor,
		} = attributes;

		if ( inputBackgroundColor ) {
			const colorValue = inputBackgroundColor?.includes( '-' ) ? `var(--wp--preset--color--${ inputBackgroundColor })` : inputBackgroundColor;

			extraProps.style = {
				...extraProps.style,
				'--wp--custom--input--background': colorValue,
			};
		}

		return extraProps;
	}
);
