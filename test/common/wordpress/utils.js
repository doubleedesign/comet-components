export function removeStrayDivTags(html) {
	// Match <div> tags with no attributes, even when nested
	const regex = /<div>(?:[^<]|<(?!div>))*<\/div>/g;

	let prevHtml;
	let currentHtml = html;

	// Keep processing until no more changes are made
	do {
		prevHtml = currentHtml;
		currentHtml = prevHtml.replace(regex, function(match) {
			// Remove the outer div tags
			return match.slice(5, -6);
		});
	} while (prevHtml !== currentHtml);

	return currentHtml;
}
