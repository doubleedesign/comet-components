import React from 'react';
import type { Preview } from '@storybook/server';
import { Controls, Description, DocsContainer, Subtitle, Title, Unstyled } from '@storybook/blocks';
import { Primary } from './blocks/Primary.tsx';
import { Stories } from './blocks/Stories.tsx';
import { withCodeTabs } from './addons/code-tabs/withCodeTabs.tsx';
import './preview.css';
import './custom-components/CodePanels.style.css';

const preview: Preview = {
	parameters: {
		viewMode: 'story',
		controls: {
			disableSaveFromUI: true,
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
				withToolbar: true,
			},
			inlineStories: true,
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

export const decorators = [
	withCodeTabs
];

export default preview;
