<script lang="ts">
import type { PanelItem } from '../../components/ResponsivePanels/types.ts';

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
			}),
			// Track animation state to help prevent race conditions
			animating: false
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

			// Prevent subsequent click events while still animating from the first one
			if (this.animating) return;

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

			this.animating = true;

			// Set details.open before measuring height
			details.open = true;

			// Force a reflow to ensure the browser recognises the open state
			void content.offsetHeight;

			// Set initial height to 0
			content.style.height = '0px';
			content.style.overflow = 'hidden';
			content.style.display = 'block';

			// Ensure the browser has processed the height changes before animating
			requestAnimationFrame(() => {
				const height = content.scrollHeight;

				// Start the animation in the next frame
				requestAnimationFrame(() => {
					content.style.height = `${height}px`;

					const onTransitionEnd = () => {
						// Clean up styles after animation completes
						content.style.height = '';
						content.style.overflow = '';
						content.style.display = '';
						content.removeEventListener('transitionend', onTransitionEnd);
						this.animating = false;
					};

					content.addEventListener('transitionend', onTransitionEnd, { once: true });
				});
			});
		},
		animateClose(details: HTMLDetailsElement) {
			const content = details.querySelector('.accordion__panel__content') as HTMLElement;
			if(!content) return;

			this.animating = true;

			// Get current height and set it explicitly so CSS transitions will work
			const height = content.scrollHeight;
			content.style.height = `${height}px`;
			content.style.overflow = 'hidden';

			// Force a reflow to ensure the browser recognises the height change
			void content.offsetHeight;

			// Start the animation in the next frame to ensure the browser has processed the height changes
			requestAnimationFrame(() => {
				content.style.height = '0px';

				const onTransitionEnd = () => {
					content.style.height = '';
					content.style.overflow = '';
					details.open = false;
					content.removeEventListener('transitionend', onTransitionEnd);
					this.animating = false;
				};

				content.addEventListener('transitionend', onTransitionEnd, { once: true });
			});
		},
		// If text in a panel is selected such as via browser 'find in page', switch to that panel
		watchForSelectedTextAndOpenPanel(event: Event) {
			// Don't trigger if another animation is still in progress
			if (this.animating) return;

			const selection = document.getSelection();
			// Get the details element that the selection is in, if any
			const details = selection?.anchorNode?.parentElement?.closest('details');
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
                @click="(event: MouseEvent) => this.togglePanel(event)"
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
</style>
