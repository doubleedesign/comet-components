import resolve from '@rollup/plugin-node-resolve';

export default {
	input: 'rollup.index.js',
	output: {
		dir: 'dist',
		format: 'esm',
		entryFileNames: 'dist.js'
	},
	plugins: [
		resolve()
	]
};
