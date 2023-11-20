import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { desktop, grid, mobile, trash } from '@wordpress/icons';
import {
	__experimentalNumberControl as NumberControl,
	Button,
	ButtonGroup,
	Flex,
	FlexItem,
	PanelBody,
	PanelRow,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { Label } from '../components';
import { select } from '@wordpress/data';
import { supportsDisplay } from '../block-extensions/display.tsx';

const blockVariation: BlockVariation = {
	name: 'group-grid',
	icon: grid,
	title: __( 'Grid', 'blockify' ),
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'design',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Arrange blocks in a grid.', 'blockify' ),
	attributes: {
		layout: {
			type: 'flex',
			orientation: 'grid',
		},
		style: {
			display: {
				all: 'grid',
			},
			gridTemplateColumns: {
				all: 'repeat(3,1fr)',
			},
			gridTemplateRows: {
				all: 'repeat(3,1fr)',
			},
		},
	},
	isActive: ( blockAttributes ) => blockAttributes?.layout?.orientation === 'grid',
};

registerBlockVariation( 'core/group', blockVariation );

export const gridTypes = [ 'grid', 'inline-grid' ];

const GridControl = ( props: blockProps, screen: string, hasGrid: boolean, parentHasGrid: boolean ) => {
	const { attributes, setAttributes } = props;
	const { style } = attributes;

	const columnValue = style?.gridTemplateColumns?.[ screen ];
	const rowValue = style?.gridTemplateRows?.[ screen ];

	const columns = columnValue ? columnValue?.replace( /repeat\((\d+),1fr\)/, '$1' ) : '';
	const rows = rowValue ? rowValue?.replace( /repeat\((\d+),1fr\)/, '$1' ) : '';

	return <>
		{ hasGrid &&
			<PanelRow
				className={ 'blockify-grid-controls' }
			>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<NumberControl
							label={ __( 'Columns', 'blockify' ) }
							value={ columns }
							onChange={ ( value: string | undefined ) => {
								setAttributes( {
									style: {
										...style,
										gridTemplateColumns: {
											...style?.gridTemplateColumns,
											[ screen ]: value ? 'repeat(' + value + ',1fr)' : '',
										},
									},
								} );
							} }
							min={ 1 }
							max={ 12 }
							step={ 1 }
						/>
					</FlexItem>
					<FlexItem>
						<NumberControl
							label={ __( 'Rows', 'blockify' ) }
							value={ rows }
							onChange={ ( value: string | undefined ) => {
								setAttributes( {
									style: {
										...style,
										gridTemplateRows: {
											...style?.gridTemplateRows,
											[ screen ]: value ? 'repeat(' + value + ',1fr)' : '',
										},
									},
								} );
							} }
							min={ 1 }
							max={ 12 }
							step={ 1 }
						/>
					</FlexItem>
				</Flex>
			</PanelRow>
		}
		{ parentHasGrid &&
			<>
				<PanelRow
					className={ 'blockify-grid-controls' }
				>
					<Flex className={ 'blockify-flex-controls' }>
						<FlexItem>
							<NumberControl
								label={ __( 'Column Start', 'blockify' ) }
								value={ style?.gridColumnStart?.[ screen ] ?? '' }
								onChange={ ( value: string | undefined ) => {
									setAttributes( {
										style: {
											...style,
											gridColumnStart: {
												...style?.gridColumnStart,
												[ screen ]: value,
											},
										},
									} );
								} }
								min={ 1 }
								max={ 12 }
								step={ 1 }
							/>
						</FlexItem>
						<FlexItem>
							<NumberControl
								label={ __( 'Column End', 'blockify' ) }
								value={ style?.gridColumnEnd?.[ screen ] ?? '' }
								onChange={ ( value: string | undefined ) => {
									setAttributes( {
										style: {
											...style,
											gridColumnEnd: {
												...style?.gridColumnEnd,
												[ screen ]: value,
											},
										},
									} );
								} }
								min={ 1 }
								max={ 12 }
								step={ 1 }
							/>
						</FlexItem>
					</Flex>
				</PanelRow>
				<PanelRow
					className={ 'blockify-grid-controls' }
				>
					<Flex className={ 'blockify-flex-controls' }>
						<FlexItem>
							<NumberControl
								label={ __( 'Row Start', 'blockify' ) }
								value={ style?.gridRowStart?.[ screen ] ?? '' }
								onChange={ ( value: string | undefined ) => {
									setAttributes( {
										style: {
											...style,
											gridRowStart: {
												...style?.gridRowStart,
												[ screen ]: value,
											},
										},
									} );
								} }
								min={ 1 }
								max={ 12 }
								step={ 1 }
							/>
						</FlexItem>
						<FlexItem>
							<NumberControl
								label={ __( 'Row End', 'blockify' ) }
								value={ style?.gridRowEnd?.[ screen ] ?? '' }
								onChange={ ( value: string | undefined ) => {
									setAttributes( {
										style: {
											...style,
											gridRowEnd: {
												...style?.gridRowEnd,
												[ screen ]: value,
											},
										},
									} );
								} }
								min={ 1 }
								max={ 12 }
								step={ 1 }
							/>
						</FlexItem>
					</Flex>
				</PanelRow>
			</>
		}
	</>;
};

const GridControls = ( props: blockProps ): JSX.Element => {
	const { attributes, setAttributes, name, clientId } = props;
	const [ screen, setScreen ] = useState( 'all' );
	const { style } = attributes;

	const hasGrid = name === 'core/group' && ( gridTypes.includes( style?.display?.all ) || gridTypes.includes( style?.display?.mobile ) || gridTypes.includes( style?.display?.desktop ) );

	const parentBlocks = select( 'core/block-editor' )?.getBlockParents( clientId ) ?? [];
	const parentBlock = parentBlocks[ parentBlocks.length - 1 ];
	const parentAttributes = select( 'core/block-editor' )?.getBlockAttributes( parentBlock ) ?? null;
	const parentHasGrid = gridTypes.includes( parentAttributes?.style?.display?.all ) || gridTypes.includes( parentAttributes?.style?.display?.mobile ) || gridTypes.includes( parentAttributes?.style?.display?.desktop );

	if ( ! hasGrid && ! parentHasGrid ) {
		return <></>;
	}

	return <PanelBody
		initialOpen={ attributes?.display?.all === 'grid' || attributes?.display?.mobile === 'grid' || attributes?.display?.desktop === 'grid' }
		title={ __( 'Grid', 'blockify' ) }
	>
		<PanelRow>
			<Label>
				<>
					{ __( 'Grid', 'blockify' ) }
					<Button
						isSmall
						isDestructive
						variant={ 'tertiary' }
						onClick={ () => {
							setAttributes( {
								style: {
									...attributes?.style,
									gridTemplateColumns: '',
									gridTemplateRows: '',
									gridColumnStart: '',
									gridColumnEnd: '',
									gridRowStart: '',
									gridRowEnd: '',
								},
							} );
						} }
						icon={ trash }
						iconSize={ 16 }
						aria-label={ __( 'Reset Grid', 'blockify' ) }
					/>
				</>
			</Label>
			<ButtonGroup>
				<Button
					isSmall
					variant={ screen === 'all' ? 'primary' : 'secondary' }
					onClick={ () => setScreen( 'all' ) }
				>
					{ __( 'All', 'blockify' ) }
				</Button>
				<Button
					isSmall
					variant={ screen === 'mobile' ? 'primary' : 'secondary' }
					onClick={ () => setScreen( 'mobile' ) }
					icon={ mobile }
				/>
				<Button
					isSmall
					variant={ screen === 'desktop' ? 'primary' : 'secondary' }
					onClick={ () => setScreen( 'desktop' ) }
					icon={ desktop }
				/>
			</ButtonGroup>
		</PanelRow>
		{ screen === 'all' && GridControl( props, screen, hasGrid, parentHasGrid ) }
		{ screen === 'mobile' && GridControl( props, screen, hasGrid, parentHasGrid ) }
		{ screen === 'desktop' && GridControl( props, screen, hasGrid, parentHasGrid ) }
	</PanelBody>;
};

addFilter(
	'editor.BlockEdit',
	'blockify/grid-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { isSelected, attributes, name } = props;

			if ( ! supportsDisplay( name ) ) {
				return <BlockEdit { ...props } />;
			}

			const InlineStyles = () => <style>
				{ '.has-display-grid{align-items:normal !important}' }
			</style>;

			const hasInlineStyles = attributes?.layout?.orientation === 'grid' && ! attributes?.layout?.verticalAlignment;

			return (
				<>
					{ hasInlineStyles &&
						<InlineStyles />
					}
					<BlockEdit { ...props } />
					{ isSelected &&
						<InspectorControls>
							<GridControls { ...props } />
						</InspectorControls>
					}
				</>
			);
		};
	}, 'withDisplay' )
);
