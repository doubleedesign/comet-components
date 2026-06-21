import React, { useContext, useMemo } from 'react';
import { DocsContext } from '@storybook/addon-docs/blocks';
import type { ModuleExports, PreparedStory } from 'storybook/internal/types';
import { type PropDescriptor, filterArgTypes } from 'storybook/preview-api';
import type { Renderer } from 'storybook/internal/csf';
import _ from 'lodash';

import { PureArgsTable } from '@storybook/addon-docs/blocks';
import { useControlsInteractiveState } from './hooks/use-controls-interactive-state.tsx';

const usePrimaryStory = () => {
	const context = useContext(DocsContext);
	const stories = context.componentStories();

	return stories.find((story) => story.tags.includes('autodocs'));
};

// ArgsTable doesn't support withMdxComponentOverride, nor is there a way to override just the sorting/ordering logic.
// so we have to override the whole Controls component that is used to render it.
// Note: This hasn't been tested with complex setups like subcomponent controls.
export function CustomControls() {
	const context = useContext(DocsContext);
	const story = usePrimaryStory();
	const props = { of: story.moduleExport };
	const { argTypes, parameters } = story;
	const interactiveState = useControlsInteractiveState(story, context);
	const { args, globals, updateArgs, resetArgs } = interactiveState;

	const filterProps = getControlsFilterProps(story, props);
	const filteredMainRows = filterArgTypes(argTypes, filterProps.include, filterProps.exclude);

	const sortedArgTypes = useMemo(() => {
		if(!parameters?.argTypeGroupOrder) {
			return filteredMainRows;
		}

		return _.fromPairs(_.sortBy(_.toPairs(filteredMainRows), function([key, value]) {
			const category = value.table?.category || '';

			return parameters.argTypeGroupOrder.indexOf(category.replace('Inherited from ', ''));
		}));
	}, [filteredMainRows, parameters?.argTypeGroupOrder]);

	return (
		<PureArgsTable
			args={args}
			rows={sortedArgTypes}
			globals={globals}
			updateArgs={updateArgs}
			resetArgs={resetArgs}
			{...filterProps}
		/>
	);
}


// Copied from https://github.com/storybookjs/storybook/blob/7e7251e00f69ee6026741997312237f0053c26ce/code/addons/docs/src/blocks/blocks/Controls.tsx
type ControlsParameters = {
	include?: PropDescriptor;
	exclude?: PropDescriptor;
	sort?: any;
};

type ControlsProps = ControlsParameters & {
	of?: Renderer['component'] | ModuleExports;
};

function getControlsFilterProps(story: PreparedStory, props: ControlsProps): ControlsParameters {
	const controlsParameters = story.parameters.docs?.controls || ({} as ControlsParameters);

	return {
		include: props.include ?? controlsParameters.include,
		exclude: props.exclude ?? controlsParameters.exclude,
		sort: props.sort ?? controlsParameters.sort,
	};
}

