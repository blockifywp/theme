import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	__experimentalNumberControl as NumberControl,
	PanelBody,
	PanelRow,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { replaceAll } from '../utility';
import { addClassName } from '../utility/css';

addFilter(
	'blocks.registerBlockType',
	'blockify/content-limit-attributes',
	( props, name: string ): void => {
		if ( name === 'core/post-excerpt' ) {
			props = {
				...props,
				attributes: {
					...props.attributes,
					defaultExcerpt: {
						type: 'string',
					},
					hideReadMore: {
						type: 'boolean',
					},
				},
			};
		}

		if ( name === 'core/post-content' ) {
			props = {
				...props,
				attributes: {
					...props.attributes,
					contentLimit: {
						type: 'number',
					},
				},
			};
		}

		return props;
	}
);

addFilter(
	'editor.BlockEdit',
	'blockify/with-content-limit-controls',
	createHigherOrderComponent( ( BlockEdit: any ) => ( props: any ) => {
		const { attributes, setAttributes, name } = props;

		if ( name !== 'core/post-excerpt' && name !== 'core/post-content' ) {
			return <BlockEdit { ...props } />;
		}

		return (
			<>
				<InspectorControls>
					<PanelBody title={ __( 'Content', 'blockify' ) }>
						{ name === 'core/post-excerpt' && ( <>
							<PanelRow>
								<TextareaControl
									label={ __( 'Default Content', 'blockify' ) }
									value={ attributes.defaultExcerpt }
									onChange={ ( value: string ) => {
										setAttributes( { defaultExcerpt: value } );
									} }
								/>
							</PanelRow>
							<PanelRow>
								<ToggleControl
									label={ __( 'Hide Read More Link', 'blockify' ) }
									checked={ attributes.hideReadMore }
									onChange={ ( value: boolean ) => {
										setAttributes( { hideReadMore: value } );
									} }
								/>
							</PanelRow>
						</>
						) }
						{ name === 'core/post-content' && ( <>
							<PanelRow>
								<NumberControl
									label={ __( 'Content Limit', 'blockify' ) }
									help={ __( 'Limit content to specific number of words.', 'blockify' ) }
									value={ attributes?.contentLimit }
									onChange={ ( value: number ) => {
										setAttributes( {
											contentLimit: value,
										} );
									} }
								/>
							</PanelRow>
						</>
						) }
					</PanelBody>
				</InspectorControls>
				<BlockEdit { ...props } />
			</>
		);
	}, 'withContentLimitControls' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-content-limit',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			const { name, attributes } = props;

			if ( name !== 'core/post-excerpt' ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! attributes?.hideReadMore ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = props.wrapperProps ?? {};
			const className = 'hide-read-more';

			wrapperProps.className = addClassName( wrapperProps?.className, className );

			return <BlockListBlock
				{ ...{
					...props,
					className: ( props?.className ?? '' ) + ' ' + className,
				} }
				wrapperProps={ wrapperProps }
			/>;
		},
		'withContentLimit'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/content-limit-save',
	( props: blockProps ) => {
		const { name, attributes } = props;

		if ( name === 'core/post-excerpt' && attributes?.hideReadMore ) {
			const className = 'hide-read-more';

			props.className = replaceAll( props?.className, className, '' ) + ' ' + className;
		}

		return props;
	}
);
