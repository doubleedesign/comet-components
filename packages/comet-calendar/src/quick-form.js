/**
 * Abstract class for handling custom admin form submissions:
 * - Quick Add (ACF form at the top of the admin list for events)
 * - Quick Edit (ACF forms in custom columns for each event in the admin list)
 */
export class QuickFormHandler {
	constructor(form, toggle) {
		if (this.constructor === QuickFormHandler) {
			throw new Error('Cannot instantiate abstract class QuickForm directly');
		}

		this.form = form;
		this.toggle = toggle;

		this.toggle.addEventListener('click', this.handleToggle.bind(this));
		this.form.addEventListener('submit', this.handleSubmission.bind(this), true);
		this.form.addEventListener('reset', this.reset.bind(this), true);
		this.form.addEventListener('disable', this.disable.bind(this));
	}

	handleToggle() {
		this.toggle.toggleAttribute('aria-expanded');
		this.form.toggleAttribute('aria-hidden');
		this.dispatchToggleEvent();
	}

	dispatchToggleEvent(eventType = undefined) {
		if (!eventType) {
			eventType = this.form.hasAttribute('aria-hidden') ? 'close' : 'open';
		}

		window.dispatchEvent(new CustomEvent('cometQuickFormToggle', {
			detail: {
				formId: this.form.getAttribute('id'),
				eventType: eventType || 'toggle',
			}
		}));
	}

	/**
	 * @param event
	 * @param flag - Custom flag to identify the request for custom additional processing in PHP
	 */
	handleSubmission(event, flag) {
		event.preventDefault();
		event.stopImmediatePropagation();
		event.stopPropagation();

		const formAction = this.form.getAttribute('action');
		const formData = new FormData(this.form);
		formData.append(flag, 1);

		fetch(formAction, {
			method: 'POST',
			body: formData,
			cache: 'no-store',
			credentials: 'same-origin',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Accept': 'application/json',
			}
		}).then(response => {
			return this.processResponse(response);
		}).then(response => {
			this.handleResponse(response);
		}).catch(error => {
			console.error(error);
		});
	}

	/**
	 * The new post (quick add) form gets validated by ACF on any other form submit on the admin page, and I can't find a way to stop it,
	 * so when our quick edit forms are submitted we make it look like this isn't what's happening using visuallyDim()
	 * and remove the validation messages if the submission came from another form using reset() with a reference to that form.
	 */
	visuallyDim() {
		this.form.style.opacity = 0.25;
		this.form.style['pointer-events'] = 'none';
		const button = this.form.querySelector('.acf-form-submit');
		if (button) {
			button.style.display = 'none';
		}
	}

	visuallyUnDim() {
		this.form.style.opacity = 1;
		this.form.style['pointer-events'] = 'auto';
		const button = this.form.querySelector('.acf-form-submit');
		if (button) {
			button.style.display = 'inline-block';
		}
	}

	disable() {
		this.form.addEventListener('submit', (event) => {
			event.preventDefault();
			event.stopImmediatePropagation();
			event.stopPropagation();
		});
		this.form.querySelectorAll('fieldset').forEach(element => element.disabled = true);
		this.visuallyDim();
	}

	reset() {
		this.form.querySelectorAll('fieldset').forEach(element => element.disabled = false);
		this.clearAcfValidation().then(() => this.visuallyUnDim());
	}

	async clearAcfValidation() {
		// Hack around race condition by keeping on clearing the validation classes/messages for a short while
		// and not allowing the caller to continue until that's done
		const keepChecking = setInterval(() => {
			this.form?.classList.remove('is-validating');
			this.form?.classList.remove('is-invalid');
			document.querySelectorAll('.acf-error-message').forEach(message => message.remove());
		}, 200);

		return new Promise((resolve) => {
			setTimeout(() => {
				clearInterval(keepChecking);
				resolve();
			}, 2000);
		});
	}

	processResponse(response) {
		// Check if the response is JSON
		const contentType = response.headers.get('content-type');
		if (contentType && contentType.includes('application/json')) {
			return response.json();
		}

		// If not JSON, fetch the JSON out of the HTML page that gets returned, which is expected to be the last line
		return response.text()
			.then(text => {
				const lastLine = text.split('\n').pop();
				try {
					return JSON.parse(lastLine);
				}
				catch (error) {
					alert('Problem processing the response, please refresh the page to see the updated data');
					console.error(error);

					return null;
				}
			});
	}

	handleResponse(response) {
		// Child classes should implement their own response data handling
	}
}
