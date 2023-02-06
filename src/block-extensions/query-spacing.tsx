import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';

addFilter(
	'editor.BlockListBlock',
	'blockify/with-block-gap',
	createHigherOrderComponent(
		BlockListBlock => ( props: blockProps ) => {
			if ( props?.name !== 'core/query' ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! props?.attributes?.style?.spacing?.blockGap ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = props.wrapperProps ?? {};

			wrapperProps.style = {
				...wrapperProps.style,
				'--wp--style--block-gap': props.attributes.style.spacing.blockGap
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps }/>;
		},
		'withBlockGap'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/apply-block-gap',
	( props, blockType: string, attributes ): object => {
		if ( blockType === 'core/query' && attributes?.style?.spacing?.blockGap ) {
			props.style = {
				...props.style,
				'--wp--style--block-gap': attributes.style.spacing.blockGap
			};
		}

		return props;
	}
);
