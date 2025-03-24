import { test, expect } from '@playwright/test';
import { getPadding } from '../../../../../../test/playwright-utils';

test.describe('Columns', () => {
	test.beforeEach(async ({ page }) => {
		await page.goto('/pages/columns-colours.php', {
			waitUntil: 'domcontentloaded',
		});
	});

	test.describe('No backgrounds set on anything', () => {

		test('Columns component does not have padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-1');
			const element1 = await getPadding(wrapper.locator('.columns').nth(0));

			expect(element1).toStrictEqual([0, 0, 0, 0]);
		});

		test('Inner Column components do not have padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-1');
			const column1 = await getPadding(wrapper.locator('.column').nth(0));
			const column2 = await getPadding(wrapper.locator('.column').nth(1));

			expect(column1).toStrictEqual([0, 0, 0, 0]);
			expect(column2).toStrictEqual([0, 0, 0, 0]);
		});
	});

	test.describe('Only the inner columns have backgrounds set', () => {

		test('Columns component does not have padding', async ({ page }) => {
			//
		});

		test('Inner Column components have padding', () => {
			//
		});
	});

	test.describe('Container has background and Columns does not', () => {

		test('Columns component does not have padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const element1 = await getPadding(wrapper.locator('.columns').nth(0));

			expect(element1).toStrictEqual([0, 0, 0, 0]);
		});

		test('Inner Columns components do not have padding when none of them have backgrounds set', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const column1 = await getPadding(wrapper.locator('.column').nth(0));
			const column2 = await getPadding(wrapper.locator('.column').nth(1));

			expect(column1).toStrictEqual([0, 0, 0, 0]);
			expect(column2).toStrictEqual([0, 0, 0, 0]);
		});

		test('If an inner Column has a different background to the container, they all have padding', async ({ page }) => {
			// If one does, they all should so that the content is aligned
		});
	});

	test.describe('Columns has the same background as its container', () => {

		test('Columns component does not have padding', async ({ page }) => {
			//
		});

		test('Column with a different background has padding', async ({ page }) => {
			//
		});

		test('When the inner Columns do not have a background set, they do not have padding', async ({ page }) => {
			//
		});

		test('When one or more of the inner columns has a different background, they all have padding', async ({ page }) => {
			// If one does, they all should so that the content is aligned
		});
	});

	test.describe('Columns has a different background to its container', () => {

		test('Columns component has padding', async({ page }) => {
			//
		});
	});
});
