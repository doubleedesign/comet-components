// Decorator to add the server URL to the story context, so relative URLs can be used in the stories
export const withRelativeUrls = (Story, context) => {
	if (context.parameters.server && context.parameters.server.url) {
		const baseUrl = process.env.BROWSER_TEST_URL || 'https://storybook.cometcomponents.io';

		// Prepend baseUrl if it's a relative URL
		if (!context.parameters.server.url.startsWith('http')) {
			context.parameters.server.url = `${baseUrl}${context.parameters.server.url}`;
		}
	}

	return Story();
};
