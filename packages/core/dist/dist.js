import '../src/plugins/tippy/popper.min.js';
import '../src/plugins/tippy/tippy.min.js';
import VueLoader from '../src/plugins/vue-wrapper/src/vue-loader.js';
import { loadModule } from '../src/plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { BASE_PATH, getVueSfcLoaderOptions } from '../src/plugins/vue-wrapper/src/index.js';
import '../vendor/feimosi/baguettebox.js/src/baguetteBox.js';

tippy('[class$="icon-links__item"][data-tippy-content]', { appendTo: 'parent' });

tippy('.label-with-tooltip [data-tippy-content]', {
	appendTo: 'parent'
});

const Vue$3 = await VueLoader;

// Run on initial page load (works on the front-end)
init$2();

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
		init$2(event.detail.targetDocument);
	}
	else {
		init$2();
	}
});

function init$2(targetDocument = document) {
	const tabs = targetDocument.querySelectorAll('[data-vue-component="tabs"]');
	if (tabs.length > 0) {
		// Add a unique attribute to use as the mount point
		// (can't use ID because that could be set by consumers)
		tabs.forEach((instance, index) => {
			Vue$3.createApp({
				components: {
					Tabs: Vue$3.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/tabs.vue`, getVueSfcLoaderOptions({ targetDocument }));
					}),
				}
			}).mount(instance);
		});
	}
}

const Vue$2 = await VueLoader;

// Run on initial page load (works on the front-end)
init$1();

/**
 * Run on event trigger
 * e.g., for ACF blocks in the WP block editor where code is set up to trigger it appropriately, and similar scenarios.
 *
 * Pass in a targetDocument in the event detail when the accordion is rendered in an iframe,
 * to ensure that the initialisation runs on the correct document.
 *
 * @param {CustomEvent|Event} event
 */
window.addEventListener('ReloadVueAccordions', (event) => {
	if (event?.detail?.targetDocument) {
		init$1(event.detail.targetDocument);
	}
	else {
		init$1();
	}
});

function init$1(targetDocument = document) {
	const accordions = targetDocument.querySelectorAll('[data-vue-component="accordion"]');
	if (accordions.length > 0) {
		accordions.forEach((instance, index) => {
			Vue$2.createApp({
				components: {
					Accordion: Vue$2.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/plugins/shared-vue-components/accordion.vue`, getVueSfcLoaderOptions({ targetDocument }));
					}),
				}
			}).mount(instance);
		});
	}
}

window.addEventListener('load', function () {
	if (!document.querySelector('[data-lightbox="true"]')) return;

	window.baguetteBox.run('[data-lightbox="true"]');
});

const Vue$1 = await VueLoader;

Vue$1.createApp({
	components: {
		SiteHeaderResponsive: Vue$1.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/SiteHeader/site-header-responsive.vue`, getVueSfcLoaderOptions());
		}),
	}
}).mount('[data-vue-component="site-header__responsive"]');

const Vue = await VueLoader;

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

function init(targetDocument = document) {
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
