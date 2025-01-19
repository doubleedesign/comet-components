/* global wp */

/**
 * Customisations for the block editor interface broadly
 * (not individual blocks)
 */
document.addEventListener('DOMContentLoaded', async function() {
	// Open list view by default
	wp.domReady(() => {
		const { select, dispatch } = wp.data;
		const listViewIsOpen = select('core/editor').isListViewOpened();

		if (!listViewIsOpen) {
			dispatch('core/editor').setIsListViewOpened(true);
		}
	});

	// When block inspector is opened
	wp.data.subscribe(() => {
		const { select } = wp.data;
		const { getSelectedBlock } = select('core/editor');

		if (getSelectedBlock()) {
			hideBlockOptionToggleByLabelText('Stack on mobile');
			hideBlockOptionToggleByLabelText('Allow to wrap to multiple lines');
		}
	});
});

function hideBlockOptionToggleByLabelText(labelText) {
	const toggles = document.getElementsByClassName('components-toggle-control');
	Object.values(toggles).forEach((toggle) => {
		if (toggle.querySelector('.components-toggle-control__label').textContent.trim() === labelText) {
			toggle.style.display = 'none';
		}
	});
}

// Adapted from https://codepen.io/boosmoke/pen/abbMZzb
function waitForElementToExist(selector, limit) {
	return new Promise((resolve, reject) => {
		let count = 0;
		(function waitForFoo() {
			const element = document.querySelector(selector);
			if (element) {
				return resolve(element);
			}
			if (limit && count > limit) {
				//reject(new Error('Element not found'));
				return false;
			}
			count += 1;
			setTimeout(waitForFoo, 50);
		}());
	});
}

function arrayToHtmlCollection(array) {
	const fragment = document.createDocumentFragment();
	array.forEach((child) => {
		fragment.appendChild(child);
	});

	return fragment;
}
