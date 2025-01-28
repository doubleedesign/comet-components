type MockPhpSourceCodeArgs = {
	componentName: string;
	content: string;
	attributes: Record<string, string>;
};

/**
 * Mocks PHP source code for a given component.
 * This is because it's not straightforward to load the real PHP file.
 * Some assumptions are made here based on how Comet Components generally work.
 * @param componentName PascalCase component class name
 * @param content       The content of the component
 * @param attributes    Other component attributes
 */
export const mockPhpSourceCode = async ({
	componentName,
	content,
	attributes
}: MockPhpSourceCodeArgs): Promise<string> => {

	function getAttributesText() {
		return `$attributes = [
				${Object.entries(attributes)
					// Ignore attribute when the value is empty
					.filter(([key, value]) => value !== '')
					// Put into PHP format
					.map(([key, value], index) => {
						// We are expecting Storybook to send a string for the classes but want to show the preferred array format
						if(key === 'classes') {
							return `\t'${key}' => ["${value}"],`;
						}

						if (Number.parseFloat(value)) {
							return index === 0 ? `'${key}' => ${value},` : `\t'${key}' => ${value},`;
						}

						return `\t'${key}' => '${value}',`;
					})
					.join('\n')}
		];`;
	}

	if(!content) {
		return `
		use DoubleeDesign\\Comet\\Components\\${componentName};
		
		${getAttributesText()}
		
		$component = new ${componentName}($attributes, $innerComponents);
		$component->render();
		`;
	}

	return `
		use DoubleeDesign\\Comet\\Components\\${componentName};
	
		${getAttributesText()}
		$content = '${content}';
		
		$component = new ${componentName}($attributes, $content);
		$component->render();
	`;
};
