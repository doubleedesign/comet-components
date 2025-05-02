import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

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
