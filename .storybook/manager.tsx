import React from 'react';
import { addons, types } from '@storybook/manager-api';
import { themes } from '@storybook/theming';
import { DOCS_PREPARED, DOCS_RENDERED, STORY_PREPARED, SET_CURRENT_STORY, UPDATE_QUERY_PARAMS } from '@storybook/core-events';
import {  HtmlPanel } from './custom-components/HtmlPanel.tsx';
import './manager.css';
import { PhpPanel } from './custom-components/PhpPanel.tsx';

addons.setConfig({
	theme: themes.light,
	sidebar: {
		showRoots: true,
		filters: {
			// Only show Docs pages in the sidebar
			// patterns: (item) => {
			// 	return item.name === 'Docs';
			// }
		}
	},
});

// Hacky workaround to force switch back to Canvas tab if a custom code tab is active
// updateQueryParams is the event emitted when the Canvas tab is clicked on, but programmatically emitting that doesn't work
function switchToCanvasTab(channel) {
	if(window.location.search.endsWith('code-tabs/html-result') || window.location.search.endsWith('code-tabs/php-input')) {
		const canvasButton: HTMLButtonElement | undefined = Array.from(document.querySelectorAll('.sb-bar button'))
			.find(button => {
				return button.textContent.trim() === 'Canvas';
			}) as HTMLButtonElement | undefined;

		if (canvasButton) {
			canvasButton.click();
		}
	}
}

addons.register('@doubleedesign/code-tabs', () => {
	const channel = addons.getChannel();
	const PHP_TAB_BUTTON_LABEL = 'PHP source';
	const HTML_TAB_BUTTON_LABEL = 'HTML result';

	// Add/remove classes for customising the toolbar display
	channel.on(STORY_PREPARED, (story) => {
		document.getElementById('root').classList.add('is-story-page');
		document.getElementById('root').classList.remove('is-docs-page');
	});
	channel.on(DOCS_PREPARED, (story) => {
		document.getElementById('root').classList.add('is-docs-page');
		document.getElementById('root').classList.remove('is-story-page');
	});

	// Switch back to Canvas tab when navigating to Docs
	channel.on(DOCS_RENDERED, (story) => {
		switchToCanvasTab(channel);
	});
	// Switch back to Canvas tab if switching stories
	channel.on(SET_CURRENT_STORY, (story) => {
		switchToCanvasTab(channel);
	});

	// Add the custom tabs
	addons.add('@doubleedesign/code-tabs/php-input', {
		type: types.TAB,
		title: PHP_TAB_BUTTON_LABEL,
		render: () => <PhpPanel />
	});
	addons.add('@doubleedesign/code-tabs/html-result', {
		type: types.TAB,
		title: HTML_TAB_BUTTON_LABEL,
		render: () => <HtmlPanel />
	});
});
