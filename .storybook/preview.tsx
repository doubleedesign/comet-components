import React from 'react';
import type { Preview } from '@storybook/server';
import { Controls, Description, DocsContainer, Subtitle, Title, Unstyled, CodeOrSourceMdx } from '@storybook/blocks';
import { Primary } from './blocks/Primary.tsx';
import { Stories } from './blocks/Stories.tsx';
import { withServerPageStates } from './decorators/server-page-states/withServerPageStates.tsx';
import { withCodeTabs } from './addons/code-tabs/withCodeTabs.tsx';
import './preview.css';
import './custom-components/CodePanels.style.css';
import comet from './theme.ts';
import { PhpCodeBlock } from './custom-components/PhpCodeBlock.tsx';

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
			theme: comet,
			// Code is handled in a custom component
			source: {
				code: null,
				disable: true,
			},
			canvas: {
				withToolbar: true,
			},
			components: {
				// eslint-disable-next-line @typescript-eslint/no-explicit-any
				code: (props: { className?: string, children: any }) => {
					// For PHP, use the same custom component as is used for source code in the component docs
					if(props?.className === 'language-php') {
						return <PhpCodeBlock codeString={props.children} />;
					}

					return <CodeOrSourceMdx {...props} />;
				}
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
				const component = context?.primaryStory?.title.split('/')?.reverse()[0]?.toLowerCase() || 'default';

				return (
					<div className={`docs-wrapper docs-wrapper--${component}`}>
						<DocsContainer context={context}>
							{children}
						</DocsContainer>
					</div>
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
						<div className="controls-wrapper">
							<Controls/>
						</div>
						<div className="stories-wrapper">
							<Stories includePrimary={false} title="Variations"/>
						</div>
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
