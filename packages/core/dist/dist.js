import '../src/plugins/tippy/popper.min.js';
import '../src/plugins/tippy/tippy.min.js';
import VueLoader from '../src/plugins/vue-wrapper/src/vue-loader.js';
import { loadModule } from '../src/plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { BASE_PATH, vueSfcLoaderOptions } from '../src/plugins/vue-wrapper/src/index.js';
import '../../../feimosi/baguettebox.js/src/baguetteBox.js';

tippy('[data-tippy-content]');

const Vue$3 = await VueLoader;

const tabs = document.querySelectorAll('[data-vue-component="tabs"]');
if (tabs.length > 0) {
	// Add a unique attribute to use as the mount point
	// (can't use ID because that could be set by consumers)
	tabs.forEach((tab, index) => {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set to a custom value by consumers)
		tab.setAttribute('data-tabs-instance', `tabs-${index}`);

		Vue$3.createApp({
			components: {
				Tabs: Vue$3.defineAsyncComponent(() => {
					return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/tabs.vue`, vueSfcLoaderOptions);
				}),
			}
		}).mount(`[data-tabs-instance="tabs-${index}"]`);
	});
}

const Vue$2 = await VueLoader;

const accordions = document.querySelectorAll('[data-vue-component="accordion"]');
if (accordions.length > 0) {
	accordions.forEach((accordion, index) => {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set to a custom value by consumers)
		accordion.setAttribute('data-accordion-instance', `accordion-${index}`);

		Vue$2.createApp({
			components: {
				Accordion: Vue$2.defineAsyncComponent(() => {
					return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/accordion.vue`, vueSfcLoaderOptions);
				}),
			}
		}).mount(`[data-accordion-instance="accordion-${index}"]`);
	});
}

window.addEventListener('load', function() {
	if(!document.querySelector('.gallery')) return;

	window.baguetteBox.run('.gallery');
});

const Vue$1 = await VueLoader;

Vue$1.createApp({
	components: {
		SiteHeaderResponsive: Vue$1.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/SiteHeader/site-header-responsive.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="site-header__responsive"]');

const Vue = await VueLoader;

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
