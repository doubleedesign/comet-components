document.addEventListener('DOMContentLoaded', function () {
	// Target the admin post list for Events only
	if(document.body.classList.contains('post-type-event') && document.body.classList.contains('edit-php')) {

		// Handle expand/collapse of the Quick Add form
		const quickAddBox = document.querySelector('.admin-quick-add');
		const quickAddToggle = quickAddBox.querySelector('.postbox-header button');
		const quickAddForm = quickAddBox.querySelector('.acf-form');
		if(quickAddToggle && quickAddForm) {
			quickAddToggle.addEventListener('click', function() {
				const isExpanded = quickAddToggle.getAttribute('aria-expanded') === 'true';
				if(isExpanded) {
					quickAddToggle.setAttribute('aria-expanded', 'false');
					quickAddForm.setAttribute('aria-hidden', 'true');
				}
				else {
					quickAddToggle.setAttribute('aria-expanded', 'true');
					quickAddForm.setAttribute('aria-hidden', 'false');
				}
			});
		}

		// Handle Quick Add form submissions
		// Its default submission causes a white screen, so we need some custom processing
		if(quickAddForm) {
			quickAddForm.addEventListener('submit', function (event) {
				event.preventDefault();
				event.stopPropagation();

				const formAction = this.getAttribute('action');
				const formData = new FormData(this);
				// Custom flag to identify the request for custom additional processing in PHP
				formData.append('custom_acf_quick_add_form', 'true');

				fetch(formAction, {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
						cache: 'no-store',
						credentials: 'same-origin'
					}
				})
					.then(response => {
						if (response.status === 200 || response.status === 201) {
							return handle_maybe_json_response(response);
						}
					})
					.then(response => {
						// Refresh the page with the new post ID in the URL, which the PHP will use to insert an admin confirmation message
						if (response?.data?.post_id) {
							const postId = response.data.post_id;
							const url = new URL(window.location.href);
							url.searchParams.set('added', postId);
							window.location.href = url.toString();
						}
						else {
							alert('Problem processing the response, please refresh the page to see the updated data');
						}
					})
					.catch(error => {
						console.error(error);
					});
			});

			// Clear validation messages on reset
			quickAddForm.addEventListener('reset', function () {
				unvalidate_quick_add_form();
			});
		}

		// Show/hide inline ACF forms (added in the custom columns in Events.php)
		const acfInlineButtons = document.querySelectorAll('.button-link--acf');
		acfInlineButtons.forEach(function (button) {
			button.addEventListener('click', function (event) {
				event.preventDefault();

				// Close any other open forms and show their "Quick edit" links
				const openForms = document.querySelectorAll('.admin-column-acf-form');
				openForms.forEach(function (form) {
					form.style.display = 'none';
				});
				const openRows = document.querySelectorAll('.row-actions');
				openRows.forEach(function (row) {
					row.style.display = 'block';
				});

				// Show this form and hide the "Quick edit" link
				const row = this.closest('.row-actions');
				const formId = this.getAttribute('aria-controls');
				if (formId) {
					const form = document.querySelector(`.admin-column-acf-form[data-form-id="${formId}"]`);
					if (form) {
						form.style.display = 'flex';
						row.style.display = 'none';
					}
				}
			});
		});

		// Handle inline ACF form submission and resets
		const forms = document.querySelectorAll('.admin-column-acf-form .acf-form');
		forms.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				event.preventDefault();
				event.stopImmediatePropagation();

				dim_quick_add_form();

				const parent = this.closest('.admin-column-acf-form');
				const spinner = this.querySelector('.acf-spinner');
				const row = parent.closest('td').querySelector('.row-actions');

				if (parent && spinner) {
					parent.classList.add('admin-column-acf-form--loading');
					spinner.style.display = 'inline-block';
				}

				const formAction = this.getAttribute('action');
				const formData = new FormData(this);
				// Custom flag to identify the request for custom additional processing in PHP
				formData.append('custom_acf_inline_form', 'true');

				fetch(formAction, {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
						cache: 'no-store',
						credentials: 'same-origin'
					}
				})
					.then(response => {
						if (response?.status === 200) {
							if (parent && spinner) {
								parent.style.display = 'none';
								parent.classList.remove('admin-column-acf-form--loading');
								spinner.style.display = 'none';
								row.style.display = 'block';
							}
						}

						unvalidate_quick_add_form();

						return handle_maybe_json_response(response);
					})
					.then(response => {
						if(response?.data?.fields && response?.data?.post_id) {
							Object.entries(response.data.fields).forEach(([key, value]) => {
								const text = document.querySelector(`.acf-field-value[data-field-key="${key}"][data-post-id="${response.data.post_id}"]`);
								if(text) {
									// Link field
									if(response.data.fields['field_680b0560ee067'] &&
										Object.keys(response.data.fields['field_680b0560ee067']).includes('url') &&
										Object.keys(value).includes('title')) {
										text.innerHTML = `<a href="${value.url}" target="_blank">${value.title}</a>`;
									}
									if(typeof value === 'string') {
										text.innerHTML = format_data(value);
									}
									if(typeof value === 'object') {
										// Probably a date range field
										if(Object.values(value).length === 2) {
											text.innerHTML = Object.values(value)
												.map(val => format_data(val))
												.join(' - ');
										}
										else {
											text.innerHTML = Object.values(value).map(val => {
												if(typeof val === 'string') {
													return format_data(val);
												}
												if(typeof val === 'object') {
													return Object.values(val).map(v => format_data(v))
														.filter(v => v !== null)
														.join('<br>');
												}
											}).join('<br> ');
										}
									}
								}
							});
						}

						return response;
					})
					.catch(error => {
						console.error(error);
					});

				unvalidate_form(quickAddForm);
			});

			form.addEventListener('reset', function (event) {
				const parent = this.closest('.admin-column-acf-form');
				const row = parent.closest('td').querySelector('.row-actions');
				if (parent) {
					parent.style.display = 'none';
					row.style.display = 'block';
				}
			});
		});
	}
});

function format_data(value) {
	if(typeof value === 'string' && value === '') return null;

	if(typeof value === 'object') {
		return Object.values(value).map(val => format_data(val)).join('<br>');
	}

	if(typeof value === 'string') {
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

function handle_maybe_json_response(response) {
	// Check if the response is JSON
	const contentType = response.headers.get('content-type');
	if (contentType && contentType.includes('application/json')) {
		return response.json();
	}

	// If not JSON, fetch the JSON out of the HTML page that gets returned, which is expected to be the last line
	return response.text().then(text => {
		const lastLine = text.split('\n').pop();
		try {
			return JSON.parse(lastLine);
		}
		catch (e) {
			alert('Problem processing the response, please refresh the page to see the updated data');
			console.error(e);
		}
	});
}

/**
 * The new post form gets validated by ACF on any other form submit on the admin page, and I can't find a way to stop it
 * so these are hacks to make it look like this isn't what's happening and remove the validation messages if the submission came from another form
 * (add this to other forms' submit events).
 * unvalidate_quick_add_form() can also be used to clear the validation messages on reset
 */
function dim_quick_add_form() {
	const form = document.querySelector('.admin-quick-add .acf-form');
	if(!form) return;

	form.style.opacity = 0.25;
	form.style['pointer-events'] = 'none';

	const button = form.querySelector('.acf-form-submit');
	if(button) {
		button.style.display = 'none';
	}
}
function unvalidate_quick_add_form() {
	const form = document.querySelector('.admin-quick-add .acf-form');
	if(!form) return;

	form.classList.remove('is-validating');
	form.classList.remove('is-invalid');

	const errorMessages = form.querySelectorAll('.acf-error-message');
	errorMessages.forEach(function (message) {
		message.remove();
	});

	// Undo the styling hacks from dim_quick_add_form
	setTimeout(() => {
		form.style.opacity = 1;
		form.style['pointer-events'] = 'auto';
		const button = form.querySelector('.acf-form-submit');
		if (button) {
			button.style.display = 'inline-block';
		}
	}, 300);

}
