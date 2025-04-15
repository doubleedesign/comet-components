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
			name: 'remap-import-paths',
			resolveId(source) {
				if (source.includes('vendor')) {
					const shortPath = source.split('vendor')[1];

					return {
						id: path.resolve(`../../../${shortPath}`),
						external: true
					};
				}

				return null;
			}
		}
	]
};
