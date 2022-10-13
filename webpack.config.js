const defaultConfig     = require( '@wordpress/scripts/config/webpack.config' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

module.exports = env => {
	return {
		...defaultConfig,

		module: {
			...defaultConfig.module,
		},

		entry: {
			editor: './src/editor.tsx',
			'public/animation': './src/public/animation.tsx',
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

