/* global acf */
import { QuickFormHandler } from './quick-form.js';

document.addEventListener('DOMContentLoaded', function () {
	// Target the admin post list for Events only
	if (!document.body.classList.contains('post-type-event') && !document.body.classList.contains('edit-php')) {
		return;
	}

	const quickAddBox = document.querySelector('.admin-quick-add');
	if (!quickAddBox) {
		return;
	}

	const quickAddToggle = quickAddBox.querySelector('.postbox-header button');
	const quickAddForm = quickAddBox.querySelector('#acf-form-quick-add');

	if (quickAddForm.tagName !== 'FORM') {
		console.error('Quick Add form not found or invalid.');

		return;
	}

	new QuickAddFormHandler(quickAddForm, quickAddToggle);
});

class QuickAddFormHandler extends QuickFormHandler {
	constructor(form, toggle) {
		super(form, toggle);
	}

	handleSubmission(event) {
		super.visuallyDim();
		super.handleSubmission(event, 'custom_acf_quick_add_form');
	}

	processResponse(response) {
		return super.processResponse(response);
	}

	handleResponse(response) {
		super.handleResponse(response);

		// Refresh the page with the new post ID in the URL, which the PHP will use to insert an admin confirmation message
		if (response?.data?.post_id) {
			this.redirect(response.data.post_id);
		}
		// Otherwise, just redirect anyway, the post has probably been saved at this point
		// and it's just something in the custom JS that's janky
		else {
			this.redirect();
		}
	}

	redirect(postId) {
		const url = new URL(window.location.href);
		url.searchParams.set('added', postId);

		// Stop the "unsaved changes" browser warning before redirecting
		window.onbeforeunload = null;
		if (typeof acf !== 'undefined' && acf.unload) {
			acf.unload.stopListening();
		}

		window.location.href = url.toString();
	}

	reset() {
		super.reset();
	}
}
