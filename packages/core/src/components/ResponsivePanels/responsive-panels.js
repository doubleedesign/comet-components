import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

const panels = document.querySelectorAll('[data-vue-component="responsive-panels"]');
if (panels.length > 0) {
	panels.forEach((panel, index) => {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set to a custom value by consumers)
		panel.setAttribute('data-responsive-panels-instance', `responsive-panels-${index}`);

		Vue.createApp({
			components: {
				ResponsivePanels: Vue.defineAsyncComponent(() => {
					return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, vueSfcLoaderOptions);
				}),
			}
		}).mount(`[data-responsive-panels-instance="responsive-panels-${index}"]`);
	});
}

