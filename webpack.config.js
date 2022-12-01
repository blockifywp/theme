const defaultConfig     = require( '@wordpress/scripts/config/webpack.config' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

module.exports = env => {
	return {
		...defaultConfig,

		module: {
			...defaultConfig.module
		},

		entry: {
			editor: './src/editor.tsx',
			accordion: './src/public/accordion.tsx',
			animation: './src/public/animation.tsx',
			counter: './src/public/counter.tsx',
		},

		plugins: [
			...defaultConfig.plugins,

			new BrowserSyncPlugin( {
				host: 'localhost',
				port: 8887,
				proxy: 'https://blockify.local/'
			} ),
		]
	};
};

