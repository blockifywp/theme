import {
	PanelBody,
	PanelRow,
	TextareaControl,
	// @ts-ignore
	__experimentalNumberControl as NumberControl,
	FlexItem,
	FlexBlock, Flex,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { renderToString } from '@wordpress/element';
import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { rotateRight } from '@wordpress/icons';
import domReady from '@wordpress/dom-ready';
import { getBlock } from '@wordpress/block-editor/store/selectors';

const isStyleCurvedText = ( attributes: attributes ) => {
	if ( ! attributes?.className || typeof attributes?.className !== 'string' ) {
		return false;
	}

	return attributes?.className?.includes( 'is-style-curved-text' );
};

const defaults = {
	content: __( 'Add your text here. Add your text here.', 'blockify' ),
	containerSize: '150',
	pathSize: '100',
};

const curvedTextVariation: BlockVariation = {
	name: 'curved-text',
	title: __( 'Curved Text', 'blockify' ),
	icon: rotateRight,
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'text',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert curved text around circular SVG path.', 'blockify' ),
	attributes: {
		className: 'is-style-curved-text',
	},
	isActive: ( blockAttributes ) => {
		return blockAttributes?.className?.includes( 'is-style-curved-text' );
	},
};

domReady( () => {
	registerBlockVariation( 'core/paragraph', curvedTextVariation );
} );

addFilter(
	'blocks.registerBlockType',
	'blockify/curved-text-attributes',
	( props: blockProps, name: string ): blockProps => {
		if ( name === 'core/paragraph' ) {
			props = {
				...props,
				attributes: {
					...props?.attributes,
					curvedText: {
						type: 'object',
					},
				},
			};
		}

		return props;
	}
);

const CurvedText = ( attributes: attributes = {} ) => {
	const { curvedText = defaults } = attributes;

	const text: string = curvedText?.content ?? defaults.content;
	const container: string = curvedText?.containerSize ?? defaults.containerSize;
	const path: string = curvedText?.pathSize ?? defaults.pathSize;
	const halfContainer: number = parseInt( container ) / 2;
	const halfPath: number = parseInt( path ) / 2;

	const svgProps = {
		viewBox: `0 0 ${ container } ${ container }`,
		xmlns: 'http://www.w3.org/2000/svg',
		enableBackground: `new 0 0 ${ container } ${ container }`,
		xmlSpace: 'preserve',
		width: container,
		height: container,
		contentEditable: false,
		x: 0,
		y: 0,
	};

	const circleId = Date.now() + Math.random();

	const pathProps = {
		id: 'circle-' + circleId,
		d: `M ${ halfContainer }, ${ halfContainer } m -${ halfPath }, 0 a ${ halfPath },${ halfPath } 0 0,1 ${ path },0 a ${ halfPath },${ halfPath } 0 0,1 -${ path },0`,
		fill: 'transparent',
	};

	return (
		<svg { ...svgProps }>
			<path { ...pathProps }>{ ' ' }</path>
			<text fill={ 'currentColor' }>
				<textPath xlinkHref={ '#circle-' + circleId }>
					{ text }
				</textPath>
			</text>
		</svg>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/with-curved-text-css',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, setAttributes } = props;

			if ( ! isStyleCurvedText( attributes ) ) {
				return <BlockEdit { ...props } />;
			}

			const iframe = document.getElementsByClassName( 'edit-site-visual-editor__editor-canvas' )?.item( 0 ) as HTMLIFrameElement;
			const editorCanvas = document.getElementsByName( 'editor-canvas' )?.item( 0 ) as HTMLIFrameElement;

			let editorDocument: HTMLDocument;

			if ( iframe ) {
				editorDocument = iframe.contentDocument as HTMLDocument;
			} else if ( editorCanvas ) {
				editorDocument = editorCanvas.contentDocument as HTMLDocument;
			} else {
				editorDocument = document;
			}

			if ( ! editorDocument ) {
				return <BlockEdit { ...props } />;
			}

			const p = editorDocument?.getElementById( 'block-' + props?.clientId ) as HTMLParagraphElement;

			if ( p ) {
				// Force update content.
				p.innerHTML = renderToString( CurvedText( {
					...attributes,
					clientId: props.clientId,
				} ) );
			}

			const { curvedText = defaults } = attributes;

			const applyChanges = ( newAttributes: { [key: string]: any } ) => {
				const merged = {
					...newAttributes,
					svgString: renderToString( <CurvedText { ...{
						...attributes,
						...newAttributes,
						clientId: props.clientId ?? '1',
					} } /> ),
				};

				setAttributes( {
					curvedText: {
						...curvedText,
						...merged,
					},
				} );
			};

			return (
				<>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody
							className={ 'blockify-controls' }
							title={ __( 'Curved Text', 'blockify' ) }
						>
							<TextareaControl
								label={ __( 'Content', 'blockify' ) }
								value={ curvedText?.content ?? defaults.content }
								onChange={ ( value: string ) => {
									applyChanges( {
										content: value,
									} );
								} }
							/>
							<p>{ __( 'Size', 'blockify' ) }</p>
							<PanelRow>
								<br />
								<Flex>
									<FlexItem style={ { width: '50%' } }>
										<NumberControl
											label={ __( 'Container', 'blockify' ) }
											value={ curvedText?.containerSize ?? defaults.containerSize }
											onChange={ ( value: string ) => {
												applyChanges( {
													containerSize: value,
												} );
											} }
										/>
									</FlexItem>
									<FlexBlock>
										<NumberControl
											label={ __( 'Path', 'blockify' ) }
											value={ curvedText?.pathSize ?? defaults.pathSize }
											onChange={ ( value: string ) => {
												applyChanges( {
													pathSize: value,
												} );
											} }
										/>
									</FlexBlock>
								</Flex>
							</PanelRow>
						</PanelBody>
					</InspectorControls>
				</>
			);
		};
	}, 'withCurvedTextSettings' ),
	9
);
