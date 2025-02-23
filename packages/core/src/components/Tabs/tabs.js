import Tab from '../../../../vendor/twbs/bootstrap/js/src/tab.js';

const triggerTabList = document.querySelectorAll('[role="tab"]');
triggerTabList.forEach(triggerEl => {
	const tabTrigger = new Tab(triggerEl);

	triggerEl.addEventListener('click', event => {
		event.preventDefault();
		tabTrigger.show();
	});
});
