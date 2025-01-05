import { addons } from '@storybook/manager-api';
import { themes } from '@storybook/theming';
import './manager.css';

addons.setConfig({
	theme: themes.light,
	sidebar: {
		showRoots: true,
		filters: {
			//Customise which stories are shown in the sidebar
			patterns: (item) => {
				return !item.tags?.includes('docsOnly');
			}
		}
	},
});

