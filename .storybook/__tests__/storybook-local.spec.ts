import { test, expect, Page } from '@playwright/test';

// TODO: Get these automatically somehow
const componentPages = [
	'structure-container',
	'structure-group',
	'navigation-breadcrumbs',
	'navigation-menu',
	'navigation-sectionmenu'
];

test.describe('Local Storybook', () => {
	test.beforeEach(async ({ browser }) => {
		const page = await browser.newPage();
		await page.goto('/');
		await page.waitForSelector('#storybook-explorer-tree');
	});

	componentPages.forEach((component) => {
		test(`should render ${component} component`, async ({ page }) => {
			await page.goto(`/?path=/docs/${component}`);

			// Wait for #browser-test-content
			const content = page.locator('#browser-test-content');
			await content.waitFor({ state: 'visible' }); // TODO: This doesn't work because it's in an iframe, need to work out how to get into the iframe content

			// Assert that there are no .xdebug-error elements
			const xdebugErrors = await page.$$('.xdebug-error');
			expect(xdebugErrors.length).toBe(0);
		});
	});
});
