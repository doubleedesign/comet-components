import * as Vue from '../../plugins/vue-wrapper/src/vue.esm-browser.js';
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';

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
