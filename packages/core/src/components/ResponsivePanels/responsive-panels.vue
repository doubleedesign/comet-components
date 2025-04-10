<script lang="ts">
import Accordion from '../../plugins/shared-vue-components/accordion.vue';
import Tabs from '../../plugins/shared-vue-components/tabs.vue';
import type { PanelItem } from './types.ts';

export default {
	name: 'ResponsivePanels',
	components: { Accordion, Tabs },
	inheritAttrs: true,
	props: {
		titles: {
			type: Array as () => PanelItem[],
			required: true,
		},
		contents: {
			type: Array as () => PanelItem[],
			required: true,
		},
		breakpoint: String
	},
	data() {
		return {
			panels: this.titles.map((title: string, index: number) => {
				return {
					title: title,
					content: this.contents[index],
				};
			}),
			showAsTabs: false, // to be swapped when breakpoint is reached
			debouncedResize: null
		};
	},
	mounted() {
		// Check available space and determine which component to show on initial load, without a wait
		this.checkAvailableSpaceAndMaybeSwitch();
		// Store a reference to the debounced function rather than applying it directly, so it can be cleaned up later
		this.debouncedResize = this.debounce(this.checkAvailableSpaceAndMaybeSwitch, 200);
		// Recalculate on resize, after a short wait
		window.addEventListener('resize', this.debouncedResize);
	},
	beforeUnmount() {
		// Remove the exact same function reference
		window.removeEventListener('resize', this.debouncedResize);
	},
	methods: {
		checkAvailableSpaceAndMaybeSwitch() {
			const container = this.$el;
			const containerWidth = container ? container.clientWidth : 0;
			const breakpointNumber = this.breakpoint.endsWith('px')
				? parseInt(this.breakpoint.replace('px', ''))
				: parseInt(this.breakpoint.replace('rem', '')) * 16;

			this.showAsTabs = containerWidth >= breakpointNumber;
		},
		debounce(func: Function, delay: number) {
			let timerId;

			return function () {
				clearTimeout(timerId);
				timerId = setTimeout(func, delay);
			};
		},
	}

	// TODO: The inner Accordion and Tabs components should be refactored out into their own Vue components
	// and used for the standalone accordion and tabs too, so they share all styling in a cleaner way
	// - this currently relies on those components' styling which is...not consistent with the rest of the project's architecture
};
</script>

<template>
    <Transition name="panel-switch" mode="out-in">
        <Tabs v-if="this.showAsTabs" :panels="this.panels"></Tabs>
        <Accordion v-else :panels="this.panels"></Accordion>
    </Transition>
</template>

<style scoped>
.panel-switch-enter-active,
.panel-switch-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.panel-switch-enter-from,
.panel-switch-leave-to {
    opacity: 0;
}

.panel-switch-enter-from {
    transform: translateY(1rem);
}

.panel-switch-leave-to {
    transform: translateY(-1rem);
}
</style>
