import VueLoader from '../../plugins/vue-wrapper/src/vue-loader.js';
const Vue = await VueLoader;
import { loadModule } from  '../../plugins/vue-wrapper/src/vue3-sfc-loader.esm.js';
import { vueSfcLoaderOptions, BASE_PATH } from '../../plugins/vue-wrapper/src/index.js';

Vue.createApp({
	components: {
		SiteHeaderResponsive: Vue.defineAsyncComponent(() => {
			return loadModule(`${BASE_PATH}/src/components/SiteHeader/site-header-responsive.vue`, vueSfcLoaderOptions);
		}),
	}
}).mount('[data-vue-component="site-header__responsive"]');
