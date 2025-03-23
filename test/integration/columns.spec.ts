import { test, expect } from '@playwright/test';

test.describe('When Container and Columns have no set background', () => {
	test('Columns does not have padding', async ({ page }) => {
		await page.goto('/pages/container--columns.php', {
			waitUntil: 'domcontentloaded',
		});

		const container = page.getByTestId('example-1');
		const columns = container.locator('div[class="columns"]');

		expect(columns).not.toHaveCSS('padding', '*');
	});

	test('Column with no set background does not have padding', () => {
		//
	});

	test('Column with a different background has padding', () => {
		//
	});
});

// test.describe('When Container and Columns have the same background', () => {
// 	test('Columns does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with no set background does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with a different background has padding', () => {
// 		//
// 	});
// });
//
// test.describe('When Container has no set background but Columns does', () => {
// 	test('Columns has padding', () => {
// 		//
// 	});
//
// 	test('Column with no set background does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with a different background to Columns has padding', () => {
// 		//
// 	});
//
// 	test('Column with the same background to Columns does not have padding', () => {
// 		//
// 	});
// });
//
// test.describe('When Container has a different background to Columns', () => {
// 	test('Columns has padding', () => {
// 		//
// 	});
//
// 	test('Column with no set background does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with a different background to Columns has padding', () => {
// 		//
// 	});
// });
//
// test.describe('When Container has a background colour set but Columns does not', () => {
//
// 	test('Columns does not have padding', () => {
//
// 	});
//
// 	test('Column with no set background does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with the same background as Container does not have padding', () => {
// 		//
// 	});
//
// 	test('Column with a different background to Container has padding', () => {
// 		//
// 	});
// });
