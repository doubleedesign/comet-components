import resolve from '@rollup/plugin-node-resolve';
import path from 'node:path';

export default {
	input: 'rollup.index.js',
	output: {
		dir: 'dist',
		format: 'esm',
		entryFileNames: 'dist.js'
	},
	plugins: [
		resolve(),
		{
			// Allow ES module imports in dist.js by updating the import paths
			// to what they need to be when imported in /vendor/doubleedesign/comet-components/core/dist/dist.js
			name: 'remap-import-paths',
			resolveId(source) {
				if (source.includes('vendor')) {
					const shortPath = source.split('vendor')[1];

					return {
						id: path.resolve(`../../../${shortPath}`),
						external: true // ES import in the dist.js file instead of copying its contents in
					};
				}
				if (source.includes('plugins')) {
					const shortPath = source.split('plugins')[1];
					return {
						id: path.resolve(`../src/plugins/${shortPath}`),
						external: true // ES import in the dist.js file instead of copying its contents in
					}
				}

				return null;
			}
		}
	]
};
