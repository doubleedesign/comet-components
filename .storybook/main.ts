import { fileURLToPath } from 'node:url';
import { dirname } from 'node:path';
import type { StorybookConfig } from '@storybook/html-vite';
import { mergeConfig } from 'vite';

const config: StorybookConfig = {
	'stories': [
		'../packages/core/src/components/**/*.stories.@(js|jsx|mjs|ts|tsx)',
		'../packages/core/src/components/**/*.docs.@(mdx)',
	],
	'addons': [
		getAbsolutePath('@storybook/addon-docs'),
		//getAbsolutePath('./addons/code-tabs/preset.ts')
	],
	'framework': {
		'name': '@storybook/html-vite',
		'options': {}
	},
	core: {
		allowedHosts: ['storybook.comet-components.test'],
	},
	viteFinal(config) {
		return mergeConfig(config, { server: { allowedHosts: true } });
	}
};
export default config;

function getAbsolutePath(value: string): any {
	return dirname(fileURLToPath(import.meta.resolve(`${value}/package.json`)));
}
