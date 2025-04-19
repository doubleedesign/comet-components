import React from 'react';
import { addons } from '@storybook/manager-api';
import comet from './theme.ts';
import './manager.css';
import { VueComponentIcon } from './custom-components/icon-items/VueComponentIcon.tsx';
import { JavaScriptComponentIcon } from './custom-components/icon-items/JavaScriptComponentIcon.tsx';

addons.setConfig({
	theme: comet,
	sidebar: {
		showRoots: true,
		filters: {
			patterns: (item) => {
				// If there is only docs, show it as a top-level item
				return !item.tags?.includes('docsOnly') || item.type === 'docs';
			},
		},
		renderLabel: (item) => {
			if(item.tags.includes('vue')) {
				return (
					<>
						{item.name}
						<span className="sbdocs__icon">
							<VueComponentIcon tooltipPosition="right" asLink={false} />
						</span>
					</>
				);
			}
			if(item.tags.includes('javascript')) {
				return (
					<>
						{item.name}
						<span className="sbdocs__icon">
							<JavaScriptComponentIcon tooltipPosition="right" asLink={false} />
						</span>
					</>
				);
			}

			return item.name;
		},
	},
});

