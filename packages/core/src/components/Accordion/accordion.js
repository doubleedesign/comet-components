import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

const accordions = document.querySelectorAll('[data-vue-component="accordion"]');
if (accordions.length > 0) {
	accordions.forEach((accordion, index) => {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set to a custom value by consumers)
		accordion.setAttribute('data-accordion-instance', `accordion-${index}`);

		Vue.createApp({
			components: {
				Accordion: Vue.defineAsyncComponent(() => {
					return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/accordion.vue`, vueSfcLoaderOptions);
				}),
			}
		}).mount(`[data-accordion-instance="accordion-${index}"]`);
	});
}

