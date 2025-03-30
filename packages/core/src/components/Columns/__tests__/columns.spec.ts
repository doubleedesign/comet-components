import { test, expect, Page } from '@playwright/test';
import { getPadding, NESTED_ELEMENT_PADDING, NO_PADDING } from '../../../../../../test/playwright-utils';

test.describe('Columns', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/pages/columns-colours.php', {
			waitUntil: 'domcontentloaded',
		});
	});

	test.afterAll(async () => {
		await page.close();
	});

	test.describe('Inner columns basic behaviour', () => {

		test('No backgrounds = no padding', async() => {
			const wrapper = page.getByTestId('example-1');
			const column1 = await getPadding(wrapper.locator('.column').nth(0));
			const column2 = await getPadding(wrapper.locator('.column').nth(1));

			expect(column1).toStrictEqual(NO_PADDING);
			expect(column2).toStrictEqual(NO_PADDING);
		});

		// If one does, they all should so that the content is aligned (except in the cases tested below)
		test('If at least one has a background, all should have padding', async() => {
			const wrapper = page.getByTestId('example-1');
			const columnSet2 = wrapper.locator('.columns').nth(1);
			const column1 = await getPadding(columnSet2.locator('.column').nth(0));
			const column2 = await getPadding(columnSet2.locator('.column').nth(1));

			expect(column1).toStrictEqual(NESTED_ELEMENT_PADDING);
			expect(column2).toStrictEqual(NESTED_ELEMENT_PADDING);
		});
	});

	test.describe('Column set basic behaviour', () => {

		test('No background = no padding', async() => {
			const wrapper = page.getByTestId('example-2');
			const columnSet1 =  await getPadding(wrapper.locator('.columns').nth(0));

			expect(columnSet1).toStrictEqual(NO_PADDING);
		});

		test('Same background as parent = no padding', async () => {
			const wrapper = page.getByTestId('example-2');
			const columnSet = wrapper.locator('.columns[data-background="primary"]').nth(0);
			const columnSet1Padding = getPadding(columnSet);

			expect(columnSet1Padding).toStrictEqual(NO_PADDING);
		});

		test('Different background to parent = has padding', () => {
			const wrapper = page.getByTestId('example-2');
			const columnSet = wrapper.locator('[data-background="primary"] > .columns[data-background="light"]').nth(0);
			const columnSet1Padding = getPadding(columnSet);

			expect(columnSet1Padding).toStrictEqual(NESTED_ELEMENT_PADDING);
		});

		test.describe('Adjacent with same background', () => {

			test('First has top and bottom padding', async() => {
				const wrapper = page.getByTestId('example-2');
				const columnSet1 = wrapper.locator('.columns').nth(1);
				const columnSet1Padding = await getPadding(columnSet1);

				expect(columnSet1Padding).toStrictEqual(NESTED_ELEMENT_PADDING);
			});

			test('Middle has bottom padding but no top padding', async() => {
				const wrapper = page.getByTestId('example-2');
				const columnSet2 = wrapper.locator('.columns').nth(2);
				const columnSet2Padding = await getPadding(columnSet2);

				expect(columnSet2Padding).toStrictEqual([0, 0, 16, 0]);
			});

			test('Last has bottom padding but no top padding', async() => {
				const wrapper = page.getByTestId('example-2');
				const columnSet3 = wrapper.locator('.columns').nth(3);
				const columnSet3Padding = await getPadding(columnSet3);

				expect(columnSet3Padding).toStrictEqual([0, 0, 16, 0]);
			});
		});

		test.describe('Adjacent with different backgrounds', () => {
			test('Both have top and bottom padding', async() => {
				const wrapper = page.getByTestId('example-2');
				const columnSet1 = await getPadding(wrapper.locator('.columns').nth(3));
				const columnSet2 = await getPadding(wrapper.locator('.columns').nth(4));

				expect(columnSet1).toStrictEqual(NESTED_ELEMENT_PADDING);
				expect(columnSet2).toStrictEqual(NESTED_ELEMENT_PADDING);
			});
		});
	});
});
