import * as Vue from 'vue';
import { loadModule } from '../vue-wrapper/src/vue3-sfc-loader.esm.js';
import { BASE_PATH, getVueSfcLoaderOptions } from '../vue-wrapper/src';
import Vueform from '@vueform/vueform';
import vueformConfig from './vueform.config';

init();

export function init() {
	const instances = document.querySelectorAll('[data-vue-component="focal-point-picker"]');
	if (instances.length > 0) {
		instances.forEach((instance, index) => {
			instance.setAttribute('data-focal-point-picker-instance', `focal-point-picker-${index}`);

			const app = Vue.createApp({
				components: {
					FocalPointPicker: Vue.defineAsyncComponent(() => {
						return loadModule(`${BASE_PATH}/src/plugins/FocalPointPicker/focal-point-picker.vue`, {
							moduleCache: {
								vue: Vue
							},
							getFile: getVueSfcLoaderOptions().getFile,
							addStyle: getVueSfcLoaderOptions().addStyle,
							async handleModule(type, getContentData, path, options) {
								if (type === ".vue") {
									await options.addStyle(path);
								}
							}
						});
					}),
				}
			});

			app.use(Vueform, vueformConfig);
			app.mount(`[data-focal-point-picker-instance="focal-point-picker-${index}"]`);
		});
	}
}

