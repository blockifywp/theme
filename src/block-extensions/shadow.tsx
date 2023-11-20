import { __ } from '@wordpress/i18n';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addFilter } from '@wordpress/hooks';
import { PanelBody } from '@wordpress/components';
import { EditorColor, InspectorControls } from '@wordpress/block-editor';
import { BoxShadowControl } from '../components';
import { addClassName } from '../utility/css';
import { select } from '@wordpress/data';

export const supportsShadow = ( name: string ): boolean =>
	window?.blockify?.blockSupports?.[ name ]?.blockifyBoxShadow ?? false;

interface BoxShadowValues {
	[key: string]: string | number | boolean | undefined | BoxShadowValues;

	inset?: boolean;
	x?: number;
	y?: number;
	blur?: number;
	spread?: number;
	color?: string;
}

interface BoxShadow extends BoxShadowValues {
	hover?: BoxShadowValues;
}

addFilter(
	'blocks.registerBlockType',
	'blockify/add-box-shadow-attributes',
	( props, name ): object => {
		if ( supportsShadow( name ) ) {
			props.attributes = {
				...props.attributes,
				shadowPreset: {
					type: 'string',
				},
				shadowPresetHover: {
					type: 'string',
				},
				useCustomBoxShadow: {
					type: 'boolean',
				},
				style: {
					...( props?.attributes?.style ?? {} ),
					boxShadow: {
						type: 'object',
					},
				},
			};
		}

		return props;
	},
	0
);

const getStyles = ( attributes: {
	style?: {
		boxShadow?: BoxShadow;
	};
} ): genericStrings => {
	const boxShadow: BoxShadow = attributes?.style?.boxShadow ?? {};

	const style: genericStrings = {};

	const colorPalette: EditorColor[] = select( 'core/block-editor' ).getSettings().colors ?? [];

	const units: { [property: string]: string } = {
		inset: '',
		x: 'px',
		y: 'px',
		blur: 'px',
		spread: 'px',
		color: '',
	};

	Object.keys( units ).map( ( key: string ) => {
		if ( boxShadow?.[ key ] || boxShadow?.[ key ]?.toString() === '0' ) {
			style[ '--wp--custom--box-shadow--' + key ] = boxShadow?.[ key ] + units?.[ key ];
		}

		if (
			boxShadow?.hover?.[ key ] ||
			boxShadow?.hover?.[ key ]?.toString() === '0'
		) {
			style[ '--wp--custom--box-shadow--hover--' + key ] = boxShadow?.hover?.[ key ] + units?.[ key ];
		}

		return true;
	} );

	return style;
};

addFilter(
	'editor.BlockListBlock',
	'blockify/edit-box-shadow-styles',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { attributes, name } = props;

			if ( ! supportsShadow( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			const styles = getStyles( attributes );
			const hasPreset = attributes?.shadowPreset || attributes?.shadowPresetHover;

			if ( ! hasPreset && ! Object.keys( styles ).length ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = { ...props.wrapperProps };

			let className = props.className;

			className = addClassName( className, wrapperProps.className );

			className = addClassName( className, 'has-box-shadow' );

			if ( hasPreset ) {
				className = addClassName( className, 'has-shadow' ).replace( 'has-box-shadow', '' );
			}

			if ( attributes?.shadowPreset ) {
				className = addClassName( className, `has-${ attributes.shadowPreset }-shadow` );
			}

			if ( attributes?.shadowPresetHover ) {
				className = addClassName( className, `has-${ attributes.shadowPresetHover }-shadow-hover` );
			}

			props = {
				...props,
				className: addClassName( props?.className, className ),
				style: {
					...props.style,
					...styles,
				},
			};

			wrapperProps.className = className;

			wrapperProps.style = {
				...wrapperProps.style,
				...styles,
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		};
	}, 'withBoxShadow' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/save-box-shadow-styles',
	( props, block, attributes ): blockProps => {
		const { name } = block;

		if ( ! supportsShadow( name ) ) {
			return props;
		}

		const styles = getStyles( attributes );

		const hasPreset = attributes?.shadowPreset || attributes?.shadowPresetHover;

		if ( ! hasPreset && ! Object.keys( styles ).length ) {
			return props;
		}

		let className = addClassName( props?.className, 'has-box-shadow' );

		if ( hasPreset ) {
			className = addClassName( className, 'has-shadow' ).replace( 'has-box-shadow', '' );
		}

		if ( attributes?.shadowPreset ) {
			className = addClassName( className, `has-${ attributes.shadowPreset }-shadow` );
		}

		if ( attributes?.shadowPresetHover ) {
			className = addClassName( className, `has-${ attributes.shadowPresetHover }-shadow-hover` );
		}

		props = {
			...props,
			style: {
				...props.style,
				...styles,
			},
			className,
		};

		return props;
	}
);

export const ShadowControls = ( props: blockProps ): JSX.Element => {
	return <InspectorControls>
		<PanelBody
			initialOpen={ props?.attributes?.shadow ?? false }
			title={ __( 'Shadow', 'blockify' ) }
		>
			<BoxShadowControl
				{ ...props }
			/>
		</PanelBody>
	</InspectorControls>;
};

addFilter(
	'editor.BlockEdit',
	'blockify/shadow-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { isSelected, name } = props;

			if ( ! supportsShadow( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && (
						<ShadowControls { ...props } />
					) }
				</>
			);
		};
	}, 'withShadow' )
);
