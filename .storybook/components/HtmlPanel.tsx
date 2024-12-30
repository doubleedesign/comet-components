import React, { useState, useEffect } from 'react';
import { addons } from '@storybook/manager-api';
import { html as beautifyHtml } from 'js-beautify';
import hljs from 'highlight.js';
import 'highlight.js/styles/felipec.css';
import './HtmlPanel.style.css';

export const RENDER_PREVIEW_TAB = 'renderPreviewTab';
export const GET_PREVIEW_HTML = 'getPreviewHtml';

export const HtmlPanel = () => {
	const [html, setHtml] = useState<string>('');
	const channel = addons.getChannel();

	useEffect(() => {
		const handleHtmlContent = (htmlContent: string) => {
			const formattedCode = beautifyHtml(htmlContent, {
				indent_size: 4,
				preserve_newlines: false,
			});
			const highlightedCode = hljs.highlight(
				formattedCode,
				{ language: 'html' }
			).value;

			const lines = highlightedCode.split('\n');
			// Find the line with "browser-test-content" wrapper div
			const browserTestContentOpeningLine = lines.findIndex((line) =>
				line.includes('browser-test-content')
			);
			const browserTestContentClosingLine = lines.length - 4; // assuming it's the last thing before the closing </body> and </html>
			// Highlight the lines between the opening and closing wrapper div
			for (let i = browserTestContentOpeningLine + 1; i < browserTestContentClosingLine; i++) {
				lines[i] = `<span class="hljs-highlighted">${lines[i]}</span>`;
			}

			// Join the lines back together and update the state
			const updatedHighlightedCode = lines.join('\n');
			setHtml(updatedHighlightedCode);
		};

		channel.emit(GET_PREVIEW_HTML); // See preview.tsx for where this event is picked up
		channel.on(RENDER_PREVIEW_TAB, handleHtmlContent); // See preview.tsx for where this event is emitted

		// Cleanup
		return () => {
			channel.off(RENDER_PREVIEW_TAB, handleHtmlContent);
		};
	}, [channel]);

	return (
		<pre className="html-preview hljs">
			<code className="language-html">
				<div dangerouslySetInnerHTML={{ __html: html }} />
			</code>
		</pre>
	);
};
