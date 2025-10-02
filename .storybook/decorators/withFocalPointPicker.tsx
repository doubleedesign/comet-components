export const withFocalPointPicker = (Story, context) => {
	const content = Story();
	const htmlString = typeof content === 'string'
		? content
		: content.outerHTML;

	// Parse HTML and extract body
	const parser = new DOMParser();
	const doc = parser.parseFromString(htmlString, 'text/html');
	// And any external CSS or JS in the head
	const headContent = doc.head.innerHTML.split('\n').map(line => line.trim()).filter(line => line.length > 0);
	if (headContent.length > 0) {
		const head = document.getElementsByTagName('head')[0];
		headContent.forEach(line => {
			if (line.startsWith('<link') || line.startsWith('<style')) {
				head.insertAdjacentHTML('beforeend', line);
			}
			if (line.startsWith('<script') && line.endsWith('</script>')) {
				head.insertAdjacentHTML('beforeend', line);
			}
		});
	}

	// Wrap it in the Vue app
	const wrapper = document.createElement('div');
	wrapper.setAttribute('class', 'with-focal-point-picker');
	// const app = createApp(VueWrapper, { content: doc.body.innerHTML });
	// app.mount(wrapper);

	return wrapper;
};
