import { test, expect } from '@playwright/test';
import type { Page } from 'playwright';
import { getPadding, NESTED_ELEMENT_PADDING, NO_PADDING } from '../../../../../../test/playwright-utils';

test.describe.serial('Group', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/test/browser/pages/group-colours.php');
	});

	test.afterAll(async () => {
		await page.close();
	});

	test.describe('Vertically adjacent groups with no background set', () => {
		test('neither element has padding', async () => {
			const wrapper = page.getByTestId('example-1');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group').nth(1));

			expect(element1).toStrictEqual(NO_PADDING);
			expect(element2).toStrictEqual(NO_PADDING);
		});
	});

	test.describe('Vertically adjacent groups with the same background', () => {
		test('First element has padding', async () => {
			const wrapper = page.getByTestId('example-2');
			const element1 = await getPadding(wrapper.locator('.group[data-background="light"]').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('Second element has bottom padding but no top padding', async () => {
			const wrapper = page.getByTestId('example-2');
			const element2 = await getPadding(wrapper.locator('.group[data-background="light"]').nth(1));

			expect(element2).toStrictEqual([0, 16, 16, 16]);
		});
	});

	test.describe('Vertically adjacent groups with different backgrounds', () => {
		test('Both elements have padding', async () => {
			const wrapper = page.getByTestId('example-3');
			const element1 = await getPadding(wrapper.locator('.group[data-background="dark"]').nth(0));
			const element2 = await getPadding(wrapper.locator('.group[data-background="primary"]').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
			expect(element2).toStrictEqual(NESTED_ELEMENT_PADDING);
		});
	});

	test.describe('Nested group, no backgrounds', async () => {
		test('Neither element has padding', async () => {
			const wrapper = page.getByTestId('example-4');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group').nth(1));

			expect(element1).toStrictEqual(NO_PADDING);
			expect(element2).toStrictEqual(NO_PADDING);
		});
	});

	test.describe('Nested group, same background', async () => {
		test('The outer group has padding', async () => {
			const wrapper = page.getByTestId('example-5');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('The inner group has no padding', async () => {
			const wrapper = page.getByTestId('example-5');
			const element2 = await getPadding(wrapper.locator('.group > .group'));

			expect(element2).toStrictEqual(NO_PADDING);
		});
	});

	test.describe('Nested group, different backgrounds', async () => {
		test('The outer group has padding', async () => {
			const wrapper = page.getByTestId('example-6');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('The inner group has padding', async () => {
			const wrapper = page.getByTestId('example-6');
			const element2 = await getPadding(wrapper.locator('.group > .group'));

			expect(element2).toStrictEqual(NESTED_ELEMENT_PADDING);
		});
	});

	test.describe('Two nested groups with the same background as each other', async () => {
		test('The first inner group has padding', async () => {
			const wrapper = page.getByTestId('example-7');
			const element1 = await getPadding(wrapper.locator('.group > .group').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('The second inner group no top padding', async () => {
			const wrapper = page.getByTestId('example-7');
			const element2 = await getPadding(wrapper.locator('.group > .group').nth(1));

			expect(element2).toStrictEqual([0, 16, 16, 16]);
		});
	});

	test.describe('Two nested groups with the same background as the outer group', async () => {
		test('The outer group has padding', async () => {
			const wrapper = page.getByTestId('example-8');
			const element1 = await getPadding(wrapper.locator('.group').nth(0));

			expect(element1).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('Neither of the inner groups have padding', async () => {
			const wrapper = page.getByTestId('example-8');
			const element1 = await getPadding(wrapper.locator('.group > .group').nth(0));
			const element2 = await getPadding(wrapper.locator('.group > .group').nth(1));

			expect(element1).toStrictEqual(NO_PADDING);
			expect(element2).toStrictEqual(NO_PADDING);
		});
	});
});
