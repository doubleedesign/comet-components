import { defineConfig, devices } from '@playwright/test';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

// Get the equivalent of __dirname in ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const projectRoot = resolve(__dirname, '..');

import dotenv from 'dotenv';
import path from 'path';
dotenv.config({ path: path.resolve(__dirname, '../.env') });

/**
 * See https://playwright.dev/docs/test-configuration.
 */
export default defineConfig({
	testDir: resolve(projectRoot, 'packages/core/src/components'),
	testMatch: '**/__tests__/*.spec.ts',
	fullyParallel: true,
	reporter: 'html',
	timeout: 60000,
	expect: {
		timeout: 10000, // Assertion timeout
	},
	use: {
		headless: true,
		viewport: { width: 1024, height: 768 },
		navigationTimeout: 60000,
		baseURL: process.env.BROWSER_TEST_URL,
	},

	projects: [
		{
			name: 'firefox',
			use: { ...devices['Desktop Firefox'] },
		},
	],
});
