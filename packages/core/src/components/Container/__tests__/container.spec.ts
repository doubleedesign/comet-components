import { test, expect } from '@playwright/test';
import type { Page } from 'playwright';
import { getPadding, SECTION_PADDING } from '../../../../../../test/playwright-utils';

// Define a test fixture for the page
test.describe('Container', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/test/browser/pages/container-colours.php', {
			waitUntil: 'domcontentloaded',
		});
	});

	test.afterAll(async () => {
		await page.close();
	});

	test.describe('Adjacent page section containers with no background set', () => {
		test('First element has top and bottom padding', async () => {
			const wrapper = page.getByTestId('example-1');
			const element1 = await getPadding(wrapper.locator('.page-section').nth(0));

			expect(element1).toStrictEqual(SECTION_PADDING);
		});

		test('Second element has bottom padding but no top padding', async () => {
			const wrapper = page.getByTestId('example-1');
			const element2 = await getPadding(wrapper.locator('.page-section').nth(1));

			expect(element2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with the same background', () => {
		test('First element has top and bottom padding', async () => {
			const wrapper = page.getByTestId('example-2');
			const element1 = await getPadding(wrapper.locator('.page-section[data-section-background="light"]').nth(0));

			expect(element1).toStrictEqual(SECTION_PADDING);
		});

		test('Second element has bottom padding but no top padding', async () => {
			const wrapper = page.getByTestId('example-2');
			const element2 = await getPadding(wrapper.locator('.page-section[data-section-background="light"]').nth(1));

			expect(element2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with different backgrounds', () => {
		test('Both elements have top and bottom padding', async () => {
			const wrapper = page.getByTestId('example-3');
			const element1 = await getPadding(wrapper.locator('.page-section[data-section-background="dark"]').nth(0));
			const element2 = await getPadding(wrapper.locator('.page-section[data-section-background="primary"]').nth(0));

			expect(element1).toStrictEqual(SECTION_PADDING);
			expect(element2).toStrictEqual(SECTION_PADDING);
		});
	});
});
