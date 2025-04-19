import React from 'react';
import type { Preview } from '@storybook/server';
import { Controls, DocsContainer, Subtitle, Unstyled, CodeOrSourceMdx } from '@storybook/blocks';
import { Primary } from './blocks/Primary.tsx';
import { Stories } from './blocks/Stories.tsx';
import { withCodeTabs } from './addons/code-tabs/withCodeTabs.tsx';
import './preview.css';
import './custom-components/CodePanels.style.css';
import comet from './theme.ts';
import { PhpCodeBlock } from './custom-components/PhpCodeBlock.tsx';
import { withRelativeUrls, withServerPageStates } from './decorators';
import { ResponsiveContainer } from './custom-components/ResponsiveContainer.tsx';
import { Title } from './blocks/Title.tsx';
import { Description } from './blocks/Description.tsx';

// Log all events
// import { addons } from '@storybook/preview-api';
// import events from '@storybook/core-events';
// const channel = addons.getChannel();
// Object.values(events).forEach((event) => {
// 	channel.on(event, (data) => {
// 		console.log(event, data);
// 		debugger;
// 	});
// });

const preview: Preview = {
	parameters: {
		viewMode: 'story',
		server: {
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
		options: {
			storySort: {
				order: [
					'Structure',
					'Layout',
					'Text',
					'**'
				],
			},
		},
		controls: {
			disableSaveFromUI: true,
			matchers: {
				color: /(background|color)$/i,
				date: /Date$/i,
			},
			sort: 'none' // get order from story files
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
			controls: {
				sort: 'none' // get order from story files
			},
			components: {
				// code: (props: { className?: string, children: any }) => {
				// 	// For PHP, use the same custom component as is used for source code in the component docs
				// 	if(props?.className === 'language-php') {
				// 		return <PhpCodeBlock codeString={props.children} />;
				// 	}
				//
				// 	return <CodeOrSourceMdx {...props} />;
				// }
			},
			story: {
				inline: true, // This makes the primary story respond to controls
				parameters: {
					server: { autoRefresh: true },
				},
			},
			container: ({ children, context }) => {
				const component = context?.primaryStory?.title.split('/')?.reverse()[0]?.toLowerCase() || 'default';

				// Extremely hacky way to get the context to where I need it in the ResponsiveContainer
				localStorage.setItem('storyContext', JSON.stringify(context));

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
							<ResponsiveContainer>
								<Primary />
							</ResponsiveContainer>
						</div>
						<div className="controls-wrapper">
							<h2 className="section-heading">Attributes</h2>
							<p>The public properties you can assign to your component at creation time using the <code>$attributes</code> argument.</p>
							{/* eslint-disable-next-line max-len */}
							<p>You can also assign any <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Attributes" target="_blank">HTML attributes</a> that are valid for the selected tag.</p>
							<Controls/>
						</div>
						{/*<div className="stories-wrapper">*/}
						{/*	<Stories includePrimary={false} title="Variations and examples"/>*/}
						{/*</div>*/}
					</Unstyled>
				);
			}
		}
	},
	tags: ['autodocs'],
};

export const decorators = [
	withRelativeUrls,
	//withCodeTabs,
	withServerPageStates
];

export default preview;
