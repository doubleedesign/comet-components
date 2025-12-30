import VueLoader from '../../plugins/vue-wrapper/src/vue-loader.js';

const Vue = await VueLoader;
import { loadModule } from '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { getVueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

// Run on initial page load (works on the front-end)
init();

/**
 * Run on event trigger
 * e.g., for ACF blocks in the WP block editor where code is set up to trigger it appropriately, and similar scenarios.
 *
 * Pass in a targetDocument in the event detail when the accordion is rendered in an iframe,
 * to ensure that the initialisation runs on the correct document.
 *
 * @param {CustomEvent|Event} event
 */
window.addEventListener('ReloadVueTabs', (event) => {
	if (event?.detail?.targetDocument) {
		init(event.detail.targetDocument);
	}
	else {
		init();
	}
});

export function init(targetDocument = document) {
	const tabs = targetDocument.querySelectorAll('[data-vue-component="tabs"]');
	if (tabs.length > 0) {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set by consumers)
		tabs.forEach((instance, index) => {
			Vue.createApp({
				components: {
					Tabs: Vue.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/tabs.vue`, getVueSfcLoaderOptions({ targetDocument }));
					}),
				}
			}).mount(instance);
		});
	}
}
