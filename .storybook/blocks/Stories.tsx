import type { FC, ReactElement } from 'react';
import React, { useContext } from 'react';
import { styled } from 'storybook/internal/theming';
import { DocsContext, Heading } from '@storybook/blocks';
import { DocsStory } from './DocsStory';

interface StoriesProps {
	title?: ReactElement | string;
	includePrimary?: boolean;
}

export const Stories: FC<StoriesProps> = ({ title = 'Stories', includePrimary = true }) => {
	const { componentStories, projectAnnotations, getStoryContext } = useContext(DocsContext);

	let stories = componentStories();
	const { stories: { filter } = { filter: undefined } } = projectAnnotations.parameters?.docs || {};
	if (filter) {
		stories = stories.filter((story) => filter(story, getStoryContext(story)));
	}
	// NOTE: this should be part of the default filter function. However, there is currently
	// no way to distinguish a Stories block in an autodocs page from Stories in an MDX file
	// making https://github.com/storybookjs/storybook/pull/26634 an unintentional breaking change.
	//
	// The new behavior here is that if NONE of the stories in the autodocs page are tagged
	// with 'autodocs', we show all stories. If ANY of the stories have autodocs then we use
	// the new behavior.
	const hasAutodocsTaggedStory = stories.some((story) => story.tags?.includes('autodocs'));
	if (hasAutodocsTaggedStory) {
		// Don't show stories where mount is used in docs.
		// As the play function is not running in docs, and when mount is used, the mounting is happening in play itself.
		stories = stories.filter((story) => story.tags?.includes('autodocs') && !story.usesMount);
	}

	if (!includePrimary) {
		stories = stories.slice(1);
	}

	if (!stories || stories.length === 0) {
		return null;
	}

	return (
		<>
			<h2 className="section-heading">{title}</h2>
			{stories.map(
				(story) =>
					story && <DocsStory key={story.id} of={story.moduleExport} expanded __forceInitialArgs />
			)}
		</>
	);
};
