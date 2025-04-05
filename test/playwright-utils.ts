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

export const SECTION_PADDING = [32, 0, 32, 0];
export const NESTED_ELEMENT_PADDING = [16, 16, 16, 16];
export const NO_PADDING = [0, 0, 0, 0];
