import React from 'react';
import type { Preview } from '@storybook/server';
import { addons } from '@storybook/preview-api';
import { Description, Controls, Stories, DocsContainer, Subtitle, Title, Unstyled } from '@storybook/blocks';
import { Primary } from './blocks/Primary.tsx';
import { RENDER_PREVIEW_TAB, GET_PREVIEW_HTML } from './components/HtmlPanel.tsx';
import './preview.css';

const channel = addons.getChannel();

// Listen for the HTML result tab navigation event and send the HTML content
channel.on(GET_PREVIEW_HTML, () => {
	channel.emit(RENDER_PREVIEW_TAB, function() {
		const storyRoot = document.getElementById('storybook-root');
		if (!storyRoot) return '';

		const divMarker = '<div id="browser-test-content">';
		const htmlParts = storyRoot.innerHTML.split(divMarker);

		// Get head content (everything before the div)
		const headContent = htmlParts[0];

		// Get body content (the div and everything after it)
		const bodyContent = htmlParts.length > 1
			? divMarker + htmlParts[1]
			: ''; // Fallback if div isn't found

		return `
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
	}());
});

// Overall preview configuration
const preview: Preview = {
	parameters: {
		viewMode: 'story',
		controls: {
			matchers: {
				color: /(background|color)$/i,
				date: /Date$/i,
			},
		},
		docs: {
			source: {
				code: null,
				disable: true,
			},
			canvas: {
				withToolbar: false,
			},
			container: ({ children, context }) => {
				return (
					<DocsContainer context={context}>
						{children}
					</DocsContainer>
				);
			},
			page: () => {
				return (
					<Unstyled>
						<Title/>
						<Subtitle/>
						<Description/>
						<div className="breakout">
							<Primary/>
						</div>
						<Controls/>
						<Stories includePrimary={false} title="Variations"/>
					</Unstyled>
				);
			}
		}
	},
	tags: ['autodocs'],
};

export default preview;
