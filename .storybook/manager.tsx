import { addons } from '@storybook/manager-api';
import comet from './theme.ts';
import './manager.css';

addons.setConfig({
	theme: comet,
	sidebar: {
		showRoots: true,
		filters: {
			//Customise which stories are shown in the sidebar
			patterns: (item) => {
				//return !item.tags?.includes('docsOnly') || item.type === 'docs';
				return item.type === 'docs';
			}
		}
	},
});

