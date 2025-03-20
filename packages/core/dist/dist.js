import Tab from '../vendor/twbs/bootstrap/js/src/tab.js';
import Collapse from '../vendor/twbs/bootstrap/js/src/collapse.js';
import '../vendor/feimosi/baguettebox.js/src/baguetteBox.js';
import * as Vue from '../vendor/doubleedesign/comet-vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from '../vendor/doubleedesign/comet-vue-wrapper/src/vue3-sfc-loader.esm.js';

const triggerTabList = document.querySelectorAll('[role="tab"]');

// On page load, show the first tab
if (triggerTabList.length) {
	const firstTab = new Tab(triggerTabList[0]);
	firstTab.show();
}

triggerTabList.forEach(triggerEl => {
	const tabTrigger = new Tab(triggerEl);

	triggerEl.addEventListener('click', event => {
		event.preventDefault();
		tabTrigger.show();
	});
});

const panels = document.querySelectorAll('.accordion__panel');

panels.forEach((details, index) => {
	// Get the summary and content elements
	const summary = details.querySelector('.accordion__panel__title');
	const content = details.querySelector('.accordion__panel__content');

	if (summary && content) {
		// Generate a unique ID for the collapse
		const collapseId = `accordion-collapse-${index}`;
		content.id = collapseId;

		// Add collapse class to content
		content.classList.add('collapse');

		// Initialize Bootstrap collapse on the content
		const collapseInstance = new Collapse(content, {
			toggle: details.hasAttribute('open'), // Initialize open state based on 'open' attribute
			parent: details.closest('.accordion') // For accordion behavior. Turning this off allows multiple open at a time. // TODO: Make this configurable
		});

		// Prevent native details toggling behavior
		details.addEventListener('click', (event) => {
			event.preventDefault();
		});

		// Set up summary as trigger
		summary.setAttribute('role', 'button');
		summary.setAttribute('aria-expanded', details.hasAttribute('open') ? 'true' : 'false');
		summary.setAttribute('aria-controls', collapseId);

		// Add click handler to summary
		summary.addEventListener('click', (event) => {
			event.preventDefault(); // Prevent default details toggling
			collapseInstance.toggle(); // Use Bootstrap's toggle instead

			// Update the open attribute to match collapse state
			if (content.classList.contains('show')) {
				details.removeAttribute('open');
				summary.setAttribute('aria-expanded', 'false');
			}
			else {
				details.setAttribute('open', '');
				summary.setAttribute('aria-expanded', 'true');
			}
		});

		// Listen for Bootstrap events to update details state
		content.addEventListener('shown.bs.collapse', () => {
			details.setAttribute('open', '');
			summary.setAttribute('aria-expanded', 'true');
		});

		content.addEventListener('hidden.bs.collapse', () => {
			details.removeAttribute('open');
			summary.setAttribute('aria-expanded', 'false');
		});
	}
});

window.addEventListener('load', function() {
	window.baguetteBox.run('.gallery');
});

const vueSfcLoaderOptions = {
	moduleCache: { vue: Vue },
	getFile: async(url) => {
		const res = await fetch(url);
		if (!res.ok) {
			throw Object.assign(new Error(res.statusText + ' ' + url), { res });
		}

		return {
			getContentData: () => {
				return res.text().then((content) => {
					// Filter out the <style> tags from the component as they need to be processed separately
					const dom = new DOMParser().parseFromString(content, 'text/html');

					return Array.from(dom.head.children)
						.filter((element) => element.tagName !== 'STYLE')
						.map((element) => element.outerHTML)
						.join('\n');
				});
			},
		};
	},
	addStyle: async(fileUrl) => {
		const res = await fetch(fileUrl);
		const dom = new DOMParser().parseFromString(await res.text(), 'text/html');
		const css = Array.from(dom.head.children).find((element) => element.tagName === 'STYLE');
		if (css?.textContent) {
			const style = document.createElement('style');
			style.setAttribute('data-vue-component', fileUrl.split('/').pop());
			style.type = 'text/css';
			style.textContent = css.textContent;
			document.body.appendChild(style);
		}
	},
	async handleModule(type, getContentData, path, options) {
		if (type === '.vue') {
			await options.addStyle(path);
		}
	},
};

const BASE_PATH = (function() {
	// NOTE: If we are loading from an implementation, the <script> tag for dist.js needs to have the data-base-path attribute set to
	// the path to the Core package, e.g./wp-content/plugins/comet-plugin/vendor/doubleedesign/comet-components-core
	// The below finds it there.
	const scripts = document.getElementsByTagName('script');
	for (let i = 0; i < scripts.length; i++) {
		if (scripts[i].hasAttribute('data-base-path')) {
			return scripts[i].getAttribute('data-base-path');
		}
	}

	// TODO Fix for other use cases like Storybook
	return '';
})();

Vue.createApp({
	components: {
		SiteHeaderResponsive: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/SiteHeader/site-header-responsive.vue`, vueSfcLoaderOptions);
		}),
	},
	template: '',
	compilerOptions: {},
}).mount('[data-vue-component="site-header__responsive"]');
