module.exports = {
	extends: [
		'eslint:recommended',
		'plugin:@wordpress/eslint-plugin/recommended-with-formatting',
		'plugin:@typescript-eslint/recommended',
	],
	parser: '@typescript-eslint/parser',
	plugins: [ '@typescript-eslint' ],
	root: true,
	rules: {
		'@typescript-eslint/indent': [ 'error', 'tab' ],
	},
	settings: {
		'import/resolver': {
			node: {
				extensions: [
					'.js',
					'.jsx',
					'.ts',
					'.tsx'
				]
			}
		}
	}
};
