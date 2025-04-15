import * as Vue from '../src/plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from '../src/plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { BASE_PATH, vueSfcLoaderOptions } from '../src/plugins/vue-wrapper/src/index.js';
import '../../../feimosi/baguettebox.js/src/baguetteBox.js';

Vue.createApp({
	components: {
		Tabs: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/tabs.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="tabs"]');

Vue.createApp({
	components: {
		Accordion: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/accordion.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="accordion"]');

window.addEventListener('load', function() {
	if(!document.querySelector('.gallery')) return;

	window.baguetteBox.run('.gallery');
});

Vue.createApp({
	components: {
		SiteHeaderResponsive: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/SiteHeader/site-header-responsive.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="site-header__responsive"]');

Vue.createApp({
	components: {
		ResponsivePanels: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="responsive-panels"]');
