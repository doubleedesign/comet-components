import { addons } from '@storybook/preview-api';
import events, { STORY_RENDER_PHASE_CHANGED, STORY_FINISHED, STORY_ARGS_UPDATED } from '@storybook/core-events';
export const withServerPageStates = (StoryFn, context) => {
	const channel = addons.getChannel();

	//For debugging - log all events that are available here
	//Logging from preview.tsx shows events that this function can't see
	// Object.values(events).forEach((event) => {
	// 	channel.on(event, (data) => {
	// 		console.log('on', event, data);
	// 		debugger;
	// 	});
	// });

	channel.on(STORY_RENDER_PHASE_CHANGED, (data) => {
		if(context.viewMode !== 'docs') return;

		// Add loader div to all stories as early as possible
		// Annoyingly, the first event received by this decorator is STORY_RENDER_PHASE_CHANGED with the newPhase 'completed'
		if(data?.newPhase === 'completed') {
			addLoaders();
			displayLoaders();
		}

		// Display loaders if the story is reloading
		if(data?.newPhase === 'loading' || data?.newPhase === 'rendering') {
			displayLoaders();
		}
	});

	channel.on(STORY_FINISHED, (data) => {
		if(data.status === 'success') {
			hideLoaders();
		}
		else {
			console.error(`withServerPageStates decorator - story finished event with error: ${data.error}`);
		}
	});

	channel.on(STORY_ARGS_UPDATED, (data) => {
		// Chuck the updated args into local storage so the custom ResponsiveContainer component can pick them up
		localStorage.setItem('storyArgs', JSON.stringify(data.args));
	});

	return StoryFn();
};

function addLoaders() {
	const allDocsStoryCssSelector = '.docs-story';
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

function displayLoaders() {
	const allDocsStoryCssSelector = '.docs-story';
	const storyPreviews = document.querySelectorAll(allDocsStoryCssSelector);
	if(storyPreviews) {
		storyPreviews.forEach((storyPreview) => {
			const parent = storyPreview.closest('.sbdocs-preview');
			// Bail if the loader is not present
			if(!parent || !parent.querySelector('.sb-loader')) return;

			const loader = parent.querySelector('.sb-loader');
			// @ts-expect-error TS2339: Property style does not exist on type Element
			loader.style.display = 'block';
		});
	}
}

function hideLoaders() {
	const allDocsStoryCssSelector = '.docs-story';
	const storyPreviews = document.querySelectorAll(allDocsStoryCssSelector);
	// Using div qualifier because we don't want this to capture the injected <style> which has the same attribute
	const isStoryForVueComponent = document.querySelector('div[data-vue-component]');

	if(storyPreviews) {
		storyPreviews.forEach(async (storyPreview) => {
			const parent = storyPreview.closest('.sbdocs-preview');
			// Bail if the loader is not present
			if(!parent || !parent.querySelector('.sb-loader')) return;

			// Vue components take a little longer to load
			if(isStoryForVueComponent) {
				await waitForContent(isStoryForVueComponent);
				const loader = parent.querySelector('.sb-loader');
				// @ts-expect-error TS2339: Property style does not exist on type Element
				loader.style.display = 'none';
			}

			// Otherwise, remove the loader straight away
			const loader = parent.querySelector('.sb-loader');
			// @ts-expect-error TS2339: Property style does not exist on type Element
			loader.style.display = 'none';
		});
	}
}

function waitForContent(selector, maxAttempts = 30, interval = 500) {
	return new Promise((resolve, reject) => {
		let attempts = 0;

		const checkContent = () => {
			const content = selector.querySelector('div');

			if (content) {
				resolve(content);

				return;
			}

			attempts++;
			if (attempts >= maxAttempts) {
				reject(new Error('Timeout waiting for Vue component content to load'));

				return;
			}

			setTimeout(checkContent, interval);
		};

		checkContent();
	});
}
