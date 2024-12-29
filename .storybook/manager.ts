import { addons } from '@storybook/manager-api';
import { themes } from '@storybook/theming';

addons.setConfig({
	theme: themes.light,
	sidebar: {
		showRoots: true,
		filters: {
			// Only show Docs pages in the sidebar
			// patterns: (item) => {
			// 	return item.name === 'Docs';
			// }
		}
	},
});
