import React, { useState, useCallback, useEffect } from 'react';
import type { ModuleExport } from 'storybook/internal/types';
import { omit } from 'lodash';
import { mockPhpSourceCode } from '../utils.ts';
import { PhpCodeBlock } from './PhpCodeBlock.tsx';
import { HtmlCodeBlock } from './HtmlCodeBlock.tsx';

type DocsCodePanelProps = {
	title: string;
	story: ModuleExport;
};

export const DocsCodePanel = ({ title, story }: DocsCodePanelProps) => {
	const [sourceCode, setSourceCode] = useState<string>('');
	const [outputCode, setOutputCode] = useState<string>('');
	const classShortName = title.split('/').pop();
	const content = story?.args?.content;
	// args is an object, omit style and content from it using lodash because they're handled separately
	const attributes = omit(story.args, ['content']);

	const mockSourceCode = useCallback(async() => {
		const mockCode = await mockPhpSourceCode({
			componentName: classShortName,
			content,
			attributes
		});

		setSourceCode(mockCode);
	}, [story]);

	const getOutputCode = useCallback(async() => {
		const likelyFileName = classShortName.toLowerCase();
		const urlParams = Object.entries(story.args)
			.filter(([key, value]) => value)
			.map(([key, value]) => `${key}=${value}`).join('&');
		const url = `http://localhost:6001/components/${likelyFileName}.php?${urlParams}`;

		const MAX_RETRIES = 3;
		let retryCount = 0;

		while (retryCount < MAX_RETRIES) {
			try {
				const response = await fetch(url);
				const html = await response.text();
				setOutputCode(html);

				return;
			}
			catch (error) {
				retryCount++;
				console.error(`Attempt ${retryCount} failed:`, error);

				if (retryCount === MAX_RETRIES) {
					console.error('Max retries reached. Final error:', error);
					break;
				}

				await new Promise(resolve => setTimeout(resolve, 500));
			}
		}
	}, [story]);

	useEffect(() => {
		mockSourceCode();
		getOutputCode();
	}, [story]);

	return (
		<div className="code-preview-pair">
			<figure>
				<figcaption>PHP source</figcaption>
				<PhpCodeBlock codeString={sourceCode} />
			</figure>
			<figure>
				<figcaption>HTML output</figcaption>
				<HtmlCodeBlock codeString={outputCode} />
			</figure>
		</div>
	);
};
