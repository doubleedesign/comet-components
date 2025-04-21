export type ComponentContentDefProps = {
	type: string;
	description: string;
	required?: boolean;
};

export type ComponentDefinition = {
	name: string;
	description: string;
	extends: string;
	abstract: boolean;
	isInner: boolean;
	belongsInside: string | string[] | null;
	attributes: object;
	content: ComponentContentDefProps;
	innerComponents: ComponentContentDefProps;
	// Special cases where a component has something different to $content or $innerComponents
	[key: string]: ComponentContentDefProps | unknown;
};
