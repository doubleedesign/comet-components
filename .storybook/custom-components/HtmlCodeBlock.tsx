import React, { useState, useCallback, useEffect } from 'react';
import { html as beautifyHtml } from 'js-beautify';
import hljs from 'highlight.js';
import 'highlight.js/styles/felipec.css';
import './CodePanels.style.css';
import { Loader } from 'storybook/internal/components';

export const HtmlCodeBlock = ({ codeString }) => {
	const [html, setHtml] = useState<string>('');
	const [loading, setLoading] = useState<boolean>(true);

	const formatAndHighlightCode = useCallback(async () => {
		const formattedCode = beautifyHtml(codeString, {
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
	}, [codeString]);

	useEffect(() => {
		formatAndHighlightCode();
	}, [codeString]);

	useEffect(() => {
		if (html) {
			setLoading(false);
		}
	}, [html]);

	return (
		<>
			{loading ? (
				<Loader/>
			) : (
				<pre className="code-preview code-preview--html hljs">
					<code className="language-html">
						<div dangerouslySetInnerHTML={{ __html: html }}/>
					</code>
				</pre>
			)}
		</>
	);
};
