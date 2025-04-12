import type { StorybookConfig } from '@storybook/server-webpack5';
import type { Configuration } from 'webpack';
import * as fs from 'node:fs';
import * as path from 'node:path';

const config: StorybookConfig = {
	stories: [
		'../docs/**/*.mdx',
		'../packages/core/src/components/**/*.stories.@(json|yaml|yml)'
	],
	addons: [
		'@storybook/addon-webpack5-compiler-swc',
		'@storybook/addon-essentials',
		'./addons/code-tabs/preset.ts'
	],
	framework: {
		name: '@storybook/server-webpack5',
		options: {	},
	},
	core: {
		builder: 'webpack5'
	},
	webpackFinal: async (config: Configuration) => {
		if (config.resolve) {
			config.resolve.fallback = {
				...config.resolve.fallback,
				fs: false,
				path: require.resolve('path-browserify'),
				crypto: require.resolve('crypto-browserify'),
				stream: require.resolve('stream-browserify')
			};
		}

		return {
			...config,
			optimization: {
				...config.optimization,
				splitChunks: {
					chunks: 'all',
					minSize: 100,
					maxSize: 2000,
					maxAsyncRequests: 20,
					maxInitialRequests: 20,
					automaticNameDelimiter: '-',
					enforceSizeThreshold: 1000,
					cacheGroups: {
						stories: {
							test: /[\\/]stories[\\/]/,
							priority: -10,
							reuseExistingChunk: true,
							enforce: true
						},
						default: {
							minChunks: 1,
							priority: -20,
							reuseExistingChunk: true
						}
					}
				}
			},
		};
	},
	docs: {
	},
};

export default config;
