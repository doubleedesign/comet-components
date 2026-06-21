// Copied from https://github.com/storybookjs/storybook/blob/7e7251e00f69ee6026741997312237f0053c26ce/code/addons/docs/src/blocks/blocks/Controls.tsx
// for use in custom Controls component

import React, { useEffect, useState } from 'react';
import type { DocsContextProps, PreparedStory } from 'storybook/internal/types';
import { GLOBALS_UPDATED } from 'storybook/internal/core-events';

type Globals = Record<string, any>;

export const useGlobals = (story: PreparedStory, context: DocsContextProps): [Globals] => {
	const storyContext = context.getStoryContext(story);

	const [globals, setGlobals] = useState(storyContext.globals);
	useEffect(() => {
		const onGlobalsUpdated = (changed: { globals: Globals }) => {
			setGlobals(changed.globals);
		};
		context.channel.on(GLOBALS_UPDATED, onGlobalsUpdated);

		return () => context.channel.off(GLOBALS_UPDATED, onGlobalsUpdated);
	}, [context.channel]);

	return [globals];
};
