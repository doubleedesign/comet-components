import type { StorybookConfig } from '@storybook/html-vite';

const config: StorybookConfig = {
	'stories': [
		'../packages/core/src/components/**/*.stories.@(js|jsx|mjs|ts|tsx)',
		'../packages/core/src/components/**/*.docs.@(mdx)',
	],
	'addons': [
		'@storybook/addon-essentials',
		'@storybook/addon-interactions'
	],
	'framework': {
		'name': '@storybook/html-vite',
		'options': {}
	}
};
export default config;
