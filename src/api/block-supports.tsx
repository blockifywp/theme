import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { formatCustomProperty } from '../utility/css';

const blockSupports: { [ name: string ]: any } =
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
	'blocks.registerBlockType',
	'blockify/block-attributes-search',
	( settings, name ) => {
		if ( name === 'core/search' ) {
			settings.attributes.style = {
				...settings.attributes?.style,
				spacing: {
					...settings.attributes?.style?.spacing,
					padding: {
						top: '1em',
						right: '1em',
						bottom: '1em',
						left: '2em',
					},
				},
			};
		}

		return settings;
	},
	0
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-search-padding-css',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props : blockProps ) => {
			if ( 'core/search' !== props.name ) {
				return <BlockEdit { ...props } />;
			}

			const searchInputs = document.getElementsByClassName(
				'wp-block-search__input'
			) as HTMLCollectionOf<HTMLElement>;
			const padding = props?.attributes?.style?.spacing?.padding;

			if ( searchInputs[ 0 ] && padding ) {
				if ( padding.top ) {
					searchInputs[ 0 ].style.paddingTop = padding?.top;
				}

				if ( padding.right ) {
					searchInputs[ 0 ].style.paddingRight = padding?.right;
				}

				if ( padding.bottom ) {
					searchInputs[ 0 ].style.paddingBottom = padding?.bottom;
				}
				if ( padding.left ) {
					searchInputs[ 0 ].style.paddingLeft = padding?.left;
				}
			}

			return <BlockEdit { ...props } />;
		};
	}, 'withSearchPaddingCss' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-block-gap-css',
	createHigherOrderComponent(
		( BlockListBlock ) => {
			return ( props: blockProps ) => {
				const defaultReturn = <BlockListBlock { ...props } />;

				if ( props.name !== 'core/page-list' ) {
					return defaultReturn;
				}

				const blockGap = props?.attributes?.style?.spacing?.blockGap ?? '';

				if ( ! blockGap ) {
					return defaultReturn;
				}

				props.style = {
					...props.style ?? {},
					'--wp--style--block-gap': formatCustomProperty( blockGap ),
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

