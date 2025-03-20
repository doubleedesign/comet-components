import Collapse from '../../../vendor/twbs/bootstrap/js/src/collapse.js';

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
