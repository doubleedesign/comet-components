import React, { useState, useCallback, useEffect } from 'react';
import { addons, useStorybookApi } from '@storybook/manager-api';
import prettier from 'prettier';
// @ts-expect-error TS2307: Cannot find module @prettier/ plugin-php or its corresponding type declarations.
import phpPlugin from '@prettier/plugin-php';
import hljs from 'highlight.js';
import 'highlight.js/styles/felipec.css';
import { API_StoryEntry } from '@storybook/types';
import { omit } from 'lodash';
import './CodePanels.style.css';

/**
 * The PHP code panel used in the @doubleedesign/code-tabs addon,
 * which adds a tab to story view that displays sample PHP code for the current story.
 */
export const PhpPanel = () => {
	const [code, setCode] = useState<string>('');
	const api = useStorybookApi();
	const channel = addons.getChannel();
	const story: API_StoryEntry = api.getCurrentStoryData() as API_StoryEntry;

	const mockSourceCode = useCallback(async () => {
		// Because it's not straightforward to load the real PHP file, I'm gonna go ahead and make some assumptions here
		const classShortName = story.title.split('/').pop();
		const style = story.args.style ? `is-style-${story.args.style}` : null;
		const content = story.args.content;
		// args is an object, omit style and content from it using lodash
		const attributes = omit(story.args, ['style', 'content']);

		const mockCode = `
			use DoubleeDesign\\Comet\\Components\\${classShortName};
		
			$attributes = [
				${style ? `'style' => '${style}',` : ''}
				${Object.entries(attributes).map(([key, value], index) => {
					if(Number.parseFloat(value)) {
						return index === 0 ? `'${key}' => ${value},` : `\t'${key}' => ${value},`;
					}

					return `\t'${key}' => '${value}',`;
				}).join('\n')}
			];
			$content = '${content}';
			
			$component = new ${classShortName}($attributes, $content);
			$component->render();
		`;

		// Trim extra tabs so I can leave the string literal where it is above
		const trimmedCode = mockCode.split('\n').map((line, index) => {
			return line.replace('\t\t\t', '');
		}).join('\n').trim();

		console.log(trimmedCode.split('\n'));

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

		// Syntax highlighting
		const highlightedCode = hljs.highlight(formattedCode, {
			language: 'php'
		}).value;

		// Add spans around brackets and parentheses
		const updatedHighlightedCode = highlightedCode
			.replaceAll(/(\[|\])/g, '<span class="hljs-bracket">$1</span>')
			.replaceAll(/(\(|\))/g, '<span class="hljs-paren">$1</span>')
			.replaceAll(/(\\)/g, '<span class="hljs-backslash">$1</span>');

		// Prettier's tab settings have no effect on manually added \t characters, so this is a hack to customise the width
		const updatedHighlightedCodeWithCustomIndents = updatedHighlightedCode.split('\n').map((line) => {
			if (line.startsWith('\t')) {
				return `<span class="hljs-indented-php">${line.trim()}</span>`;
			}

			return line;
		}).join('\n');

		setCode(updatedHighlightedCodeWithCustomIndents);
	}, [story]);

	useEffect(() => {
		mockSourceCode();
	}, [channel]);

	return (
		<pre className="code-preview code-preview--php hljs">
			<code className="language-php">
				<div dangerouslySetInnerHTML={{ __html: code }} />
			</code>
		</pre>
	);
};
