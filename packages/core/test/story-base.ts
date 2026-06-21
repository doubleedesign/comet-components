import type { Meta } from '@storybook/html-vite';
import { THEME_COLORS } from "./storybook-helpers.ts";
import type { ArgTypes, InputType } from 'storybook/internal/types';
import _ from 'lodash';

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
	const instance = new StoryBase(ComponentName);
	await instance.init();

	return ({
		args: instance.getArgs(),
		argTypes: instance.getArgTypes(),
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
			argTypeGroupOrder: instance.getInheritanceOrder(),
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

				const url = instance.getStoryUrl(params);

				// Load the HTML content from the URL
				try {
					const response = await fetch(url);
					const html = await response.text();

					// Save the URL of the current "Playground" story (should be the Docs primary story)
					// in local storage for the custom "open in new tab" link to pick up
					if (context?.story === 'Playground') {
						localStorage.setItem('storyUrlWithParams', url);
					}

					return {html};
				}
				catch(error) {
					console.error(error);
					return {html: ''};
				}
			}
		],
		render: (args, { loaded }) => {
			return loaded.html ?? '';
		},
	} satisfies Meta<any>);
}

class StoryBase {
	private readonly ComponentName: string;
	private jsonSpec: ComponentSpec;
	private attributes: Record<string, AttributeSpec>;
	private inheritedAttributes: Record<string, Record<string, AttributeSpec>>;
	private inheritanceOrder: string[];

	constructor(componentName: string) {
		this.ComponentName = componentName;
	}

	async init() {
		this.jsonSpec = await this.fetchJsonDef(this.ComponentName);
		this.attributes = this.jsonSpec.attributes;
		this.inheritedAttributes = await this.getInheritedAttributes();
		this.inheritanceOrder = Object.keys(this.inheritedAttributes);
	}

	// TODO: Account for parent components that are not abstract
	async fetchJsonDef(componentName: string, isAbstract = false): Promise<ComponentSpec> {
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
		} catch (error) {
			console.error('Fetch error:', error);
			throw error;
		}
	}

	async getInheritedAttributes() {
		if (!this.jsonSpec.extends) {
			return {};
		}

		async function getAttrs(spec: ComponentSpec, fetcher: Function, acc = {}) {
			if (!spec.extends) {
				return acc;
			}

			const nextSpec = await fetcher(spec.extends, true);

			acc[nextSpec.name] = nextSpec.attributes;

			return getAttrs(nextSpec, fetcher, acc);
		}

		return await getAttrs(this.jsonSpec, this.fetchJsonDef);
	}

	getArgs() {
		const args = _.reduce(this.attributes, function(acc, value, key) {
			if(value?.default) {
				acc[key] = value.default;
			}

			return acc;
		}, {});

		return this._sortArgTypes(args);
	}

	getArgTypes(): ArgTypes {
		const self = this;

		const inheritedArgTypes = _.reduce(this.inheritanceOrder, function(acc, parentComponent) {
			const attrs = self.inheritedAttributes[parentComponent];
			const transformed = self._transformToArgTypes(attrs, parentComponent);

			return _.merge(acc, transformed);
		}, {});


		return self._sortArgTypes(
			// Inherited needs to go first so this component's attribute defaults get inserted, rather than the parent class's defaults being used
			// Note: That doesn't work for supported values in selectbox controls though - so those are handled within _getSelectOptions
			_.merge(
				inheritedArgTypes,
				self._transformToArgTypes(this.attributes),
			)
		);
	}

	_transformToArgTypes(attributes: Record<string, AttributeSpec>, parent?: string): ArgTypes {
		const self = this;

		return _.reduce(attributes, function(acc, value, key) {
			acc[key] = {
				description: value.description,
				table: {
					category: parent ? 'Inherited from ' + parent :  undefined,
					defaultValue: { summary: value?.default?.toString() || ''},
					type: { summary: value.type }
				},
				control: {
					type: self._getControlType(value),
					disable: ['shortName', 'classes', 'style'].includes(key),
					...(self._getNumberOptions(value) || {})
				},
				options: self._getSelectOptions(key, value)
			}

			return acc;
		}, {});
	}

	_sortArgTypes(argTypes: Record<string, InputType>): ArgTypes {
		const preferredOrder = ['tagName', 'size', 'hAlign', 'vAlign', 'id', 'testId', 'shortName', 'context', 'classes', 'style'];

		const sorted = Object.entries(argTypes).sort(([a], [b]) => {
			const aIndex = preferredOrder.indexOf(a);
			const bIndex = preferredOrder.indexOf(b);

			const aOrder = aIndex === -1 ? Infinity : aIndex;
			const bOrder = bIndex === -1 ? Infinity : bIndex;

			return aOrder - bOrder;
		});

		return sorted.reduce((acc, [key, value]) => {
			acc[key] = value;
			return acc;
		}, {});
	}

	_getControlType(value: any) {
		if(['ThemeColor', 'ThemeColor|ThemeGradient', 'ContainerSize', 'Alignment', 'ResponsiveStyle', 'Orientation', 'Tag'].includes(value.type)) {
			return 'select';
		}

		if(value.type === 'string' && value?.supported) {
			return 'select';
		}

		if(value.type === 'int') {
			return 'number';
		}

		if(value.type === 'boolean' || value.type === 'bool') {
			return 'boolean';
		}

		return value.type;
	}

	_getSelectOptions(key: string, value: any) {
		// Check if this component's definition specifies supported values, as they may be narrower than a parent component/trait's default
		if(this.attributes[key]?.supported) {
			return this.attributes[key].supported;
		}

		if(value.type === 'ThemeColor|ThemeGradient') {
			return THEME_COLORS;
		}

		return value?.supported;
	}

	_getNumberOptions(value: AttributeSpec) {
		if(value.type !== 'int' || !value.supported) return {};

		const supportedNumbers = value.supported.map(Number).filter(n => !isNaN(n));

		return {
			min: Math.min(...supportedNumbers),
			max: Math.max(...supportedNumbers),
			step: 1
		}
	}


	getInheritanceOrder() {
		return this.inheritanceOrder;
	}

	getStoryUrl(params: URLSearchParams): string {
		const shortName = kebabCase(this.ComponentName);

		// Default - prod URL
		let baseUrl = `https://cometcomponents.io`;

		// Dev/test URL
		if (window.location.hostname.startsWith('storybook.comet-components.test')) {
			params.append('__debug', 'true');
			baseUrl = `https://comet-components.test`;
		}

		return `${baseUrl}/packages/core/src/components/${this.ComponentName}/__tests__/${shortName}.php?${params.toString()}`;
	}
}

function kebabCase(str) {
	return str.replace(/([a-z0-9])([A-Z])/g, '$1-$2').toLowerCase();
}
