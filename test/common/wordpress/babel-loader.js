import * as url from 'url';
import * as babel from '@babel/core';
import fs from 'fs';

export async function load(path, context, defaultLoad) {
	if (path.endsWith('.jsx') || path.includes('@wordpress/')) {
		const filePath = url.fileURLToPath(new URL(path));
		const code = fs.readFileSync(filePath, 'utf-8');

		const transformed = await babel.transformAsync(code, {
			filename: filePath,
			presets: [
				['@babel/preset-react', {
					runtime: 'automatic'
				}],
				['@babel/preset-env', {
					'targets': {
						'node': 'current'
					},
					'modules': false
				}]
			],
			plugins: [
				['@babel/plugin-transform-runtime', {
					helpers: true,
					regenerator: true,
					version: '^7.22.5'
				}]
			],
			babelrc: false
		});

		return {
			format: 'module',
			source: transformed.code,
			shortCircuit: true
		};
	}

	return defaultLoad(path, context, defaultLoad);
}
