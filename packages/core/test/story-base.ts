import type { Meta } from '@storybook/html-vite';
import _ from 'lodash';
import { THEME_COLORS } from "./storybook-helpers.ts";

type AttributeSpec = {
	type: string;
	description: string;
	default?: any;
	supported?: any[];
}

function getControlType(value: any) {
	if(['ThemeColor', 'ThemeColor|ThemeGradient', 'ContainerSize', 'Alignment', 'ResponsiveStyle', 'Orientation', 'Tag'].includes(value.type)) {
		return 'select';
	}

	if(value.type === 'string' && value?.supported) {
		return 'select';
	}
}

function getSelectOptions(value: any) {
	if(value.type === 'ThemeColor|ThemeGradient') {
		return THEME_COLORS;
	}

	return value?.supported;
}

export async function createStoryBase(ComponentName: string) {
	const jsonSpec = await fetchJsonDef(ComponentName).then(response => {
		return response.json();
	});

	// Filter out attributes that we don't want to add to the controls
	const attributes = _.reduce(jsonSpec.attributes, (acc, value, key) => {
		if (['id', 'classes', 'style', 'testId'].includes(key)) return acc;

		acc[key] = value;
		return acc;
	}, {} as Record<string, AttributeSpec>);


	return ({
		args: _.reduce(attributes, function(acc, value, key) {
			if(value?.default) {
				acc[key] = value.default;
			}

			return acc;
		}, {}),
		argTypes: _.reduce(attributes, function(acc, value, key) {
			acc[key] = {
				description: value.description,
				table: {
					defaultValue: { summary: value?.default?.toString() || ''},
					type: { summary: value.type }
				},
				control: {
					type: getControlType(value),
					disable: ['shortName'].includes(key)
				},
				options: getSelectOptions(value)
			}

			return acc;
		}, {}),
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

				return {html};
			}
		],
		render: (args, {loaded: {html}}) => {
			return html;
		},
	} satisfies Meta<any>);
}

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

async function fetchJsonDef(componentName: string) {
	const jsonSpecUrl = window.location.hostname.startsWith('storybook.comet-components.test')
		? `https://comet-components.test/packages/core/src/components/${componentName}/__docs__/${componentName}.json`
		: `https://cometcomponents.io/packages/core/src/components/${componentName}/__docs__/${componentName}.json`;

	try {
		return await fetch(jsonSpecUrl, {
			method: 'GET',
			mode: 'cors',
			cache: 'no-cache',
			headers: {
				'Content-Type': 'application/json',
			},
		});
	}
	catch (error) {
		console.error('Fetch error:', error);
		throw error;
	}
}
