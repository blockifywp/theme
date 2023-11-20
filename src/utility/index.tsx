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
	ucFirst,
	ucWords,
} from './string';

const textDomain = 'blockify';

const blockifyIcon = <svg
	xmlns="http://www.w3.org/2000/svg"
	viewBox="0 0 2000 2000"
>
	<path
		fill="currentColor"
		d="m1729.66 534.39-691.26-399.1a76.814 76.814 0 0 0-76.81 0l-691.26 399.1a76.818 76.818 0 0 0-38.4 66.52v798.19c0 27.44 14.64 52.8 38.4 66.52l691.26 399.1c11.88 6.86 25.14 10.29 38.4 10.29s26.52-3.43 38.4-10.29l691.26-399.1a76.818 76.818 0 0 0 38.4-66.52V600.9c.01-27.44-14.63-52.79-38.39-66.51zm-115.21 820.36-539.18 311.3V998.46c0-27.45-14.65-52.81-38.43-66.53l-574.18-331.2L1000 290.49l614.45 354.75v709.51z"
	/>
</svg>;

export {
	textDomain,
	blockifyIcon,
	formatCustomProperty,
	cssObjectToString,
	cssStringToObject,
	unitsWithAuto,
	ucWords,
	ucFirst,
	toKebabCase,
	replaceAll,
	camelCaseToWords,
};
