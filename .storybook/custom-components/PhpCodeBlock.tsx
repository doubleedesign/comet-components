import React, { useState, useCallback, useEffect } from 'react';
import hljs from 'highlight.js';
import prettier from 'prettier';
// @ts-expect-error TS2307: Cannot find module @prettier/ plugin-php or its corresponding type declarations.
import phpPlugin from '@prettier/plugin-php';

export const PhpCodeBlock = ({ codeString }) => {
	const [code, setCode] = useState<string>('');

	const formatAndHighlightCode = useCallback(async() => {
		// Trim extra tabs so I don't have to use ugly indenting in my string literals that are passed to this
		const trimmedCode = codeString.split('\n').map((line, index) => {
			return line.replace('\t\t', '');
		}).join('\n').trim();

		// Formatting
		const formattedCode = await prettier.format(trimmedCode, {
			parser: 'php',
			plugins: [phpPlugin],
			phpVersion: '8.2',
			tabWidth: 4,
			useTabs: true,
			printWidth: 120,
			braceStyle: '1tbs',
		});

		// Base syntax highlighting
		const highlightedCode = hljs.highlight(formattedCode, {
			language: 'php'
		}).value;

		const updatedHighlightedCode = highlightedCode
			// Add spans around brackets and parentheses
			.replaceAll(/(\[|\])/g, '<span class="hljs-bracket">$1</span>')
			.replaceAll(/(\(|\))/g, '<span class="hljs-paren">$1</span>')
			.replaceAll(/(\\)/g, '<span class="hljs-backslash">$1</span>')
			// Add spans around some other specific stuff it misses
			.replaceAll('-&gt;value;', '-&gt;<span class="hljs-variable">value</span>;');

		// Prettier's tab settings have no effect on manually added \t characters, so this is a hack to customise the width
		const updatedHighlightedCodeWithCustomIndents = updatedHighlightedCode.split('\n').map((line) => {
			if (line.startsWith('\t')) {
				return `<span class="hljs-indented-php">${line.trim()}</span>`;
			}

			return line;
		}).join('\n');

		setCode(updatedHighlightedCodeWithCustomIndents);
	}, [codeString]);

	useEffect(() => {
		formatAndHighlightCode();
	}, [codeString]);

	return (
		<pre className="code-preview code-preview--php hljs">
			<code className="language-php">
				<div dangerouslySetInnerHTML={{ __html: code }} />
			</code>
		</pre>
	);
};
