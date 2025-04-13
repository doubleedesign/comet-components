// Utility function to get the computed padding of an element as a number
export async function getPadding(element): Promise<number[]> {
	return element.evaluate((el) => {
		const style = window.getComputedStyle(el);

		return [
			parseFloat(style.paddingTop),
			parseFloat(style.paddingRight),
			parseFloat(style.paddingBottom),
			parseFloat(style.paddingLeft)
		];
	});
}

export async function get_vertical_space_between(element1, element2): Promise<number> {
	const el1Data = await element1.evaluate(el => {
		const rect = el.getBoundingClientRect();

		return {
			bottom: rect.bottom,
			computedBottom: el.offsetTop + el.offsetHeight
		};
	});

	const el2Data = await element2.evaluate(el => {
		return {
			top: el.offsetTop
		};
	});

	return Math.floor(el2Data.top) - Math.floor(el1Data.computedBottom);
}

export const SECTION_PADDING = [32, 0, 32, 0];
export const NESTED_ELEMENT_PADDING = [16, 16, 16, 16];
export const NO_PADDING = [0, 0, 0, 0];
