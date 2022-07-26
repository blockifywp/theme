const FileManagerPlugin        = require( 'filemanager-webpack-plugin' );
const defaultConfig            = require( '@wordpress/scripts/config/webpack.config' );
const path                     = require( 'path' );
const glob                     = require( 'glob' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

module.exports = {
	...defaultConfig,

	module: {
		...defaultConfig.module,
	},

	entry: {
		...defaultConfig.entry,
		index: path.resolve( process.cwd(), 'src', 'index.tsx' ),
		editor: path.resolve( process.cwd(), 'src', 'editor.scss' ),
		style: path.resolve( process.cwd(), 'src', 'style.scss' ),
		...glob.sync( path.resolve( process.cwd(), 'src', 'blocks/**/style.scss' ) )
			.reduce( ( entries, filename ) => {
				const name = filename.split( '/' ).reverse()[ 1 ];

				return { ...entries, [ 'blocks/' + name + '/style' ]: filename };
			}, {} ),
		...glob.sync( path.resolve( process.cwd(), 'src', 'blocks/**/script.tsx' ) )
			.reduce( ( entries, filename ) => {
				const name = filename.split( '/' ).reverse()[ 1 ];

				return { ...entries, [ 'blocks/' + name + '/script' ]: filename };
			}, {} )
	},

	plugins: [
		...defaultConfig.plugins,

		new RemoveEmptyScriptsPlugin(),

		new FileManagerPlugin( {
			events: {
				onEnd: {
					copy: [
						{
							source: './build/style-style.css',
							destination: './build/style.css'
						}
					],
					delete: [
						'./build/index.css',
						'./build/style-editor.css',
						'./build/style-style.css'
					],
				},
			},
		} ),
	],
};

