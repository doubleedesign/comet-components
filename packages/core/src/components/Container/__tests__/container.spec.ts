import { test, expect } from '@playwright/test';
import type { Page } from 'playwright';
import { getPadding, NO_PADDING, SECTION_PADDING } from '../../../../../../test/integration/playwright-utils';

test.describe.serial('Container', () => {
	let page: Page;

	test.beforeAll(async ({ browser }) => {
		page = await browser.newPage();
		await page.goto('/packages/core/src/components/Container/__tests__/pages/container-colours.php');
	});

	test.afterAll(async () => {
		await page.close();
	});

	test.describe('Adjacent page section containers with no background set', () => {
		test('First element has top and bottom padding', async () => {
			const element1 = page.getByTestId('example-1');
			const padding1 = await getPadding(element1);

			expect(padding1).toStrictEqual(SECTION_PADDING);
		});

		test('Second element has bottom padding but no top padding', async () => {
			const element2 = page.getByTestId('example-1b');
			const padding2 = await getPadding(element2);

			expect(padding2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with the same background', () => {
		test('First element has top and bottom padding', async () => {
			const element1 = page.getByTestId('example-2');
			const padding1 = await getPadding(element1);

			expect(padding1).toStrictEqual(SECTION_PADDING);
		});

		test('Second element has bottom padding but no top padding', async () => {
			const element2 = page.getByTestId('example-2b');
			const padding2 = await getPadding(element2);

			expect(padding2).toStrictEqual([0, 0, 32, 0]);
		});
	});

	test.describe('Adjacent page section containers with different backgrounds', () => {
		test('Both elements have top and bottom padding', async () => {
			const element1 = page.getByTestId('example-3');
			const element2 = page.getByTestId('example-3b');

			const padding1 = await getPadding(element1);
			const padding2 = await getPadding(element2);

			expect(padding1).toStrictEqual(SECTION_PADDING);
			expect(padding2).toStrictEqual(SECTION_PADDING);
		});
	});

	test.describe('With the same background as the global background', () => {
		// Because page sections are expected to be top-level, they are the exception to the rule that if the only background present is the same as global, that element shouldn't have padding
		test('Page section container should have padding', async () => {
			const wrapper = page.getByTestId('example-4');
			const padding = await getPadding(wrapper);

			expect(padding).toStrictEqual(SECTION_PADDING);
		});

		test('Container without page section wrapper should not have top or bottom padding', async () => {
			const wrapper = page.getByTestId('example-5');
			const padding = await getPadding(wrapper);

			expect(padding[0]).toEqual(0);
			expect(padding[2]).toEqual(0);
		});
	});
});
