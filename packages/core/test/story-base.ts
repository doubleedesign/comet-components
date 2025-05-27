import type { Meta } from '@storybook/html';

export const createStoryBase = (ComponentName: string) => ({
	// These are used for the custom "open in new tab" link
	parameters: {
		server: {
			id: `${kebabCase(ComponentName)}.php`,
			url: `/packages/core/src/components/${ComponentName}/__tests__`,
			params: {
				__debug: true
			}
		}
	},
	loaders: [
		async (context) => {
			const params = new URLSearchParams();
			Object.entries(context.args).forEach(([key, value]) => {
				if (value !== undefined && value !== null) {
					params.append(key, String(value));
				}
			});

			const url = buildStoryUrl(ComponentName, params);

			// Load the HTML content from the URL
			const response = await fetch(url);
			const html = await response.text();

			// Save the URL in local storage for the custom "open in new tab" link to pick up
			localStorage.setItem('storyUrlWithParams', url);

			return { html };
		}
	],
	render: (args, { loaded: { html } }) => {
		return html;
	},
} satisfies Meta<any>);

/**
 * Builds a URL for the storybook component based on the component name.
 * @param componentName - PascalCase name of the component
 * @param params - URLSearchParams object containing query parameters
 */
function buildStoryUrl(componentName, params) {
	const shortName = kebabCase(componentName);

	// Default - prod URL
	let baseUrl = `https://cometcomponents.io`;

	// Dev/test URL
	if(window.location.hostname.startsWith('storybook.comet-components.test')) {
		params.append('__debug', 'true');
		baseUrl = `https://comet-components.test`;
	}

	return baseUrl + `/packages/core/src/components/${componentName}/__tests__/${shortName}.php?${params.toString()}`;
}

function kebabCase(str) {
	return str.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();
}
