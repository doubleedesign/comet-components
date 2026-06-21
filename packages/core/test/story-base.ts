import type { Meta } from '@storybook/html-vite';
import _ from 'lodash';
import { THEME_COLORS } from "./storybook-helpers.ts";

type ComponentSpec = {
	name: string;
	description: string;
	attributes: Record<string, AttributeSpec>;
	extends?: string;
	[key:string]: any
}

type AttributeSpec = {
	type: string;
	description: string;
	default?: any;
	supported?: any[];
}

export async function createStoryBase(ComponentName: string) {
	const jsonSpec = await fetchJsonDef(ComponentName);
	const attributes = jsonSpec.attributes;

	const inheritedAttrs = await getInheritedAttributes(jsonSpec);
	const inheritanceOrder = Object.keys(inheritedAttrs);
	const inheritedArgTypes = _.reduce(inheritanceOrder, function(acc, parentComponent) {
		const attrs = inheritedAttrs[parentComponent];
		const transformed = transformToArgTypes(attrs, parentComponent);

		return _.merge(acc, transformed);
	}, {});

	const mergedArgTypes = {
		...transformToArgTypes(attributes),
		...inheritedArgTypes
	};

	return ({
		args: _.reduce(jsonSpec.attributes, function(acc, value, key) {
			if(value?.default) {
				acc[key] = value.default;
			}

			return acc;
		}, {}),
		argTypes: mergedArgTypes,
		parameters: {
			// These are used for the custom "open in new tab" link
			server: {
				id: `${kebabCase(ComponentName)}.php`,
				url: `/packages/core/src/components/${ComponentName}/__tests__`,
				params: {
					__debug: true
				}
			},
			// Note: At the time of writing, sorting the argTypes at this level
			// to ensure the correct inheritance order groupings in the table doesn't work.
			// See customised version of Controls component in .storybook/blocks/Controls.tsx
			// for where this parameter is used to handle it (as long as that one is used in the docs - see .storybook/preview.tsx).
			argTypeGroupOrder: inheritanceOrder,
			controls: {
				expanded: true
			},
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

// TODO: Account for parent components that are not abstract
async function fetchJsonDef(componentName: string, isAbstract = false): Promise<ComponentSpec> {
	const basePath = isAbstract
		? 'packages/core/src/base/components/__docs__'
		: `packages/core/src/components/${componentName}/__docs__`;
	const jsonSpecUrl = window.location.hostname.startsWith('storybook.comet-components.test')
		? `https://comet-components.test/${basePath}/${componentName}.json`
		: `https://cometcomponents.io/${basePath}/${componentName}.json`;

	try {
		const response = await fetch(jsonSpecUrl, {
			method: 'GET',
			mode: 'cors',
			cache: 'no-cache',
			headers: {
				'Content-Type': 'application/json',
			},
		});

		return await response.json();
	}
	catch (error) {
		console.error('Fetch error:', error);
		throw error;
	}
}

async function getInheritedAttributes(spec: any, acc = {}) {
	if(!spec.extends) {
		return acc;
	}

	const nextSpec = await fetchJsonDef(spec.extends, true);

	acc[nextSpec.name] = nextSpec.attributes;

	return getInheritedAttributes(nextSpec, acc);
}

function transformToArgTypes(attributes: Record<string, AttributeSpec>, category?: string) {
	return _.reduce(attributes, function(acc, value, key) {
		acc[key] = {
			description: value.description,
			table: {
				category: category ? 'Inherited from ' + category :  undefined,
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
	}, {})
}
