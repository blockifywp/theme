import {
	cssObjectToString,
	cssStringToObject,
	formatCustomProperty,
	unitsWithAuto,
} from './css';

import {
	camelCaseToWords,
	replaceAll,
	toKebabCase,
	toTitleCase,
	ucFirst,
	ucWords,
} from './string';

import { blockifyIcon, defaultIcon, getIconStyles } from './icon.tsx';

const textDomain = 'blockify';

export {
	textDomain,
	blockifyIcon,
	defaultIcon,
	getIconStyles,
	formatCustomProperty,
	cssObjectToString,
	cssStringToObject,
	unitsWithAuto,
	ucWords,
	ucFirst,
	toKebabCase,
	replaceAll,
	camelCaseToWords,
	toTitleCase,
};
