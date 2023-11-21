import {
	__experimentalNumberControl as NumberControl,
	Button,
	Flex,
	FlexItem,
	PanelBody,
	PanelRow,
	SelectControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { CSSProperties } from 'react';
import { trash } from '@wordpress/icons';
import { ucWords } from '../utility/string';
import { Label, PauseIcon } from '../components';
import { InspectorControls } from '@wordpress/block-editor';

interface cssAnimation {
	name?: string | null;
	timingFunction?: string | null;
	event?: string | null;
	duration?: string | null;
	delay?: string | null;
	iterationCount?: string | null;
	direction?: string | null;
	fillMode?: string | null;
	playState?: string | null;
	offset?: string | null;
}

const timingFunctionOptions = [
	{ value: 'ease', label: __( 'Ease', 'blockify' ) },
	{ value: 'ease-in', label: __( 'Ease In', 'blockify' ) },
	{ value: 'ease-out', label: __( 'Ease Out', 'blockify' ), isDefault: true },
	{ value: 'ease-in-out', label: __( 'Ease In Out', 'blockify' ) },
	{ value: 'linear', label: __( 'Linear', 'blockify' ) },
];

const effectOptions: { value: string; label: string }[] = [
	{
		value: '',
		label: '',
	},
];

window?.blockify?.animations?.forEach( ( animation ) => {
	effectOptions.push( {
		value: animation,
		label: ucWords( animation?.replace( /-/g, ' ' ) ),
	} );
} );

const eventOptions = [
	{ value: 'enter', label: __( 'Enter', 'blockify' ), isDefault: true },
	{ value: 'exit', label: __( 'Exit', 'blockify' ) },
	{ value: 'infinite', label: __( 'Infinite', 'blockify' ) },
	{ value: 'scroll', label: __( 'Scroll', 'blockify' ) },
];

const supportsAnimation = ( name: string ): boolean =>
	window?.blockify?.blockSupports?.[ name ]?.blockifyAnimation ?? false;

addFilter(
	'blocks.registerBlockType',
	'blockify/add-animation-attributes',
	( props, name ): object => {
		if ( supportsAnimation( name ) ) {
			props.attributes = {
				...props.attributes,
				animation: {
					type: 'object',
				},
			};
		}

		return props;
	},
	0
);

const getStyles = ( animation: cssAnimation ): CSSProperties => {
	const styles: {
		'--animation-event'?: string;
		animationName?: string;
		animationTimingFunction?: string;
		animationDuration?: string;
		animationDelay?: string;
		animationIterationCount?: string;
		animationDirection?: string;
		animationFillMode?: string;
		animationPlayState?: string;
	} = {};

	if ( animation?.event ) {
		styles[ '--animation-event' ] = animation.event ?? 'enter';

		if ( animation.event === 'infinite' ) {
			styles.animationIterationCount = 'infinite';
		}
	}

	if ( animation?.name ) {
		styles.animationName = animation.name ?? '';
	}

	if ( animation?.duration ) {
		styles.animationDuration = ( animation.duration ?? '1' ) + 's';
	}

	if ( animation?.delay ) {
		styles.animationDelay = ( animation.delay ?? '0' ) + 's';
	}

	if ( animation?.timingFunction ) {
		styles.animationTimingFunction =
            animation?.timingFunction ?? 'ease-in-out';
	}

	if ( ! styles?.animationIterationCount ) {
		styles.animationIterationCount = animation?.iterationCount ?? '1';
	}

	if ( animation?.playState ) {
		styles.animationPlayState = animation?.playState ?? 'running';
	}

	return styles as CSSProperties;
};

addFilter(
	'editor.BlockListBlock',
	'blockify/with-animation-props',
	createHigherOrderComponent( ( BlockListBlock ) => {
		return ( props: blockProps ) => {
			const { attributes } = props;
			const animation: cssAnimation = attributes?.animation ?? {};

			if ( ! animation || ! Object?.keys( animation )?.length ) {
				return <BlockListBlock { ...props } />;
			}

			const styles = getStyles( animation );

			const className = props?.className?.trim() + ' has-animation';

			props = {
				...props,
				className,
			};

			const wrapperProps = {
				...props?.wrapperProps,
				className,
				style: {
					...props?.wrapperProps?.style,
					...styles,
				},
			};

			return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
		};
	}, 'withAnimation' )
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'blockify/apply-animation-styles',
	( props, block, attributes ) => {
		const animation: cssAnimation = attributes?.animation ?? {};

		if ( ! animation || ! Object?.keys( animation )?.length ) {
			return props;
		}

		props.className = props?.className?.trim() + ' has-animation';

		const styles = getStyles( animation );

		return {
			...props,
			style: {
				...props?.style,
				...styles,
			},
		};
	}
);

const Animation = ( { attributes, setAttributes }: blockProps ): JSX.Element => {
	const animation: cssAnimation = attributes?.animation ?? {};

	return (
		<>
			<PanelRow>
				<Label>
					<>
						<span>{ __( 'Animation', 'blockify' ) }</span>
						<Button
							isSmall
							isDestructive
							variant={ 'tertiary' }
							onClick={ () => setAttributes( { animation: {} } ) }
							icon={ trash }
							iconSize={ 16 }
							aria-label={ __( 'Clear Animation', 'blockify' ) }
						/>
					</>
				</Label>
				<Flex justify={ 'flex-end' }>
					<FlexItem>
						<Button
							variant={ 'secondary' }
							isSmall
							icon={
								animation?.playState === 'running' ? (
									PauseIcon
								) : (
									<svg
										xmlns={ 'http://www.w3.org/2000/svg' }
										version={ '1.1' }
										fill={ 'currentColor' }
									>
										<polygon points="10,5 0,10 0,0" />
									</svg>
								)
							}
							iconSize={ 10 }
							onClick={ () => {
								setAttributes( {
									animation: {
										...animation,
										playState:
                                            animation?.playState === 'running' ? 'paused' : 'running',
									},
								} );
							} }
						>
							{ animation?.playState === 'running'
								? __( 'Pause', 'blockify' )
								: __( 'Run', 'blockify' ) }
						</Button>
					</FlexItem>
				</Flex>
			</PanelRow>

			<PanelRow className={ 'blockify-animate-controls' }>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<FlexItem>
							<SelectControl
								label={ __( 'Effect', 'blockify' ) }
								value={ animation?.name ?? '' }
								options={ effectOptions }
								onChange={ ( value: string ) => {
									setAttributes( {
										animation: {
											...animation,
											name: value,
											duration: animation?.duration ?? 1,
										},
									} );
								} }
							/>
						</FlexItem>
					</FlexItem>
					<FlexItem>
						<FlexItem>
							<SelectControl
								label={ __( 'Easing', 'blockify' ) }
								value={ animation?.timingFunction ?? '' }
								options={ timingFunctionOptions }
								onChange={ ( value: string ) => {
									setAttributes( {
										animation: {
											...animation,
											timingFunction: value,
										},
									} );
								} }
							/>
						</FlexItem>
					</FlexItem>
					<FlexItem>
						<FlexItem>
							<SelectControl
								label={ __( 'Event', 'blockify' ) }
								value={ animation?.event ?? 'enter' }
								options={ eventOptions }
								onChange={ ( value: string ) => {
									setAttributes( {
										animation: {
											...animation,
											event: value,
											iterationCount: ( value === 'infinite' ? '-1' : animation?.iterationCount ) === '-1' ? '1' : animation?.iterationCount,
										},
									} );
								} }
							/>
						</FlexItem>
					</FlexItem>
				</Flex>
				<Flex className={ 'blockify-flex-controls' }>
					<FlexItem>
						<NumberControl
							label={ __( 'Duration', 'blockify' ) }
							value={ animation?.duration ?? 1 }
							onChange={ ( value: number ) => {
								setAttributes( {
									animation: {
										...animation,
										duration: value,
									},
								} );
							} }
							min={ 0 }
							max={ 100 }
							step={ 0.1 }
							shiftStep={ 10 }
							allowReset={ true }
						/>
					</FlexItem>
					<FlexItem>
						<NumberControl
							label={ __( 'Delay', 'blockify' ) }
							value={ animation?.delay ?? 0 }
							onChange={ ( value: number ) => {
								setAttributes( {
									animation: {
										...animation,
										delay: value,
									},
								} );
							} }
							min={ 0 }
							max={ 100 }
							step={ 0.1 }
							shiftStep={ 10 }
							allowReset={ true }
						/>
					</FlexItem>
					{ animation?.event !== 'infinite' && (
						<FlexItem>
							<NumberControl
								label={ __( 'Repeat', 'blockify' ) }
								value={
									animation?.event === 'infinite'
										? -1
										: animation?.iterationCount ?? 1
								}
								onChange={ ( value: number ) => {
									setAttributes( {
										animation: {
											...animation,
											iterationCount: value,
										},
									} );
								} }
								min={ -1 }
								max={ 100 }
								step={ 1 }
								allowReset={ true }
							/>
						</FlexItem>
					) }
					{ animation?.event === 'scroll' && (
						<FlexItem>
							<NumberControl
								label={ __( 'Offset', 'blockify' ) }
								value={ parseInt( animation?.offset ?? '50' ) }
								onChange={ ( value: number ) => {
									setAttributes( {
										animation: {
											...animation,
											offset: value.toString(),
										},
									} );
								} }
								min={ -1 }
								max={ 200 }
								step={ 1 }
								allowReset={ true }
							/>
						</FlexItem>
					) }
				</Flex>
			</PanelRow>
		</>
	);
};

addFilter(
	'editor.BlockEdit',
	'blockify/animation-controls',
	createHigherOrderComponent( ( BlockEdit ) => {
		return ( props: blockProps ) => {
			const { attributes, isSelected, name } = props;

			if ( ! supportsAnimation( name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<>
					<BlockEdit { ...props } />
					{ isSelected && (
						<InspectorControls>
							<PanelBody
								initialOpen={ attributes?.animation ?? false }
								title={ __( 'Animation', 'blockify' ) }
							>
								<Animation { ...props } />
							</PanelBody>
						</InspectorControls>
					) }
				</>
			);
		};
	}, 'withAnimation' )
);
