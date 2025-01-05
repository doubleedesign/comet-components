import React, { useState, useCallback, useEffect } from 'react';
import { addons, useStorybookApi } from '@storybook/manager-api';
import { API_StoryEntry } from '@storybook/types';
import { omit } from 'lodash';
import { mockPhpSourceCode } from '../../utils.ts';
import { PhpCodeBlock } from '../../custom-components/PhpCodeBlock.tsx';

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
		const classShortName = story.title.split('/').pop();
		const className = story?.args?.style ? `is-style-${story.args.style}` : undefined;
		const content = story?.args?.content;
		// args is an object, omit style and content from it using lodash because they're handled separately
		const attributes = omit(story.args, ['style', 'content']);

		const mockCode = await mockPhpSourceCode({
			componentName: classShortName,
			classes: [className],
			content,
			attributes
		});

		setCode(mockCode);
	}, [story]);

	useEffect(() => {
		mockSourceCode();
	}, [channel, story]);

	return (
		<PhpCodeBlock codeString={code} />
	);
};
