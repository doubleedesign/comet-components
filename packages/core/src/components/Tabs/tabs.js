import VueLoader from '../../plugins/vue-wrapper/src/vue-loader.js';
const Vue = await VueLoader;
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

// Run on initial page load (works on the front-end)
init();

// Run on event trigger (makes it work for ACF blocks in the WP block editor where code is set up to trigger it appropriately)
window.addEventListener('ReloadVueTabs', (e) => {
	init();
});

export function init() {
	const tabs = document.querySelectorAll('[data-vue-component="tabs"]');
	if (tabs.length > 0) {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set by consumers)
		tabs.forEach((tab, index) => {
			// Add a unique attribute to use as the mount point
			// (can't use ID because that could be set to a custom value by consumers)
			tab.setAttribute('data-tabs-instance', `tabs-${index}`);

			Vue.createApp({
				components: {
					Tabs: Vue.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/tabs.vue`, vueSfcLoaderOptions);
					}),
				}
			}).mount(`[data-tabs-instance="tabs-${index}"]`);
		});
	}
}
