import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { formatCustomProperty } from '../utility';

const blockSupports: { [name: string]: any } =
	window?.blockify?.blockSupports ?? {};

addFilter(
	'blocks.registerBlockType',
	'blockify/block-supports',
	( settings, name ) => {
		if ( Object.keys( blockSupports ).includes( name ) ) {
			settings.supports = {
				...settings.supports,
				...blockSupports[ name ],
			};
		}

		return settings;
	},
	0
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/alignment-class',
	( extraProps, blockType, attributes ) => {
		if (
			Object.keys( blockSupports ).includes( blockType.name ) &&
			attributes?.align &&
			! extraProps.className.includes( ' align' )
		) {
			extraProps.className += ' align' + attributes.align;
		}

		return extraProps;
	}
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-block-gap-css',
	createHigherOrderComponent(
		( BlockListBlock ) => {
			return ( props: blockProps ) => {
				const defaultReturn = <BlockListBlock { ...props } />;

				if ( props.name !== 'core/page-list' && props.name !== 'core/button' ) {
					return defaultReturn;
				}

				const blockGap = props?.attributes?.style?.spacing?.blockGap ?? '';

				if ( ! blockGap ) {
					return defaultReturn;
				}

				props = {
					...props,
					style: {
						...props.style ?? {},
						'--wp--style--block-gap': formatCustomProperty( blockGap ),
					},
				};

				const wrapperProps = {
					...props.wrapperProps,
					style: {
						...props.wrapperProps?.style,
						'--wp--style--block-gap': formatCustomProperty( blockGap ),
					},
				};

				return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
			};
		},
		'withBlockGapCss'
	)
);

