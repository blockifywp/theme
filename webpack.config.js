const defaultConfig     = require( '@wordpress/scripts/config/webpack.config' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

module.exports = {
	...defaultConfig,

	module: {
		...defaultConfig.module,
	},

	entry: {
		editor: './assets/tsx/index.tsx'
	},

	plugins: [
		...defaultConfig.plugins,
		new BrowserSyncPlugin( {
			host: 'localhost',
			port: 8887,
			proxy: 'https://blockify.local/'
		} )
	]
};

