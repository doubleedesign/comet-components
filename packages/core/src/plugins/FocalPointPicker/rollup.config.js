import replace from '@rollup/plugin-replace';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import json from '@rollup/plugin-json';
import nodePolyfills from 'rollup-plugin-polyfill-node';
import path from 'node:path';

export default {
	input: 'rollup.index.js',
	output: {
		dir: 'dist',
		format: 'esm',
		entryFileNames: 'focal-point-picker.min.js',
		paths: {
			vue: `/packages/core/src/plugins/vue-wrapper/src/vue.esm-browser.prod.js`
		}
	},
	plugins: [
		replace({
			'process.env.NODE_ENV': JSON.stringify('production'),
			'process.env': JSON.stringify({ NODE_ENV: 'production' }),
			preventAssignment: true
		}),
		resolve({
			preferBuiltins: false,
			browser: true
		}),
		commonjs(),
		json(),
		nodePolyfills({}),
		{
			// Allow ES module imports in bundled JS by updating the import paths
			name: 'remap-import-paths',
			resolveId(source) {
				if(source === 'vue') {
					return { id: 'vue', external: true };
				}
				return null;
			}
		},
	]
};
