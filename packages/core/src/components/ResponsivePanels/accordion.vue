<script lang="ts">
import type { PanelItem } from './types.ts';

export default {
	name: 'Accordion',
	inheritAttrs: true,
	props: {
		panels: {
			type: Array as () => PanelItem[],
			required: true,
		},
	},
	data() {
		return {
			// Replace generic responsive-panel classes with accordion-specific classes
			transformedPanels: this.panels.map((panel: PanelItem) => {
				return {
					title: {
						...panel.title,
						classes: panel.title.classes.map((className: string) => className.replace('responsive-panel', 'accordion__panel'))
					},
					content: {
						...panel.content,
						classes: panel.content.classes.map((className: string) => className.replace('responsive-panel', 'accordion__panel'))
					}
				};
			})
		};
	},
	mounted() {
		document.addEventListener('selectionchange', this.watchForSelectedTextAndOpenPanel);
	},
	beforeUnmount() {
		// Clean up event listeners
		document.removeEventListener('selectionchange', this.watchForSelectedTextAndOpenPanel);
	},
	methods: {
		togglePanel(event: Event) {
			event.preventDefault();
			const summary = event.currentTarget as HTMLElement;
			const details = summary.parentElement as HTMLDetailsElement;
			const isOpen = details.open;
			if(isOpen) {
				this.animateClose(details);
			}
			else {
				this.animateOpen(details);
			}
		},
		animateOpen(details: HTMLDetailsElement) {
			const content = details.querySelector('.accordion__panel__content') as HTMLElement;
			if(!content) return;

			// Initial closed state
			content.style.height = '0px';
			content.style.overflow = 'hidden';

			// Get the scroll height and animate to it
			requestAnimationFrame(() => {
				const height = content.scrollHeight;
				content.style.height = `${height}px`;

				content.addEventListener('transitionend', function removeHeight() {
					// Remove inline CSS after transition completes
					content.style.height = '';
					content.style.overflow = '';
					content.removeEventListener('transitionend', removeHeight);
				});

				// Add the open attribute to the details element for proper semantics
				// we do this last to ensure it animates - CSS transitions don't work on the open attribute itself
				details.open = true;
			});
		},
		animateClose(details: HTMLDetailsElement) {
			const content = details.querySelector('.accordion__panel__content') as HTMLElement;
			if(!content) return;

			// Set fixed height so CSS transitions work
			const height = content.scrollHeight;
			content.style.height = `${height}px`;
			content.style.overflow = 'hidden';

			// Force a reflow by accessing the current offsetHeight (but don't assign it to a variable because it won't be used)
			content.offsetHeight;

			// Animate to 0 height
			requestAnimationFrame(() => {
				content.style.height = '0px';

				content.addEventListener('transitionend', function closeDetails() {
					// Remove inline CSS after the transition completes
					content.style.height = '';
					content.style.overflow = '';
					content.removeEventListener('transitionend', closeDetails);

					// remove the open attribute from the details element for proper semantics
					// we do this inside the event listener to ensure it animates - outside it will just close the panel instantly
					details.open = false;
				});
			});
		},
		// If text in a panel is selected such as via browser 'find in page', switch to that panel
		watchForSelectedTextAndOpenPanel(event: Event) {
			const selection = document.getSelection();
			// Get the details element that the selection is in, if any
			const details = selection?.anchorNode?.parentElement.closest('details');
			// Open it if it's closed
			if (details && !details.open) {
				this.animateOpen(details);
			}
		}
	}
};
</script>

<template>
    <div class="accordion" role="group">
        <details
            class="accordion__panel"
            v-for="(panel, index) in this.transformedPanels"
            :key="index"
        >
            <summary
                :class="panel.title.classes"
                v-bind="panel.title.attributes"
                :aria-controls="panel.content.attributes.id"
                @click="(event: MouseEvent) => this.togglePanel(event, index)"
                v-html="panel.title.content"
            >
            </summary>
            <div
                :class="panel.content.classes"
                v-bind="panel.content.attributes"
                v-html="panel.content.content"
            ></div>
        </details>
    </div>
</template>

<style>
.accordion__panel__content {
    transition: height 0.3s ease-in-out;
}
</style>
