import { defineClientConfig } from '@vuepress/client';
import Layout from './layouts/Layout.vue';

export default defineClientConfig({
	layouts: {
		Layout,
	},
	enhance({ app, router }) {
		// Variables to track scroll spy state
		let scrollSpyActive = false;
		let setupTimeout = null;

		router.afterEach(() => {
			// Clear any existing timeout to prevent multiple setups
			if (setupTimeout) {
				clearTimeout(setupTimeout);
			}

			// Remove existing scroll listeners before setting up new ones
			if (scrollSpyActive) {
				window.removeEventListener('scroll', handleScroll);
				scrollSpyActive = false;
			}

			// Wait for DOM to update and animations to complete
			setupTimeout = setTimeout(() => {
				setupScrollSpy();
				setupTimeout = null;
			}, 600);
		});
	},
});

function setupScrollSpy() {
	// Find all heading level 2 and 3 elements
	const hElements = Array.from(document.querySelectorAll('h2[id], h3[id]'));

	if (hElements.length === 0) return;

	// Get the heading elements with their IDs
	const headingElements = hElements.map(el => ({
		id: el.id,
		element: el,
		offset: el.getBoundingClientRect().top + window.pageYOffset
	}));

	// Allow space for the fixed header and announcement banner
	const headerOffset = 200;

	// Track the last active heading to prevent unnecessary updates
	let lastActiveHeadingId = null;
	let isThrottled = false;
	let scrollTimeout = null;

	// Define the scroll handler outside to be able to remove it properly
	function handleScroll() {
		// Clear any pending timeout
		if (scrollTimeout) {
			clearTimeout(scrollTimeout);
		}

		// Set a new timeout to debounce rapid scroll events
		scrollTimeout = setTimeout(() => {
			if (!isThrottled) {
				isThrottled = true;

				window.requestAnimationFrame(() => {
					updateUrlOnScroll();
					isThrottled = false;
				});
			}
		}, 100);
	}

	function updateUrlOnScroll() {
		// Recompute offsets as they might have changed
		headingElements.forEach(heading => {
			heading.offset = heading.element.getBoundingClientRect().top + window.pageYOffset;
		});

		// Get current scroll position
		const scrollPosition = window.scrollY + headerOffset;

		// Find the heading that's currently in view
		// Start from the bottom to prioritize the heading that's closest to the top of the viewport
		let activeHeadingId = null;

		for (let i = headingElements.length - 1; i >= 0; i--) {
			const { id, offset } = headingElements[i];

			if (offset <= scrollPosition) {
				activeHeadingId = id;
				break;
			}
		}

		// Only update if the active heading has changed
		if (activeHeadingId !== lastActiveHeadingId) {
			lastActiveHeadingId = activeHeadingId;

			if (activeHeadingId) {
				// Only update URL if different from current hash to prevent flashing
				if (decodeURIComponent(window.location.hash) !== `#${activeHeadingId}`) {
					// Update URL without causing a jump
					history.replaceState(null, null, `#${activeHeadingId}`);

					// Dispatch event for components that need to react to hash changes
					window.dispatchEvent(new CustomEvent('urlHashChanged', {
						detail: { hash: `#${activeHeadingId}` }
					}));
				}
			}
			else if (window.location.hash) {
				// If no heading is in view, clear the hash
				history.replaceState(null, null, window.location.pathname + window.location.search);
				window.dispatchEvent(new CustomEvent('urlHashChanged', {
					detail: { hash: '' }
				}));
			}
		}
	}

	// Add the scroll event listener
	window.addEventListener('scroll', handleScroll);

	// Initialize on page load, but give time for the page to settle
	setTimeout(updateUrlOnScroll, 300);
}
