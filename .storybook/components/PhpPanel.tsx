import React, { useState, useCallback, useEffect } from 'react';
import { addons, useStorybookApi } from '@storybook/manager-api';
import prettier from 'prettier';
import phpPlugin from '@prettier/plugin-php';
import hljs from 'highlight.js';
import 'highlight.js/styles/felipec.css';
import './PhpPanel.style.css';
import { API_StoryEntry } from '@storybook/types';
import { omit } from 'lodash';

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
			use DoubleEDesign\\Comet\\Components\\${classShortName};
			
			$attributes = [
				${style ? `'style' => '${style}',\n` : ''}
				${Object.entries(attributes).map(([key, value]) => `'${key}' => '${value}',`).join('\n')}
			];
			$content = '${content}';
			
			$component = new ${classShortName}($attributes, $content);
			$component->render();
		`;

		const formattedCode = await prettier.format(mockCode, {
			parser: 'php',
			plugins: [phpPlugin],
			phpVersion: '8.2',
			tabWidth: 4,
			useTabs: true,
			printWidth: 120
		});

		const highlightedCode = hljs.highlight(formattedCode, {
			language: 'php'
		}).value;

		setCode(highlightedCode);
	}, [story]);

	useEffect(() => {
		mockSourceCode();
	}, [channel]);

	return (
		<pre className="php-preview hljs">
			<code className="language-php">
				<div dangerouslySetInnerHTML={{ __html: code }} />
			</code>
		</pre>
	);
};
