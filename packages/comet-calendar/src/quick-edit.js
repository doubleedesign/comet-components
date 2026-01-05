/* global acf */
import { QuickFormHandler } from './quick-form.js';

document.addEventListener('DOMContentLoaded', function () {
	// Target the admin post list for Events only
	if (!document.body.classList.contains('post-type-event') && !document.body.classList.contains('edit-php')) {
		return;
	}

	const forms = document.querySelectorAll('.admin-column-acf-form .acf-form');
	forms?.forEach(form => {
		const formId = form.getAttribute('id');
		const toggle = document.querySelector(`[aria-controls="${formId}"]`);
		new QuickEditFormHandler(form, toggle);
	});
});

class QuickEditFormHandler extends QuickFormHandler {
	constructor(form, toggle) {
		super(form, toggle);

		this.container = this.form.closest('.admin-column-acf-form');
		this.row = this.container.closest('td').querySelector('.row-actions');
		this.spinner = this.form.querySelector('.acf-spinner');

		// If there is also a quick add form on the page, we need to handle it in certain places here too
		this.quickAddForm = document.querySelector('.admin-quick-add .acf-form');

		// Initially hide the form
		this.close();

		// On the toggle open event trigger, close this form if it was not the one that triggered the event
		window.addEventListener('cometQuickFormToggle', this.handleOtherFormToggle.bind(this));
	}

	handleToggle(event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		super.handleToggle();
	}

	handleOtherFormToggle(event) {
		const formId = this.form.getAttribute('id');
		if (event.detail.formId !== formId && event.detail.eventType === 'open') {
			this.close();
		}
	}

	handleSubmission(event) {
		this.showLoadingState();
		super.handleSubmission(event, 'custom_acf_inline_form');
	}

	showLoadingState() {
		this?.container?.classList?.add('admin-column-acf-form--loading');
		this.spinner.style.display = 'inline-block';

		this.quickAddForm.dispatchEvent(new Event('disable'));
	}

	clearLoadingState() {
		this?.container?.classList?.remove('admin-column-acf-form--loading');
		this.container.style.display = 'none';
		this.spinner.style.display = 'none';

		this.quickAddForm.dispatchEvent(new Event('reset'));
	}

	processResponse(response) {
		return super.processResponse(response);
	}

	handleResponse(response) {
		super.handleResponse(response);

		if (response?.data?.fields && response?.data?.post_id) {
			Object.entries(response.data.fields).forEach(([key, value]) => {
				const text = document.querySelector(`.acf-field-value[data-field-key="${key}"][data-post-id="${response.data.post_id}"]`);
				if (text) {
					// Link field
					if (response.data.fields['field__event__link'] &&
						Object.keys(response.data.fields['field__event__link']).includes('url') &&
						Object.keys(value).includes('title')) {
						text.innerHTML = `<a href="${value.url}" target="_blank">${value.title}</a>`;
					}
					if (typeof value === 'string') {
						text.innerHTML = this.formatData(value);
					}
					if (typeof value === 'object') {
						// Probably a date range field
						if (Object.values(value).length === 2) {
							text.innerHTML = Object.values(value)
								.map(val => this.formatData(val))
								.join(' - ');
						}
						else {
							text.innerHTML = Object.values(value).map(val => {
								if (typeof val === 'string') {
									return this.formatData(val);
								}
								if (typeof val === 'object') {
									return Object.values(val).map(v => this.formatData(v))
										.filter(v => v !== null)
										.join('<br>');
								}
							}).join('<br> ');
						}
					}
				}
			});
		}


		this.clearLoadingState();
	}

	formatData(value) {
		if (typeof value === 'string' && value === '') return null;

		if (typeof value === 'object') {
			return Object.values(value).map(val => this.formatData(val)).join('<br>');
		}

		if (typeof value === 'string') {
			const year = value.substring(0, 4);
			const month = value.substring(4, 6);
			const day = value.substring(6, 8);
			const date = new Date(year, month - 1, day);

			if (isNaN(date.getTime())) {
				// Invalid date
				return value;
			}

			return date.toLocaleDateString('en-US', {
				month: 'long',
				day: 'numeric',
				year: 'numeric'
			});
		}

		return value;
	}

	reset() {
		super.reset();
		this.close();
	}

	cancel() {
		this.reset();
		this.close();
	}

	close() {
		this.form.setAttribute('aria-hidden', 'true');
		this.toggle.setAttribute('aria-expanded', 'false');
		super.dispatchToggleEvent('close');
	}
}
