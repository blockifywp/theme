const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

module.exports = ( env ) => {
	return {
		...defaultConfig,

		module: {
			...defaultConfig.module,
		},

		entry: {
			editor: './src/index.tsx',
			animation: './src/public/animation.tsx',
			counter: './src/public/counter.tsx',
			details: './src/public/details.tsx',
			packery: './src/public/packery.tsx',
			scroll: './src/public/scroll.tsx',
		},

		plugins: [
			...defaultConfig.plugins,

			new BrowserSyncPlugin( {
				host: 'localhost',
				port: 8887,
				proxy: 'https://blockifywp.local/',
			} ),
		],
	};
};

