import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

Vue.createApp({
	components: {
		ResponsivePanels: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="responsive-panels"]');
