import { ESLint } from 'eslint';
import vuePlugin from 'eslint-plugin-vue';
import vueEslintParser from 'vue-eslint-parser';
import globals from 'globals';
import rootConfig from '../../eslint.config.js';
import tsParser from '@typescript-eslint/parser';

// Extract rules and plugins from root config
const rootRules = rootConfig[0].rules || {};
const rootPlugins = rootConfig[0].plugins || {};

export default [
	{
		files: ['**/*.vue'],
		ignores: ['dist', 'node_modules/**'],
		languageOptions: {
			ecmaVersion: 2020,
			globals: globals.browser,
			parser: vueEslintParser,  // Use vue-eslint-parser to handle Vue files
			parserOptions: {
				parser: tsParser,  // Use TypeScript parser for <script> blocks
				ecmaVersion: 'latest',
				sourceType: 'module',
			},
		},
		plugins: {
			...rootPlugins,
			vue: vuePlugin,
		},
		rules: {
			...rootRules,
			'vue/multi-word-component-names': 'off',
		}
	}
];
