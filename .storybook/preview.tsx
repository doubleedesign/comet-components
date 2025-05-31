import React from 'react';
import type { Preview } from '@storybook/html';
import { Title } from './blocks/Title.tsx';
import { Description } from './blocks/Description.tsx';
import { ResponsiveContainer } from './custom-components/ResponsiveContainer.tsx';
import { Primary } from './blocks/Primary.tsx';
import { CommonAttributes } from './custom-components/CommonAttributes.tsx';
import { Controls, DocsContainer, Subtitle, Unstyled } from '@storybook/blocks';
import comet from './theme.ts';
import './preview.css';
import './custom-components/CodePanels.style.css';
import { addons } from '@storybook/preview-api';
const channel = addons.getChannel();

// Log all events
// import events from '@storybook/core-events';
// Object.values(events).forEach((event) => {
// 	channel.on(event, (data) => {
// 		console.log(event, data);
// 		//debugger;
// 	});
// });

channel.on('storyArgsUpdated', (data) => {
	// Dispatch an event that the ResponsiveContainer can pick up to re-fetch the URL (set in the story files) from local storage
	document.dispatchEvent(new Event('storyArgsUpdatedCustom'));
});

const preview: Preview = {
	parameters: {
		viewMode: 'story',
		controls: {
			disableSaveFromUI: true,
			matchers: {
				color: /(background|color)$/i,
				date: /Date$/i,
			},
			sort: 'none' // get order from story files
		},
		options: {
			storySort: {
				order: [
					'Structure',
					'Navigation',
					'Layout',
					'UI',
					'Text',
					'Calendar',
					'Media',
					'**'
				],
			},
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
							<Controls/>
							<CommonAttributes/>
						</div>
						{/*<div className="stories-wrapper">*/}
						{/*	<Stories includePrimary={false} title="Variations and examples"/>*/}
						{/*</div>*/}
					</Unstyled>
				);
			}
		},
		tags: ['autodocs'],
	},
};

export const decorators = [
	//withCodeTabs,
];


export default preview;
