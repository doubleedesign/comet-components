import Tab from '../../../vendor/twbs/bootstrap/js/src/tab.js';

const triggerTabList = document.querySelectorAll('[role="tab"]');

// On page load, show the first tab
if (triggerTabList.length) {
	const firstTab = new Tab(triggerTabList[0]);
	firstTab.show();
}

triggerTabList.forEach(triggerEl => {
	const tabTrigger = new Tab(triggerEl);

	triggerEl.addEventListener('click', event => {
		event.preventDefault();
		tabTrigger.show();
	});
});
