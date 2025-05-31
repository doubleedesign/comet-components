import '../src/plugins/tippy/popper.min.js';
import '../src/plugins/tippy/tippy.min.js';
import VueLoader from '../src/plugins/vue-wrapper/src/vue-loader.js';
import { loadModule } from '../src/plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { BASE_PATH, vueSfcLoaderOptions } from '../src/plugins/vue-wrapper/src/index.js';
import '../../../feimosi/baguettebox.js/src/baguetteBox.js';

tippy('[data-tippy-content]');

const Vue$3 = await VueLoader;

// Run on initial page load (works on the front-end)
init$2();

// Run on event trigger (makes it work for ACF blocks in the WP block editor where code is set up to trigger it appropriately)
window.addEventListener('ReloadVueTabs', (e) => {
	init$2();
});

function init$2() {
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
}

const Vue$2 = await VueLoader;

// Run on initial page load (works on the front-end)
init$1();

// Run on event trigger (makes it work for ACF blocks in the WP block editor where code is set up to trigger it appropriately)
window.addEventListener('ReloadVueAccordions', (e) => {
	init$1();
});

function init$1() {
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

// Run on initial page load (works on the front-end)
init();

// Run on event trigger (makes it work for ACF blocks in the WP block editor where code is set up to trigger it appropriately)
window.addEventListener('ReloadVueResponsivePanels', (e) => {
	init();
});

function init() {
	const instances = document.querySelectorAll('[data-vue-component="responsive-panels"]');
	if (instances.length > 0) {
		instances.forEach((instance, index) => {
			// Add a unique attribute to use as the mount point
			// (can't use ID because that could be set to a custom value by consumers)
			instance.setAttribute('data-responsive-panels-instance', `responsive-panels-${index}`);

			Vue.createApp({
				components: {
					ResponsivePanels: Vue.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/components/ResponsivePanels/responsive-panels.vue`, vueSfcLoaderOptions);
					}),
				}
			}).mount(`[data-responsive-panels-instance="responsive-panels-${index}"]`);
		});
	}
}
