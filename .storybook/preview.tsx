import React from 'react';
import type { Preview } from '@storybook/server';
import { Controls, Description, DocsContainer, Primary, Stories, Subtitle, Title, Unstyled } from '@storybook/blocks';
import './preview.css';

const preview: Preview = {
	parameters: {
		controls: {
			matchers: {
				color: /(background|color)$/i,
				date: /Date$/i,
			},
		},
		docs: {
			source: {
				code: null
			},
			container: ({ children, context }) => {
				return (
					<DocsContainer context={context}>
						{children}
					</DocsContainer>
				);
			},
			page: () => (
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
			)
		}
	},
	tags: ['autodocs'],
};

export default preview;
