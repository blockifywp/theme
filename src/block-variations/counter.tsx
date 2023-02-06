import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	PanelRow,
	TextControl,
	//@ts-ignore
	__experimentalNumberControl as NumberControl, Flex, FlexItem
} from '@wordpress/components';
import { backup } from '@wordpress/icons';
import { InspectorControls } from '@wordpress/block-editor';
import { BlockVariation, registerBlockVariation } from '@wordpress/blocks';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import domReady from '@wordpress/dom-ready';

interface counter {
	start: string,
	end: string,
	duration: string,
	delay: string,
	prefix: string,
	suffix: string,
}

const defaults: counter = {
	start: '0',
	end: '100',
	duration: '2',
	delay: '0',
	prefix: '',
	suffix: '',
};

const counterVariation: BlockVariation = {
	name: 'counter',
	title: __( 'Counter', 'blockify' ),
	keywords: [ 'counter', 'number', 'count', 'stats' ],
	icon: backup,
	isDefault: false,
	category: window?.blockify?.isPlugin ? 'blockify' : 'text',
	scope: [ 'inserter', 'transform', 'block' ],
	description: __( 'Insert counter animation.', 'blockify' ),
	attributes: {
		className: 'is-style-counter',
	},
	isActive: ( blockAttributes ) => {
		return blockAttributes && blockAttributes?.className?.includes( 'is-style-counter' );
	}
};

domReady( () => {
	registerBlockVariation( 'core/paragraph', counterVariation );
} );

addFilter(
	'editor.BlockEdit',
	'blockify/with-counter-controls',
	createHigherOrderComponent( ( BlockEdit: any ) => ( props: blockProps ) => {

		const { attributes, setAttributes } = props;

		const defaultReturn = <BlockEdit { ...props } />;

		if ( ! attributes?.className?.includes( 'is-style-counter' ) ) {
			return defaultReturn;
		}

		const { style } = attributes;

		let counter: counter = defaults;

		if ( ! style?.counter ) {
			setAttributes( {
				style: {
					...style,
					counter: defaults,
				}
			} );
		} else {
			counter = style.counter;
		}

		if ( counter?.prefix || counter?.end || counter?.suffix ) {
			let newContent = counter?.end;

			if ( typeof counter?.prefix === 'string' ) {
				newContent = counter?.prefix + newContent;
			}

			if ( typeof counter?.suffix === 'string' ) {
				newContent = newContent + counter?.suffix;
			}

			setAttributes( {
				content: newContent,
			} );
		}

		return (
			<>
				{ defaultReturn }
				<InspectorControls>
					<PanelBody
						title={ __( 'Counter Settings', 'blockify' ) }
						initialOpen={ true }
						className={ 'blockify-counter-settings' }
					>
						<PanelRow>

							<Flex
								className={ 'blockify-flex-controls' }
							>
								<FlexItem>
									<NumberControl
										label={ __( 'Start', 'blockify' ) }
										value={ counter?.start }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														start: value,
													}
												}
											} );
										} }
										step={ 1 }
										shiftStep={ 10 }
										isDragEnabled={ true }
										isShiftStepEnabled={ true }
									/>
								</FlexItem>
								<FlexItem>
									<NumberControl
										label={ __( 'End', 'blockify' ) }
										value={ counter?.end }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														end: value,
													}
												}
											} );
										} }
										step={ 1 }
										shiftStep={ 10 }
										isDragEnabled={ true }
										isShiftStepEnabled={ true }
									/>
								</FlexItem>
							</Flex>


						</PanelRow>
						<PanelRow>
							<Flex
								className={ 'blockify-flex-controls' }
							>
								<FlexItem>
									<NumberControl
										label={ __( 'Duration (seconds)', 'blockify' ) }
										value={ counter?.duration }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														duration: value,
													}
												}
											} );
										} }
										step={ .1 }
										shiftStep={ 1 }
										isDragEnabled={ true }
										isShiftStepEnabled={ true }
									/>
								</FlexItem>
								<FlexItem>
									<NumberControl
										label={ __( 'Delay (seconds)', 'blockify' ) }
										value={ counter?.delay }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														delay: value,
													}
												}
											} );
										} }
										step={ .1 }
										shiftStep={ 1 }
										isDragEnabled={ true }
										isShiftStepEnabled={ true }
									/>
								</FlexItem>
							</Flex>

						</PanelRow>
						<PanelRow>
							<Flex
								className={ 'blockify-flex-controls' }
							>
								<FlexItem>
									<TextControl
										label={ __( 'Prefix', 'blockify' ) }
										value={ counter?.prefix }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														prefix: value,
													}
												}
											} );
										} }
									/>
								</FlexItem>
								<FlexItem>
									<TextControl
										label={ __( 'Suffix', 'blockify' ) }
										value={ counter?.suffix }
										onChange={ ( value: string ) => {
											setAttributes( {
												style: {
													...style,
													counter: {
														...counter,
														suffix: value,
													}
												}
											} );
										} }
									/>
								</FlexItem>
							</Flex>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			</>
		);
	}, 'withCounterControls' ),
	9
);
