import '../../../vendor/feimosi/baguettebox.js/src/baguetteBox.js';

window.addEventListener('load', function() {
	if(!document.querySelector('.gallery')) return;

	window.baguetteBox.run('.gallery');
});
