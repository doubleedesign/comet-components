import { test, expect } from '@playwright/test';
import type { Page } from 'playwright';
import {
	get_vertical_space_between,
	getPadding,
	NESTED_ELEMENT_PADDING,
	NO_PADDING
} from '../../../../../../test/integration/playwright-utils';

test.describe.serial('Group', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/packages/core/src/components/Group/__tests__/pages/group-colours.php');
	});

	test.afterAll(async () => {
		await page.close();
	});

	test('Vertically adjacent, no backgrounds = no padding', async () => {
		const wrapper = page.getByTestId('example-1');
		const element1 = await getPadding(wrapper.locator('.group').nth(0));
		const element2 = await getPadding(wrapper.locator('.group').nth(1));

		expect(element1).toStrictEqual(NO_PADDING);
		expect(element2).toStrictEqual(NO_PADDING);
	});

	test('Vertically adjacent, same background = do not double up on space between', async () => {
		const wrapper = page.getByTestId('example-2');
		const element1 = wrapper.locator('.group').nth(0);
		const element2 = wrapper.locator('.group').nth(1);

		const space = await get_vertical_space_between(element1, element2);

		expect(space).toEqual(16);
	});

	test('Nested group, no backgrounds = no padding', async () => {
		const wrapper = page.getByTestId('example-4');
		const outer = await getPadding(wrapper);
		const inner = await getPadding(wrapper.locator('.group').nth(0));

		expect(outer).toStrictEqual(NO_PADDING);
		expect(inner).toStrictEqual(NO_PADDING);
	});

	test('Nested group, same background = padding on outer and not inner', async () => {
		const wrapper = page.getByTestId('example-5');
		const outer = await getPadding(wrapper);
		const inner = await getPadding(wrapper.locator('.group').nth(0));

		expect(outer).toStrictEqual(NESTED_ELEMENT_PADDING);
		expect(inner).toStrictEqual(NO_PADDING);
	});

	test('Nested group, different backgrounds = both have padding', async () => {
		const wrapper = page.getByTestId('example-6');
		const inner = await getPadding(wrapper);
		const outer = await getPadding(wrapper.locator('.group').nth(0));

		expect(outer).toStrictEqual(NESTED_ELEMENT_PADDING);
		expect(inner).toStrictEqual(NESTED_ELEMENT_PADDING);
	});
});
