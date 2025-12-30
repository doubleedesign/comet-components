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
window.addEventListener('ReloadVueResponsivePanels', (event) => {
	if (event?.detail?.targetDocument) {
		init(event.detail.targetDocument);
	}
	else {
		init();
	}
});

export function init(targetDocument = document) {
	const instances = targetDocument.querySelectorAll('[data-vue-component="responsive-panels"]');
	if (instances.length > 0) {
		instances.forEach((instance, index) => {
			Vue.createApp({
				components: {
					ResponsivePanels: Vue.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, getVueSfcLoaderOptions({ targetDocument }));
					}),
				}
			}).mount(instance);
		});
	}
}
