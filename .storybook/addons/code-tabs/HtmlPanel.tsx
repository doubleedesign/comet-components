import React, { useState, useEffect } from 'react';
import { addons } from '@storybook/manager-api';
import { HtmlCodeBlock } from '../../custom-components/HtmlCodeBlock.tsx';
import { RENDER_PREVIEW_TAB, GET_PREVIEW_HTML } from './constants.tsx';

/**
 * The HTML output panel used in the @doubleedesign/code-tabs addon,
 * which adds a tab to story view that displays the raw HTML result of the current story.
 */
export const HtmlPanel = () => {
	const [html, setHtml] = useState<string>('');
	const channel = addons.getChannel();

	useEffect(() => {
		const handleHtmlContent = (htmlContent: string) => {
			setHtml(htmlContent);
		};

		channel.emit(GET_PREVIEW_HTML);
		channel.on(RENDER_PREVIEW_TAB, handleHtmlContent);

		// Cleanup
		return () => {
			channel.off(RENDER_PREVIEW_TAB, handleHtmlContent);
		};
	}, [channel]);

	return (
		<HtmlCodeBlock codeString={html}/>
	);
};
