import React from 'react';
import type { Preview } from '@storybook/server';
import { Controls, Description, DocsContainer, Subtitle, Title, Unstyled } from '@storybook/blocks';
import { Primary } from './blocks/Primary.tsx';
import { Stories } from './blocks/Stories.tsx';
import { withServerPageStates } from './decorators/server-page-states/withServerPageStates.tsx';
import { withCodeTabs } from './addons/code-tabs/withCodeTabs.tsx';
import './preview.css';
import './custom-components/CodePanels.style.css';

// Log all events
// import { addons } from '@storybook/preview-api';
// import events from '@storybook/core-events';
// const channel = addons.getChannel();
// Object.values(events).forEach((event) => {
// 	channel.on(event, (data) => {
// 		console.log(event, data);
// 	});
// });

const preview: Preview = {
	parameters: {
		viewMode: 'story',
		server: {
			url: 'http://localhost:6001',
			fetchStoryTimeout: 5000,
			maxSimultaneousRequests: 2,
			reconnectionAttempts: 5,
			disableConsoleLog: false,
			autoRefresh: true,
			handleArgsUpdates: true,
			fetchOptions: {
				cache: 'no-store'
			},
		},
		controls: {
			disableSaveFromUI: true,
			matchers: {
				color: /(background|color)$/i,
				date: /Date$/i,
			},
		},
		backgrounds: {
			disable: true
		},
		actions: {
			disable: true
		},
		docs: {
			// Code is handled in a custom component
			source: {
				code: null,
				disable: true,
			},
			canvas: {
				withToolbar: true,
			},
			story: {
				inline: true, // This makes the primary story respond to controls
				parameters: {
					server: {
						autoRefresh: true
					}
				},
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
							<Primary />
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

export const decorators = [
	withCodeTabs,
	withServerPageStates
];

export default preview;
