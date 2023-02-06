import { CustomSelectControl } from "@wordpress/components";
import Option = CustomSelectControl.Option;
import { CSSProperties } from "react";

export {};

declare global {

	namespace JSX {
		interface IntrinsicElements {
			marquee?: any;
		}
	}

	interface blockify {

		// Editor.
		siteUrl?: string;
		adminUrl?: string;
		ajaxUrl?: string;
		url?: string;
		restUrl?: string;
		nonce?: string;
		icon?: string;
		darkMode?: string;
		removeEmojiScripts?: boolean;
		excerptLength?: number;
		blockSupports?: blockSupports;
		blockStyles?: blockStyles;
		conicGradient?: string;
		underlineTypes?: string[];
		fontFamilies?: string[];
		selectedFonts?: string[];
		positionOptions?: positionOptions;
		animations?: string[];
		animationOffset?: string;
		breakpoint?: string; // Front end.
		siteEditor?: boolean;
		isPlugin?: boolean;
		userRoles?: { [key: string]: string };
		loremIpsum?: string;

		// Public.
		setCookie: ( name: string, value: string, days: number ) => void,
		getCookie: ( name: string ) => string,
		eraseCookie: ( name: string ) => void,
	}

	interface blockSupports {
		[blockName: string]: {
			color?: {
				gradient?: boolean;
				text?: boolean;
				background?: boolean;
				link?: boolean;
			},
			blockifyAnimation?: boolean;
			blockifyBackground?: boolean;
			blockifyBoxShadow?: boolean;
			blockifyDisplay?: boolean;
			blockifyFilter?: boolean;
			blockifyOnclick?: boolean;
			blockifyPosition?: boolean;
			blockifyTransform?: boolean;
		}
	}

	interface blockStyle {
		type: string
		name: string,
		label: string,
		isDefault?: boolean
	}

	interface blockStyles {
		unregister: [],
		register: []
	}

	interface blockifyPatternEditor {
		restUrl?: string;
		nonce?: string;
		currentUser?: string;
		adminUrl?: string;
		stylesheet?: string;
		isChildTheme?: boolean;
		patternDir?: string;
		imgDir?: string;
	}

	interface Window {
		blockify: blockify;
		blockifyPatternEditor?: blockifyPatternEditor;
	}

	interface style {
		[name: string]: string | null;
	}

	interface attributes {
		[name: string]: any;
	}

	interface blockProps {
		name: string;
		clientId: string;
		className: string;
		style: style | CSSProperties;
		attributes: attributes,
		setAttributes: ( attributes: any ) => void;
		wrapperProps?: {
			[name: string]: any;
		},
		isSelected?: boolean;
		value?: any,
		children?: any,
	}

	interface formatProps {
		isActive?: boolean,
		onChange: ( value: any ) => any,
		formatTypes?: { name: string }[],
		value?: any,
	}

	interface customSelectOptions extends Array<Option> {
		[index: number]: Option
	}

	interface cssTransforms {
		matrix?: string,
		matrix3d?: string,
		perspective?: string,
		rotate?: string,
		rotate3d?: string,
		rotateX?: string,
		rotateY?: string,
		rotateZ?: string,
		translate?: string,
		translate3d?: string,
		translateX?: string,
		translateY?: string,
		translateZ?: string,
		scale?: string,
		scale3d?: string,
		scaleX?: string,
		scaleY?: string,
		scaleZ?: string,
		skew?: string,
		skewX?: string,
		skewY?: string,
		hover?: cssTransforms,
		animate?: cssTransforms,
	}

	interface cssFilters {
		[name: string]: string | undefined | boolean | cssFilters,

		blur?: string,
		brightness?: string,
		contrast?: string,
		grayscale?: string,
		hueRotate?: string,
		invert?: string,
		opacity?: string,
		saturate?: string,
		sepia?: string,
		backdrop?: boolean,
		hover?: cssFilters,
		animate?: cssFilters,
	}

	interface gradient {
		slug: string,
		name: string,
		gradient: string,
	}

	interface positionOptions {
		[name: string]: responsiveSetting | undefined,

		position?: responsiveSetting,
		inset?: responsiveSetting,
		zIndex?: responsiveSetting,
		overflow?: responsiveSetting,
		pointerEvents?: responsiveSetting,
	}

	interface displayOptions {
		display?: responsiveSetting,
		order?: responsiveSetting,
		width?: responsiveSetting,
		maxWidth?: responsiveSetting,
	}

	interface responsiveSetting {
		value: string,
		label: string,
		options?: Array<{
			value: string,
			label: string,
		}>
	}
}

export { blockify, blockifyPatternEditor };
