import '../../../vendor/feimosi/baguettebox.js/src/baguetteBox.js';

window.addEventListener('load', function () {
	if (!document.querySelector('[data-lightbox="true"]')) return;

	window.baguetteBox.run('[data-lightbox="true"]');
});
