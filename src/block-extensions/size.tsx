import { __ } from '@wordpress/i18n';
import { BlockControls } from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import {
	DropdownMenu,
	MenuGroup,
	MenuItem,
	ToolbarGroup,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { replaceAll } from '../utility';
import { addClassName } from '../utility/css';

const blockSupports = window?.blockify?.blockSupports ?? {};

export const supportsSize = ( name: string ): boolean => blockSupports?.[ name ]?.blockifySize ?? false;

const Controls = ( props: any ) => {
	const { attributes, setAttributes } = props;
	const { size } = attributes;

	const sizes = [
		{
			key: 'large',
			label: __( 'Large', 'blockify' ),
		},
		{
			key: 'medium',
			label: __( 'Medium', 'blockify' ),
		},
		{
			key: 'small',
			label: __( 'Small', 'blockify' ),
		},
	];

	return <BlockControls>
		<ToolbarGroup>
			<DropdownMenu
				icon={ <span>{ __( 'Size', 'blockify' ) }</span> }
				label={ __( 'Switch Size', 'blockify' ) }
			>
				{ ( { onClose } ) => (
					<MenuGroup>
						{ sizes.map( ( sizeData ) => (
							<MenuItem
								key={ sizeData.key }
								icon={ size === sizeData.key ? 'yes' : '' }
								onClick={ () => {
									if ( size === sizeData.key ) {
										setAttributes( {
											size: '',
										} );
										onClose();

										return;
									}

									setAttributes( {
										size: sizeData.key,
									} );
									onClose();
								} }
							>
								{ sizeData.label }
							</MenuItem>
						) ) }
					</MenuGroup>
				) }
			</DropdownMenu>
		</ToolbarGroup>
	</BlockControls>;
};

addFilter(
	'blocks.registerBlockType',
	'blockify/add-size-attribute',
	( settings: any ) => {
		if ( settings?.attributes && supportsSize( settings?.name ) ) {
			settings.attributes = {
				...settings.attributes,
				size: {
					type: 'string',
					default: '20px',
				},
			};
		}

		return settings;
	}
);

addFilter(
	'editor.BlockEdit',
	'blockify/add-size-block-controls',
	createHigherOrderComponent( ( BlockEdit: any ) => {
		return ( props: blockProps ) => {
			const { name, isSelected } = props;

			if ( ! supportsSize( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<Fragment>
					{ isSelected && <Controls { ...props } /> }
					<BlockEdit { ...props } />
				</Fragment>
			);
		};
	}, 'addSizeBlockControls' )
);

addFilter(
	'editor.BlockListBlock',
	'blockify/with-size',
	createHigherOrderComponent(
		( BlockListBlock ) => ( props: blockProps ) => {
			const { name, attributes } = props;

			if ( ! supportsSize( name ) ) {
				return <BlockListBlock { ...props } />;
			}

			if ( ! attributes?.size ) {
				return <BlockListBlock { ...props } />;
			}

			const wrapperProps = props.wrapperProps ?? {};
			const className = 'is-style-' + attributes.size;

			wrapperProps.className = addClassName( wrapperProps?.className, className );

			return <BlockListBlock
				{ ...{
					...props,
					className: ( props?.className ?? '' ) + ' ' + className,
				} }
				wrapperProps={ wrapperProps }
			/>;
		},
		'withSizeClass'
	)
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/size-save',
	( props: blockProps ) => {
		const { name, attributes } = props;

		if ( supportsSize( name ) && attributes?.size ) {
			const className = 'is-style-' + attributes.size;

			props.className = replaceAll( props?.className, className, '' ) + ' ' + className;
		}

		return props;
	}
);
