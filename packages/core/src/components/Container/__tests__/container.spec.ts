import { test, expect } from '@playwright/test';
import { getPadding } from '../../../../../../test/playwright-utils';

test.describe('Container', () => {
	test.describe('Adjacent page section containers with no background set', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/container-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('First element has top and bottom padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-1');
			const element1 = await getPadding(wrapper.locator('.page-section').nth(0));

			expect(element1).toStrictEqual([32, 0, 32, 0]);
		});

		test('Second element has bottom padding but no top padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-1');
			const element2 = await getPadding(wrapper.locator('.page-section').nth(1));

			expect(element2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with the same background', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/container-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('First element has top and bottom padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const element1 = await getPadding(wrapper.locator('.page-section[data-background="light"]').nth(0));

			expect(element1).toStrictEqual([32, 0, 32, 0]);
		});

		test('Second element has bottom padding but no top padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const element2 = await getPadding(wrapper.locator('.page-section[data-background="light"]').nth(1));

			expect(element2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with different backgrounds', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/container-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('Both elements have top and bottom padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-3');
			const element1 = await getPadding(wrapper.locator('.page-section[data-background="dark"]').nth(0));
			const element2 = await getPadding(wrapper.locator('.page-section[data-background="primary"]').nth(0));

			expect(element1).toStrictEqual([32, 0, 32, 0]);
			expect(element2).toStrictEqual([32, 0, 32, 0]);
		});
	});
});
