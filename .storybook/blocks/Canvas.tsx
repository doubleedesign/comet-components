// @source https://github.com/storybookjs/storybook/blob/next/code/lib/blocks/src/blocks/Canvas.tsx
// Copied as part of the overrides needed to load a custom Source component
// And also modified to add the story name to the HTML for customising output wrapper styling for specific stories
import React, { useContext } from 'react';
import type { FC } from 'react';

import type { ModuleExport, ModuleExports } from 'storybook/internal/types';
import { DocsContext, type SourceProps, useSourceProps, SourceContext, type StoryProps, Story, useOf } from '@storybook/addon-docs/blocks';
import { Preview, type PreviewProps, type Layout } from './components/Preview.tsx';

type CanvasProps = Pick<PreviewProps, 'withToolbar' | 'additionalActions' | 'className'> & {
	/**
	 * Pass the export defining a story to render that story
	 *
	 * ```jsx
	 * import { Meta, Canvas } from '@storybook/addon-docs/blocks';
	 * import * as ButtonStories from './Button.stories';
	 *
	 * <Meta of={ButtonStories} />
	 * <Canvas of={ButtonStories.Primary} />
	 * ```
	 */
	of?: ModuleExport;
	/**
	 * Pass all exports of the CSF file if this MDX file is unattached
	 *
	 * ```jsx
	 * import { Canvas } from '@storybook/addon-docs/blocks';
	 * import * as ButtonStories from './Button.stories';
	 *
	 * <Canvas of={ButtonStories.Primary} meta={ButtonStories} />;
	 * ```
	 */
	meta?: ModuleExports;
	/**
	 * Specify the initial state of the source panel hidden: the source panel is hidden by default
	 * shown: the source panel is shown by default none: the source panel is not available and the
	 * button to show it is hidden
	 *
	 * @default 'hidden'
	 */
	sourceState?: 'hidden' | 'shown' | 'none';
	/**
	 * How to layout the story within the canvas padded: the story has padding within the canvas
	 * fullscreen: the story is rendered edge to edge within the canvas centered: the story is
	 * centered within the canvas
	 *
	 * @default 'padded'
	 */
	layout?: Layout;
	/** @see {SourceProps} */
	source?: Omit<SourceProps, 'dark'>;
	/** @see {StoryProps} */
	story?: Pick<StoryProps, 'inline' | 'height' | 'autoplay' | '__forceInitialArgs' | '__primary'>;
	title?: string; // I added this to pass it down to my custom component
};

export const Canvas: FC<CanvasProps> = (props) => {
	const docsContext = useContext(DocsContext);
	const sourceContext = useContext(SourceContext);
	const { title, of, source } = props;
	if ('of' in props && of === undefined) {
		throw new Error('Unexpected `of={undefined}`, did you mistype a CSF file reference?');
	}

	const { story } = useOf(of || 'story', ['story']);
	const sourceProps = useSourceProps({ ...source, ...(of && { of }) }, docsContext, sourceContext);

	const layout =
		props.layout ?? story.parameters.layout ?? story.parameters.docs?.canvas?.layout ?? 'padded';
	const withToolbar = props.withToolbar ?? story.parameters.docs?.canvas?.withToolbar ?? false;
	const additionalActions =
		props.additionalActions ?? story.parameters.docs?.canvas?.additionalActions;
	const sourceState = props.sourceState ?? story.parameters.docs?.canvas?.sourceState ?? 'hidden';
	const defaultClassName = props.className ?? story.parameters.docs?.canvas?.className;
	const storyClassNames = `story--${story.componentId} story--${story.id}`;
	const className = `${defaultClassName ?? ''} ${storyClassNames}`.trim();

	return (
		<Preview
			//withSource={sourceState === 'none' ? undefined : sourceProps} // Uncomment this to put the Code block back into the preview
			isExpanded={sourceState === 'shown'}
			withToolbar={withToolbar}
			additionalActions={additionalActions}
			className={className}
			layout={layout}
			title={title}
			of={of}
		>
			<Story of={of || story.moduleExport} meta={props.meta} {...props.story} />
		</Preview>
	);
};
