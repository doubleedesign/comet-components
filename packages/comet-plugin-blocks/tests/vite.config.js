import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { fileURLToPath, URL } from 'node:url';
import path from 'path';

export default defineConfig({
	plugins: [react()],
	define: {
		'process.env.NODE_ENV': '"development"',
	},
	resolve: {
		dedupe: [
			'@wordpress/rich-text',
			'react',
			'react-dom',
			'@wordpress/element',
		],
	},
});
