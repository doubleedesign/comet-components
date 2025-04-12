import { addons } from '@storybook/preview-api';
import events, { DOCS_RENDERED, STORY_RENDER_PHASE_CHANGED } from '@storybook/core-events';

function addLoaders() {
	const allDocsStoryCssSelector = '.sbdocs-preview .docs-story';
	const storyPreviews = document.querySelectorAll(allDocsStoryCssSelector);
	if(storyPreviews) {
		storyPreviews.forEach((storyPreview) => {
			const parent = storyPreview.closest('.sbdocs-preview');
			// Bail if the loader is already present
			if(parent && parent.querySelector('.sb-loader')) return;

			const previewLoader = document.createElement('div');
			storyPreview.insertAdjacentElement('afterend', previewLoader);
			previewLoader.classList.add('sb-loader');
			previewLoader.style.display = 'none';
		});
	}
	else {
		console.warn(`withServerPageStates decorator could not find the story selector: ${allDocsStoryCssSelector}`);
	}
}

export const withServerPageStates = (StoryFn, context) => {
	const channel = addons.getChannel();

	// Add loader div to all stories as early as possible
	channel.on(DOCS_RENDERED, () => {
		if(context.viewMode !== 'docs') return;

		addLoaders();
	});

	channel.on(STORY_RENDER_PHASE_CHANGED, ({ newPhase, storyId }) => {
		if(context.viewMode !== 'docs') return;

		// sometimes DOCS_RENDERED won't be emitted before this, so add loaders now if they don't already exist
		if(newPhase === 'loading' || newPhase === 'rendering') {
			addLoaders();
		}

		// Note: Custom .story--${storyId} classes are added in the component override for the Canvas block
		// @see ./.storybook/blocks/Canvas.tsx
		const storyCssSelector = `.sbdocs-preview.story--${storyId} .docs-story`;
		const storyPreview = document.querySelector(storyCssSelector);
		if(!storyPreview) return;

		const loader = storyPreview.closest('.sbdocs-preview').querySelector('.sb-loader');

		if(storyId && storyPreview && loader) {
			if (newPhase === 'loading' || newPhase === 'rendering') {
				storyPreview.classList.add('sbdocs-preview--story-loading');
				// @ts-expect-error TS2339: Property style does not exist on type Element
				loader.style.display = 'block';
			}
			if (newPhase === 'completed') {
				storyPreview.classList.remove('sbdocs-preview--story-loading');
				// @ts-expect-error TS2339: Property style does not exist on type Element
				loader.style.display = 'none';
			}
		}
		else if(storyId) {
			console.warn(`withServerPageStates decorator could not find the story selector: ${storyCssSelector}`);
		}
	});

	return StoryFn();
};
