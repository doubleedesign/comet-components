import { test, expect } from '@playwright/test';
import type { Page } from 'playwright';
import { getPadding, NESTED_ELEMENT_PADDING, NO_PADDING, get_vertical_space_between } from '../../../../../../test/integration/playwright-utils';

test.describe.serial('Columns', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/packages/core/src/components/Columns/__tests__/pages/columns-colours.php');
	});

	test.afterAll(async () => {
		await page.close();
	});

	test.describe('Inner columns', () => {

		test('No backgrounds = no padding', async() => {
			const wrapper = page.getByTestId('example-1');
			const column1 = await getPadding(wrapper.locator('.column').nth(0));
			const column2 = await getPadding(wrapper.locator('.column').nth(1));

			expect(column1).toStrictEqual(NO_PADDING);
			expect(column2).toStrictEqual(NO_PADDING);
		});

		test('If at least one has a different background to the parent, all should have padding', async() => {
			const wrapper = page.getByTestId('example-4');
			const columnSet = wrapper.locator('.columns').nth(0);
			const column1 = await getPadding(columnSet.locator('.column').nth(0));
			const column2 = await getPadding(columnSet.locator('.column').nth(1));

			expect(column1).toStrictEqual(NESTED_ELEMENT_PADDING);
			expect(column2).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('If the only background present is the same as global, none should have padding', async() => {
			const wrapper = page.getByTestId('example-9');
			const columnSet = wrapper.locator('.columns').nth(0);
			const column1 = await getPadding(columnSet.locator('.column').nth(0));
			const column2 = await getPadding(columnSet.locator('.column').nth(1));

			expect(column1).toStrictEqual(NO_PADDING);
			expect(column2).toStrictEqual(NO_PADDING);
		})
	});

	test.describe('Column sets', () => {

		test('No background = no padding', async() => {
			const wrapper = page.getByTestId('example-2');
			const columnSet1 =  await getPadding(wrapper.locator('.columns').nth(0));

			expect(columnSet1).toStrictEqual(NO_PADDING);
		});

		test('Two adjacent with same background do not double up on spacing between', async() => {
			const wrapper = page.getByTestId('example-2');
			const columnSet1 = wrapper.locator('.columns').nth(0);
			const columnSet2 = wrapper.locator('.columns').nth(1);

			const space = await get_vertical_space_between(columnSet1, columnSet2);

			expect(space).toEqual(16);
		});

		test('Two adjacent with different backgrounds have space between', async() => {
			const wrapper = page.getByTestId('example-2');
			const columnSet1 = wrapper.locator('.columns').nth(0);
			const columnSet2 = wrapper.locator('.columns').nth(1);

			const space = await get_vertical_space_between(columnSet1, columnSet2);

			expect(space).toEqual(16);
		});

		test('Two adjacent with different backgrounds, both have padding', async() => {
			const wrapper = page.getByTestId('example-2');
			const columnSet1 = wrapper.locator('.columns').nth(2);
			const columnSet2 = wrapper.locator('.columns').nth(3);

			const columnSet1Padding = await getPadding(columnSet1);
			const columnSet2Padding = await getPadding(columnSet2);

			expect(columnSet1Padding).toStrictEqual(NESTED_ELEMENT_PADDING);
			expect(columnSet2Padding).toStrictEqual(NESTED_ELEMENT_PADDING);
		});
		
		test('Different background to parent = has padding', async () => {
			const wrapper = page.getByTestId('example-4');
			const columnSet = wrapper.locator('.columns').nth(0);
			const columnSet1Padding = await getPadding(columnSet);

			expect(columnSet1Padding).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test('No ancestors have background + background is same as global = no padding', async() => {
			const wrapper = page.getByTestId('example-10');
			const columnSet = wrapper.locator('.columns').nth(0);
			const columnSet1Padding = getPadding(columnSet);

			expect(columnSet1Padding).toStrictEqual(NO_PADDING);
		})
	});
});
