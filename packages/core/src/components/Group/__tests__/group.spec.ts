import { test, expect } from '@playwright/test';
import { getPadding } from '../../../../../../test/playwright-utils';

test.describe('Group', () => {
	test.describe('Vertically adjacent groups with no background set', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('neither element has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-1');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group').nth(1));

			expect(element1).toStrictEqual([0, 0, 0, 0]);
			expect(element2).toStrictEqual([0, 0, 0, 0]);
		});
	});

	test.describe('Vertically adjacent groups with the same background', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('First element has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const element1 = await getPadding(wrapper.locator('.group[data-background="light"]').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
		});

		test('Second element has bottom padding but no top padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-2');
			const element2 = await getPadding(wrapper.locator('.group[data-background="light"]').nth(1));

			expect(element2).toStrictEqual([0, 16, 16, 16]);
		});
	});

	test.describe('Vertically adjacent groups with different backgrounds', () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('Both elements have padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-3');
			const element1 = await getPadding(wrapper.locator('.group[data-background="dark"]').nth(0));
			const element2 = await getPadding(wrapper.locator('.group[data-background="primary"]').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
			expect(element2).toStrictEqual([16, 16, 16, 16]);
		});
	});

	test.describe('Nested group, no backgrounds', async () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('Neither element has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-4');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group').nth(1));

			expect(element1).toStrictEqual([0, 0, 0, 0]);
			expect(element2).toStrictEqual([0, 0, 0, 0]);
		});
	});

	test.describe('Nested group, same background', async () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('The outer group has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-5');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
		});

		test('The inner group has no padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-5');
			const element2 = await getPadding(wrapper.locator('.group > .group'));

			expect(element2).toStrictEqual([0, 0, 0, 0]);
		});
	});

	test.describe('Nested group, different backgrounds', async () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('The outer group has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-6');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
		});

		test('The inner group has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-6');
			const element2 = await getPadding(wrapper.locator('.group > .group'));

			expect(element2).toStrictEqual([16, 16, 16, 16]);
		});
	});

	test.describe('Two nested groups with the same background as each other', async () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('The first inner group has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-7');
			const element1 = await getPadding(wrapper.locator('.group > .group').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
		});

		test('The second inner group no top padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-7');
			const element2 = await getPadding(wrapper.locator('.group > .group').nth(1));

			expect(element2).toStrictEqual([0, 16, 16, 16]);
		});
	});

	test.describe('Two nested groups with the same background as the outer group', async () => {
		test.beforeEach(async ({ page }) => {
			await page.goto('/pages/group-colours.php', {
				waitUntil: 'domcontentloaded',
			});
		});

		test('The outer group has padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-8');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual([16, 16, 16, 16]);
		});

		test('Neither of the inner groups have padding', async ({ page }) => {
			const wrapper = page.getByTestId('example-8');
			const element1 = await getPadding(wrapper.locator('.group > .group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group > .group').nth(1));

			expect(element1).toStrictEqual([0, 0, 0, 0]);
			expect(element2).toStrictEqual([0, 0, 0, 0]);
		});
	});
});
