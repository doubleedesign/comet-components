// Utility function to get the computed padding of an element as a number
export async function getPadding(element): Promise<number> {
	const values = await element.evaluate((element) => {
		return [
			window.getComputedStyle(element).getPropertyValue('padding-top'),
			window.getComputedStyle(element).getPropertyValue('padding-right'),
			window.getComputedStyle(element).getPropertyValue('padding-bottom'),
			window.getComputedStyle(element).getPropertyValue('padding-left'),
		];
	});

	return values.map((value: string) => Math.ceil(Number(value.replace('px', ''))));
}
