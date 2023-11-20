import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { CSSProperties } from 'react';
import { formatCustomProperty } from '../utility';

addFilter(
	'editor.BlockListBlock',
	'blockify/with-block-gap',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			if ( props?.name !== 'core/post-template' ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! props?.attributes?.style?.spacing?.blockGap ) {
				return <BlockListBlock { ...props } />;
			}
			const wrapperProps = props.wrapperProps ?? {};

			wrapperProps.style = {
				...wrapperProps.style,
				'--wp--style--block-gap': formatCustomProperty( props.attributes.style.spacing.blockGap ),
			} as CSSProperties;

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		},
		'withBlockGap'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/apply-block-gap',
	( props, blockType: string, attributes ): object => {
		if ( blockType === 'core/post-template' && attributes?.style?.spacing?.blockGap ) {
			props = {
				...props,
				style: {
					...props.style,
					'--wp--style--block-gap': formatCustomProperty( attributes.style.spacing.blockGap ),
				},
			};
		}

		return props;
	}
);
