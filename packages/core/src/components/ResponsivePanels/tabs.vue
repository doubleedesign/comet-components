<script lang="ts">
import type { PanelItem } from './types.ts';

export default {
	name: 'Tabs',
	inheritAttrs: true,
	props: {
		panels: {
			type: Array as () => PanelItem[],
			required: true,
		}
	},
	data() {
		return {
			debouncedResize: null,
			// Initially open the first panel
			activePanelIndex: 0,
			// Overall height of the element, to be based on the highest panel
			height: 0,
			// Replace generic responsive-panel classes with tab-specific classes
			titles: this.panels.map((panel: PanelItem) => ({
				...panel.title,
				classes: panel.title.classes.map((className: string) => className.replace('responsive-panel__title', 'tabs__tab-list__item')),
			})),
			contents: this.panels.map((panel: PanelItem) => ({
				...panel.content,
				classes: panel.content.classes.map((className: string) => className.replace('responsive-panel__content', 'tabs__content__tab-panel')),
			})),
			// Get the colour theme to pass down in more specific ways
			colorTheme: this.$attrs['data-color-theme'] || 'primary',
		};
	},
	mounted() {
		document.addEventListener('selectionchange', this.watchForSelectedTextAndOpenPanel);

		// Calculate initial height without a wait
		this.height = this.recalculateHeight();
		// Store a reference to the debounced function rather than applying it directly, so it can be cleaned up later
		this.debouncedResize = this.debounce(() => this.height = this.recalculateHeight(), 200);
		// Recalculate on resize, after a short wait
		window.addEventListener('resize', this.debouncedResize);
	},
	beforeUnmount() {
		// Clean up event listeners
		document.removeEventListener('selectionchange', this.watchForSelectedTextAndOpenPanel);
		window.removeEventListener('resize', this.debouncedResize);
	},
	methods: {
		recalculateHeight() {
			// Get the height of the panels before any CSS to set the height of the overall element accordingly
			const panels = this.$el.querySelectorAll('[role="tabpanel"]');
			const maxHeight = Array.from(panels).reduce((max: number, panel: HTMLElement) => {
				const height = panel.scrollHeight;

				return height > max ? height : max;
			}, 0);

			return maxHeight;
		},
		// Open/close panels
		togglePanel(event: Event, index: number) {
			event.preventDefault();
			this.activePanelIndex = index;
		},
		// If text in a panel is selected such as via browser 'find in page', switch to that panel
		watchForSelectedTextAndOpenPanel(event: Event) {
			const selection = document.getSelection();
			// Get the element the selection is in, if any
			const tab = selection?.anchorNode?.parentElement.closest('[role="tabpanel"]');
			if (tab) {
				const index = Array.from(tab.parentElement.children).indexOf(tab);
				this.togglePanel(event, index);
			}
		},
		debounce(func: Function, delay: number) {
			let timerId;

			return function () {
				clearTimeout(timerId);
				timerId = setTimeout(func, delay);
			};
		},
	}
};
</script>

<template>
   <div class="tabs" :style="this.height ? { height: this.height + 'px' } : {}" role="tablist">
       <ul class="tabs__tab-list" role="tablist" :data-background="this.colorTheme">
            <li v-for="(title, index) in this.titles" :key="index" :class="title.classes" role="presentation">
                <!--TODO: Make direct page anchors work, and find a way to make it a relevant ID -->
                <a
                    role="tab"
                    :aria-selected="index === this.activePanelIndex"
                    :aria-controls="'tabpanel-' + index"
                    :href="'#tabpanel-' + index"
                    @click="this.togglePanel($event, index)"
                    v-html="title.content"
                ></a>
            </li>
       </ul>
       <div class="tabs__content" :data-color-theme="this.colorTheme">
            <div v-for="(content, index) in this.contents"
                 :key="index"
                 role="tabpanel"
                 :id="'tabpanel-' + index"
                 :class="content.classes"
                 :aria-labelledby="'tab-' + index"
                 :data-open="index === this.activePanelIndex"
                 v-html="content.content"
            ></div>
       </div>
   </div>
</template>

<style scoped>
</style>
