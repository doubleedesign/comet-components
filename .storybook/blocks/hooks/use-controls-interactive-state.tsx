// Copied from https://github.com/storybookjs/storybook/blob/7e7251e00f69ee6026741997312237f0053c26ce/code/addons/docs/src/blocks/blocks/Controls.tsx
// for use in custom Controls component

import { useId } from '@react-aria/utils';
import { useArgs } from './use-args.tsx';
import type { DocsContextProps, PreparedStory } from 'storybook/internal/types';
import { useGlobals } from './use-globals.tsx';

type Args = Record<string, any>;
type Globals = Record<string, any>;

type ControlsInteractiveState = {
	controlsId: string;
	args: Args;
	globals: Globals;
	updateArgs: ReturnType<typeof useArgs>[1];
	resetArgs: ReturnType<typeof useArgs>[2];
};

export function useControlsInteractiveState(
	story: PreparedStory,
	context: DocsContextProps
): ControlsInteractiveState {
	// Disambiguate multiple <Controls /> blocks rendered for the same story on a single page.
	// React Aria's useId gives a stable id per component instance, with a polyfill for
	// React versions that lack the built-in useId.
	const controlsId = useId();
	const [args, updateArgs, resetArgs] = useArgs(story, context);
	const [globals] = useGlobals(story, context);

	return { controlsId, args, globals, updateArgs, resetArgs };
}
