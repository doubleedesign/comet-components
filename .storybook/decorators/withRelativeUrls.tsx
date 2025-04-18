// Decorator to add the server URL to the story context, so relative URLs can be used in the stories
export const withRelativeUrls = (Story, context) => {
	if (context.parameters.server && context.parameters.server.url) {
		let baseUrl = 'https://cometcomponents.io';

		// Check if we are in dev mode, because the build uses the env file so we falsely get the dev URL in the build if we use that to differentiate
		if(window.location.hostname === 'storybook.comet-components.test') {
			baseUrl = 'https://comet-components.test';
		}

		// Prepend baseUrl if it's a relative URL
		if (!context.parameters.server.url.startsWith('http')) {
			context.parameters.server.url = `${baseUrl}${context.parameters.server.url}`;
		}
	}

	return Story();
};
