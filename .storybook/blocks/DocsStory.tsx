// @source https://github.com/storybookjs/storybook/blob/next/code/lib/blocks/src/blocks/DocsStory.tsx
import type { FC } from 'react';
import React from 'react';
import { Anchor, Description, Subheading, useOf, type DocsStoryProps } from '@storybook/blocks';
import { Canvas } from './Canvas.tsx';

export const DocsStory: FC<DocsStoryProps> = ({
	of,
	expanded = true,
	withToolbar: withToolbarProp = false,
	__forceInitialArgs = false,
	__primary = false,
}) => {
	const { story } = useOf(of || 'story', ['story']);
	const withToolbar = story.parameters.docs?.canvas?.withToolbar ?? withToolbarProp;

	return (
		<Anchor storyId={story.id}>
			{expanded && (
				<>
					<Subheading>{story.name}</Subheading>
					<Description of={of} />
				</>
			)}
			<Canvas
				of={of}
				title={story.title}
				withToolbar={withToolbar}
				story={{
					__forceInitialArgs,
					__primary,
				}}
				source={{ __forceInitialArgs }}
			/>
		</Anchor>
	);
};
