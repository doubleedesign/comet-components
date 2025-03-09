import Tab from '../vendor/twbs/bootstrap/js/src/tab.js';
import '../vendor/feimosi/baguettebox.js/src/baguetteBox.js';
import * as Vue from '../vendor/doubleedesign/comet-vue-wrapper/src/vue.esm-browser.js';
import '../vendor/doubleedesign/comet-vue-wrapper/src/vue3-sfc-loader.esm.js';

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

window.addEventListener('load', function() {
	window.baguetteBox.run('.gallery');
});

const { loadModule } = window['vue3-sfc-loader'];

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

Vue.createApp({
	components: {
		SiteNavigation: Vue.defineAsyncComponent(() => loadModule('./site-header.vue', vueSfcLoaderOptions)),
	},
	template: '',
	compilerOptions: {},
}).mount('[data-vue-component="site-header__responsive"]');
