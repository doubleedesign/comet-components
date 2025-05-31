import { addons } from 'storybook/preview-api';
import { RENDER_PREVIEW_TAB, GET_PREVIEW_HTML } from './constants.tsx';

export const withCodeTabs = (StoryFn, context) => {
	const channel = addons.getChannel();

	// Listen for the HTML result tab navigation event and send the HTML content
	channel.on(GET_PREVIEW_HTML, () => {
		const storyRoot = document.getElementById('storybook-root');
		if (!storyRoot) {
			channel.emit(RENDER_PREVIEW_TAB, '<p>Error: Story root not found</p>');

			return;
		}

		const divMarker = '<div id="browser-test-content">';
		const htmlParts = storyRoot.innerHTML.split(divMarker);

		// Get head content (everything before the div)
		const headContent = htmlParts[0];

		// Get body content (the div and everything after it)
		const bodyContent = htmlParts.length > 1
			? divMarker + htmlParts[1]
			: ''; // Fallback if div isn't found

		const formattedHtml = `
			<!DOCTYPE html>
			<html>
			    <head>
			        ${headContent}
			    </head>
			    <body>
			        ${bodyContent}
			    </body>
			</html>
		`;

		channel.emit(RENDER_PREVIEW_TAB, formattedHtml);
	});

	return StoryFn();
};
