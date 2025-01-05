type MockPhpSourceCodeArgs = {
	componentName: string;
	content: string;
	classes: string[];
	attributes: Record<string, string>;
};

/**
 * Mocks PHP source code for a given component.
 * This is because it's not straightforward to load the real PHP file.
 * Some assumptions are made here based on how Comet Components generally work.
 * @param componentName PascalCase component class name
 * @param content       The content of the component
 * @param classes       CSS classes (e.g. is-style-accent)
 * @param attributes    Other component attributes
 */
export const mockPhpSourceCode = async ({
	componentName,
	content,
	classes,
	attributes
}: MockPhpSourceCodeArgs): Promise<string> => {

	return `
		use DoubleeDesign\\Comet\\Components\\${componentName};
	
		$attributes = [
			${classes ? `'className' => '[${classes}'],` : ''}
			${Object.entries(attributes).map(([key, value], index) => {
				if (Number.parseFloat(value)) {
					return index === 0 ? `'${key}' => ${value},` : `\t'${key}' => ${value},`;
				}

				return `\t'${key}' => '${value}',`;
			}).join('\n')}
		];
		$content = '${content}';
		
		$component = new ${componentName}($attributes, $content);
		$component->render();
	`;
};
