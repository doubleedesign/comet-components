import type { StorybookConfig } from '@storybook/server-webpack5';

const config: StorybookConfig = {
	stories: [
		'../src/**/*.mdx',
		'../test/browser/stories/**/*.stories.@(json|yaml|yml)'
	],
	addons: [
		'@storybook/addon-webpack5-compiler-swc',
		'@storybook/addon-essentials',
		'@chromatic-com/storybook',
	],
	framework: {
		name: '@storybook/server-webpack5',
		options: {},
	},
	docs: {
	}
};
export default config;
