import React, { useState, useCallback, useEffect } from 'react';
import type { ModuleExport } from 'storybook/internal/types';
import { omit } from 'lodash';
import { mockPhpSourceCode } from '../utils.ts';
import { PhpCodeBlock } from './PhpCodeBlock.tsx';
import { HtmlCodeBlock } from './HtmlCodeBlock.tsx';
import './CodePanels.style.css';

type DocsCodePanelProps = {
	title: string;
	story: ModuleExport;
};

export const DocsCodePanel = ({ title, story }: DocsCodePanelProps) => {
	const [sourceCode, setSourceCode] = useState<string>('');
	const [outputCode, setOutputCode] = useState<string>('');
	const classShortName = title.split('/').pop();
	const style = story?.args?.style ? `is-style-${story.args.style}` : undefined;
	const content = story?.args?.content;
	// args is an object, omit style and content from it using lodash because they're handled separately
	const attributes = omit(story.args, ['style', 'content']);

	const mockSourceCode = useCallback(async() => {
		const mockCode = await mockPhpSourceCode({
			componentName: classShortName,
			style: style,
			content,
			attributes
		});

		setSourceCode(mockCode);
	}, [story]);

	const getOutputCode = useCallback(async() => {
		const likelyFileName = classShortName.toLowerCase();
		try {
			const response = await fetch(`http://localhost:6001/${likelyFileName}.php`);
			const html = await response.text();
			setOutputCode(html);
		}
		catch (error) {
			console.error('Error:', error);
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
