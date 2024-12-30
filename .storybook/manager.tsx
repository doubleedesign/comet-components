import React from 'react';
import { addons, types } from '@storybook/manager-api';
import { themes } from '@storybook/theming';
import { DOCS_PREPARED, STORY_PREPARED, SET_CURRENT_STORY, UPDATE_QUERY_PARAMS } from '@storybook/core-events';
import {  HtmlPanel } from './components/HtmlPanel.tsx';
import './manager.css';
import { PhpPanel } from './components/PhpPanel.tsx';

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


addons.register('@doubleedesign/code-tabs', () => {
	const channel = addons.getChannel();
	const PHP_TAB_BUTTON_LABEL = 'PHP source';
	const HTML_TAB_BUTTON_LABEL = 'HTML result';

	channel.on(STORY_PREPARED, (story) => {
		document.getElementById('root').classList.add('is-story-page');
		document.getElementById('root').classList.remove('is-docs-page');
		channel.emit(UPDATE_QUERY_PARAMS, {
			globals: '',
			tab: undefined,
			args: ''
		});
	});

	channel.on(DOCS_PREPARED, (story) => {
		document.getElementById('root').classList.add('is-docs-page');
		document.getElementById('root').classList.remove('is-story-page');
	});

	// Switch back to Canvas tab if switching stories
	channel.on(SET_CURRENT_STORY, (story) => {
		if(window.location.search.endsWith('html-result')) {
			// This is the event emitted when the Canvas tab is clicked on
			// but at the time of writing it doesn't work on its own for programmatically switching tabs
			channel.emit(UPDATE_QUERY_PARAMS, {
				globals: '',
				tab: undefined,
				args: ''
			});

			// Hacky workaround to force switch back to Canvas tab if HTML tab is active
			const canvasButton: HTMLButtonElement | undefined = Array.from(document.querySelectorAll('.sb-bar button'))
				.find(button => {
					return button.textContent.trim() === 'Canvas';
				}) as HTMLButtonElement | undefined;

			if (canvasButton) {
				canvasButton.click();
			}
		}
	});

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
