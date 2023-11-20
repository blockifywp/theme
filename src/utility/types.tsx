import { CustomSelectControl } from '@wordpress/components';
import { CSSProperties } from 'react';
import { BlockAttributes } from '@wordpress/blocks';
import {
	AlignmentToolbar,
	BlockAlignmentToolbar,
	BlockControls,
} from '@wordpress/block-editor';
import Option = CustomSelectControl.Option;

declare global {

	interface genericStrings {
		[key: string]: string;
	}

	interface CustomBlockAttribute {
		control?: 'text' | 'textarea' | 'select' | 'number' | 'checkbox' | 'radio' | 'color' | 'range' | 'toggle';
		default?: string | number | boolean | object | null | undefined;
		name: string;
		type: string;
		label?: string;
		value: string | number | boolean | object | null | undefined;
		placeholder?: string;
		help?: string;
		min?: number;
		max?: number;
		step?: number;
		options?: genericOption[];
		multiple?: boolean;
		labelPosition?: 'top' | 'side' | 'bottom';
		inputType?: 'text' | 'number' | 'email' | 'url' | 'tel' | 'password';
		withInputField?: boolean;
		marks?: {
			value: number;
			label: string;
		}[];
		rows?: number;
		toolbar?: 'AlignmentToolbar' | 'BlockAlignmentToolbar' | 'BlockVerticalAlignmentToolbar';
		controls?: BlockAlignmentToolbar.Control[] | AlignmentToolbar.Props['alignmentControls'] | BlockControls.Props['controls'];
		onChange?: ( newValue: string | number | boolean | object | null | undefined ) => void;
		subfields?: CustomField[];
	}

	interface CustomBlock {
		title?: string;
		description?: string;
		category?: string;
		icon?: string | {
			src: string;
		};
		attributes?: {
			[key: string]: CustomBlockAttribute;
		};
		postTypes?: string[];
	}

	interface CustomField {
		id: string;
		type: string;
		index?: number;
		label?: string;
		add?: string;
		default?: string | number | boolean | object | null | undefined;
		subfields?: CustomField[];
		placeholder?: string;
	}

	interface blockify {
		name?: string;
		siteUrl?: string;
		adminUrl?: string;
		ajaxUrl?: string;
		url?: string;
		restUrl?: string;
		previewLink?: string;
		nonce?: string;
		icon?: string;
		darkMode?: string;
		removeEmojiScripts?: boolean;
		excerptLength?: number;
		blockSupports?: blockSupports;
		blockStyles?: BlockStyles;
		conicGradient?: string;
		underlineTypes?: string[];
		fontFamilies?: string[];
		selectedFonts?: string[];
		extensionOptions?: extensionOptions;
		filterOptions?: filterOptions;
		imageOptions?: imageOptions;
		animations?: string[];
		animationOffset?: string;
		breakpoint?: string; // Front end.
		siteEditor?: boolean;
		isPlugin?: boolean;
		userRoles?: genericStrings;
		loremIpsum?: string;
		googleMaps?: string;
		plugins?: string[];
		blocks?: {
			[key: string]: CustomBlock;
		};
		postMeta?: string[];
		postType?: string;
		defaultIcon?: {
			set: string;
			name: string;
			string: string;
		};
		metaBoxes?: {
			id: string;
			fields: CustomField[];
		}[];
	}

	interface Window {
		blockify: blockify;
		Splide?: any;
		splide?: {
			Extensions?: any;
		};
	}

	interface blockSupports {
		[blockName: string]: {
			color?: {
				gradient?: boolean;
				text?: boolean;
				background?: boolean;
				link?: boolean;
			};
			blockifyAnimation?: boolean;
			blockifyBackground?: boolean;
			blockifyBoxShadow?: boolean;
			blockifyDisplay?: boolean;
			blockifyFilter?: boolean;
			blockifyInlineCss?: boolean;
			blockifyOnclick?: boolean;
			blockifyPosition?: boolean;
			blockifySize?: boolean;
			blockifyTransform?: boolean;
		};
	}

	interface BlockStyles {
		unregister: genericStrings;
		register: genericStrings;
	}

	interface wrapperProps {
		style?: CSSProperties;
		'data-id'?: string;
		className?: string;
	}

	interface responsiveStyles {
		all?: string;
		mobile?: string;
		desktop?: string;
	}

	interface attributes {
		[name: string]: object | string | number | boolean | null | undefined;

		style?: {
			[key: string]: genericStrings | responsiveStyles;
		};
	}

	interface blockProps {
		name: string;
		clientId: string;
		className: string;
		style: genericStrings | CSSProperties;
		attributes: BlockAttributes | attributes;
		setAttributes: ( newAttributes: attributes ) => void;
		wrapperProps?: wrapperProps;
		isSelected?: boolean;
		value?: any;
		children?: any;
	}

	interface formatProps {
		isActive?: boolean;
		onChange: ( value: any ) => any;
		formatTypes?: { name: string }[];
		value?: any;
	}

	interface customSelectOptions extends Array<Option> {
		[index: number]: Option;
	}

	interface cssFilters {
		[name: string]: string | undefined | boolean | cssFilters;

		blur?: string;
		brightness?: string;
		contrast?: string;
		grayscale?: string;
		hueRotate?: string;
		invert?: string;
		opacity?: string;
		saturate?: string;
		sepia?: string;
		backdrop?: boolean;
		hover?: cssFilters;
		animate?: cssFilters;
	}

	interface gradient {
		slug: string;
		name: string;
		gradient: string;
	}

	interface extensionOptions {
		[name: string]: responsiveSetting | filterOptions | undefined;

		position?: responsiveSetting;
		inset?: responsiveSetting;
		zIndex?: responsiveSetting;
		overflow?: responsiveSetting;
		pointerEvents?: responsiveSetting;
		aspectRatio?: responsiveSetting;
		objectFit?: responsiveSetting;
		objectPosition?: responsiveSetting;
	}

	interface filterOptions {
		[name: string]: {
			unit?: string;
			min?: number;
			max?: number;
			step?: number;
		};
	}

	interface imageOptions {
		aspectRatio?: responsiveSetting;
		height?: responsiveSetting;
		objectFit?: responsiveSetting;
		objectPosition?: responsiveSetting;
	}

	interface genericOption {
		value: string;
		label: string;
		disabled?: boolean;
	}

	interface responsiveSetting {
		value: string;
		label: string;
		options?: genericOption[];
	}
}
