import { __ } from '@wordpress/i18n';
import { code } from '@wordpress/icons';
import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	TextareaControl,
	PanelBody,
	PanelRow, ExternalLink, ToggleControl,
} from '@wordpress/components';
import domReady from '@wordpress/dom-ready';

const svgVariation: BlockVariation = {
	name: 'svg',
	title: __( 'SVG', 'blockify' ),
	icon: code,
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'media',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert an inline SVG.', 'blockify' ),
	attributes: {
		className: 'is-style-svg',
	},
	isActive: ( blockAttributes ): boolean => {
		if ( ! blockAttributes?.className ) {
			return false;
		}

		return blockAttributes?.className && blockAttributes?.className?.includes( 'is-style-svg' );
	},
};

domReady( () => {
	registerBlockVariation( 'core/image', svgVariation );
} );

const formatUrl = ( value: string ): string => {
	return "url(\'data:image/svg+xml;utf8," + encodeURIComponent( value ) + "\')";
};

const formatSrc = ( value: string ): string => {
	return 'data:image/svg+xml;utf8,' + encodeURIComponent( value );
};

addFilter(
	'editor.BlockEdit',
	'blockify/with-svg-controls',
	createHigherOrderComponent( ( BlockEdit ) => ( props: blockProps ) => {
		const { attributes, setAttributes } = props;

		if ( ! attributes?.className?.includes( 'is-style-svg' ) ) {
			return <BlockEdit { ...props } />;
		}

		const { style } = attributes;
		const svgString = style?.svgString ?? '';
		const maskSvg = style?.maskSvg ?? false;

		if ( ! attributes?.url ) {
			setAttributes( {
				url: maskSvg ? '#' : formatSrc( svgString ),
			} );
		}

		if ( maskSvg && attributes?.url !== '#' ) {
			setAttributes( {
				url: '#',
			} );
		}

		if ( ! maskSvg && ! attributes?.url?.includes( 'data:image/svg+xml;utf8,' ) ) {
			setAttributes( {
				url: formatSrc( svgString ),
			} );
		}

		let width = 'var(--width,1em)';
		let height = '';

		if ( attributes?.width ) {
			width = attributes.width + 'px';
		}

		if ( attributes?.height ) {
			height = ( attributes.height ?? '' ) + 'px';
		}

		height = height === '' ? width : height;

		const styleObject = {
			width,
			height,
			display: 'inline-flex',
			background: 'currentColor',
			overflow: 'hidden',
			'-webkit-mask-repeat': 'no-repeat',
			'-mask-repeat': 'no-repeat',
			'-webkit-mask-size': '100% 100%',
			'-mask-size': '100% 100%',
			'-webkit-mask-position': 'center',
			'-mask-position': 'center bottom',
			'-webkit-mask-image': formatUrl( svgString ),
			'-mask-image': formatUrl( svgString ),
		};

		let styles = '';

		if ( svgString ) {
			styles = Object.entries( styleObject ).map( ( [ key, value ] ) => `${ key }:${ value };` ).join( '' );
		}

		return (
			<>
				{ maskSvg && (
					<style>{ '#block-' + props?.clientId + '>div:first-of-type{' + styles + '}' }</style>
				) }
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'SVG Settings', 'blockify-pro' ) }
						className={ __( 'blockify-svg-controls', 'blockify-pro' ) }
					>
						<PanelRow>
							<TextareaControl
								label={ __( 'SVG String', 'blockify' ) }
								help={ __( 'Paste your SVG string in the field above. It is recommended to format your SVG with an optimization tool ', 'blockify' ) }
								value={ svgString ?? '' }
								rows={ 20 }
								onChange={ ( value: string ) => {
									const newAttributes: attributes = {
										style: {
											...style,
											svgString: value,
										},
									};

									if ( maskSvg ) {
										newAttributes.url = '#';
									} else {
										newAttributes.url = formatSrc( value );
									}

									setAttributes( newAttributes );
								} }
								style={ {
									fontFamily: 'var(--wp--preset--font-family--monospace, monospace)',
								} }
							/>
						</PanelRow>
						<ExternalLink
							href={ 'https://jakearchibald.github.io/svgomg/' }
							target={ '_blank' }
						>
							{ 'https://jakearchibald.github.io/svgomg/' }
						</ExternalLink>
						<PanelRow>
							<ToggleControl
								label={ __( 'Mask with text color', 'blockify' ) }
								help={ __( 'If enabled, the SVG will be masked with the text color. (Renders inline SVG on front end).', 'blockify' ) }
								checked={ maskSvg }
								onChange={
									( value: boolean ) => {
										const newAttributes: attributes = {
											style: {
												...style,
												maskSvg: value,
											},
										};

										if ( maskSvg ) {
											newAttributes.url = '#';
										} else {
											newAttributes.url = formatSrc( svgString );
										}

										setAttributes( newAttributes );
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			</>
		);
	}, 'withSvgControls' ),
	9
);
